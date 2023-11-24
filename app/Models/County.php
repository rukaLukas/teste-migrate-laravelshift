<?php

namespace App\Models;

use App\Abstracts\AbstractModel;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Arr;

class County extends AbstractModel
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'uuid',
        'name',
        'state_id',
    ];

    protected $guarded = ['id'];

    public $timestamps = true;

    public function scopeQuery(Builder $queryBuilder, $params = []): Builder
    {
        if (Arr::has($params, 'state_id')) {
            $params['state_id'] = State::where(['uuid' => $params['state_id']])->first()->id;
            return parent::scopeQuery($queryBuilder, $params);
        }
        return $queryBuilder;
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    public function governmentOffices()
    {
        return $this->hasMany(GovernmentOffice::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function alerts()
    {
        return $this->hasMany(Alert::class);
    }

    public static function latestSeederId()
    {
        return County::all()->last()->id;
    }

    public function accessions()
    {
        return $this->hasMany(Accession::class);
    }
}
