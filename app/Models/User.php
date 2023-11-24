<?php

namespace App\Models;

use Carbon\Carbon;
use App\Helper\Number;
use App\Traits\HasUuid;
use Illuminate\Support\Arr;
use App\Traits\HasQueryTrait;
use App\Traits\HasTenantCounty;
use Laravel\Sanctum\HasApiTokens;
use App\Mail\Auth\RecoverPassword;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens,
        HasFactory,
        Notifiable,
        HasQueryTrait,
        TwoFactorAuthenticatable,
        HasUuid,
        HasTenantCounty,
        SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'cpf',
        'cell_phone',
        'office_phone',
        'position',
        'profile_id',
        'government_agency_id',
        'uuid',
        'id',
        'avatar',
        'county_id'
    ];

    protected $dates = ['deleted_at'];

    protected $appends = [
        'photo_url',
        'name_menu',
        'birthdate_formatted',
        'cpf_formatted',
        'cell_phone_formatted',
        'office_phone_formatted',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function underSubGroup()
    {
        return $this->hasOneThrough(
            UnderSubGroup::class,
            UnderSubGroupUser::class,
            'user_id',
            'id',
            'id',
            'under_sub_group_id'
        );
    }

    public function subGroup()
    {
        return $this->hasOneThrough(
            SubGroup::class,
            SubGroupUser::class,
            'user_id',
            'id',
            'id',
            'sub_group_id'
        );
    }

    public function group()
    {
        return $this->hasOneThrough(
            Group::class,
            GroupUser::class,
            'user_id',
            'id',
            'id',
            'group_id'
        );
    }

    public function alerts()
    {
        return $this->hasMany(Alert::class);
    }

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    // public function governmentOffices()
    // {
    //     return $this->hasMany(GovernmentOffice::class);
    // }

    public function governmentOffices()
    {
        return $this->hasMany(GovernmentOfficeUser::class);
    }

    public function ClosedAlert()
    {
        return $this->hasMany(ClosedAlert::class);
    }

    public function forwarding()
    {
        return $this->hasMany(Forwarding::class);
    }

    public function pendingAlert()
    {
        return $this->hasOne(PendingAlert::class);
    }

    public function county()
    {
        return $this->belongsTo(County::class);
    }

    public function caseStep()
    {
        return $this->hasMany(CaseStep::class);
    }

    public function commentsRecords()
    {
        return $this->hasMany(CommentsRecord::class);
    }

    public function groupUser()
    {
        return $this->hasMany(GroupUser::class);
    }

    public function occupation()
    {
        return $this->belongsTo(Occupation::class);
    }

    public function auditLogin()
    {
        return $this->hasMany(AuditLogin::class);
    }

    public function hasAccess($access)
    {
        $occupation = array_search($this->occupation->name, Occupation::OCCUPATIONS);
        $permissions = Occupation::PERMISSIONS[$occupation];
        return in_array($access, $permissions);
    }

    public function accession()
    {
        $user = $this->accessionPrefeito();
        if (!$user->first()) {
            $user = $this->accessionGestorPolitico();
        }
        return $user;
    }

    public function accessionPrefeito()
    {
        return $this->hasOne(Accession::class, 'prefeito_id');
    }

    public function accessionGestorPolitico()
    {
        return $this->hasOne(Accession::class, 'gestor_politico_id');
    }

    public function accessionByCounty()
    {
        return $this->hasOneThrough(
            Accession::class,
            County::class,
            'id',
            'county_id',
            'county_id',
            'id'
        );
    }

    public function menuByCounty()
    {
        return $this->hasManyThrough(
            MenuCounty::class,
            County::class,
            'id',
            'county_id',
            'county_id',
            'id'
        );
    }

    public function getPhotoUrlAttribute()
    {
        $disk = Storage::disk('public');
        if ($disk->exists('images/users/' . $this->id . '/' . $this->avatar)) {
            return url('storage/images/users/' . $this->id . '/' . $this->avatar);
        }
        // $response = Http::get('https://randomuser.me/api/');
        // return $response->json()['results'][0]['picture']['medium'];
        return 'https://cdn-icons-png.flaticon.com/512/1361/1361913.png';
    }

    public function getNameMenuAttribute()
    {
        $name = explode(' ', $this->name);
        return $name[0];
    }

    public function getBirthdateFormattedAttribute()
    {
        return $this->birthdate ? Carbon::createFromFormat(
            'Y-m-d',
            $this->birthdate
        )->format('d/m/Y') : '';
    }

    public function getCpfFormattedAttribute()
    {
        if (strlen($this->cpf) === 11) {
            return substr($this->cpf, 0, 3) . '.'
                . substr($this->cpf, 3, 3) . '.'
                . substr($this->cpf, 6, 3) . '-'
                . substr($this->cpf, 9);
        }
    }

    public function getOfficePhoneFormattedAttribute()
    {
        return Number::phoneToView($this->cell_phone);
    }

    public function getCellPhoneFormattedAttribute()
    {
        return Number::phoneToView($this->office_phone);
    }


    /**
     * Send a password reset notification to the user.
     *
     * @param string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this['token'] = $token;
        Mail::to($this->email)->send(
            new RecoverPassword($this)
        );
    }

    public function scopeQuery(Builder $queryBuilder, $params = []): Builder
    {
        if (Arr::exists($params, 'county_id')) {
            $queryBuilder->where('county_id', '=', $params['county_id']);
        }

        if (Arr::exists($params, 'searchAllFields')) {
            foreach ($queryBuilder->getModel()->getFillable() as $attribute) {
                $queryBuilder->orWhere($attribute, 'like', '%' . strtolower($params['searchAllFields']) . '%');
            }
        }

        if (Arr::exists($params, 'notIn')) {
            $ids = explode(',', $params['notIn']);
            $queryBuilder->whereNotIn('id', $ids);
        }

        $queryBuilder->orderBy('name');

        return $queryBuilder;
    }
}
