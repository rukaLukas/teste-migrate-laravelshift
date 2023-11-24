<?php

namespace App\Models;

use App\Abstracts\AbstractModel;
use App\Helper\AuthHelper;
use App\Scopes\TenantCountyScope;
use App\Traits\HasTenantCounty;
use App\Traits\HasUuid;
use http\Client\Request;
use http\Client\Response;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class Group extends AbstractModel
{
    use HasFactory, HasUuid, HasTenantCounty;

    protected $primaryKey = 'id';
    protected $fillable = ['name', 'uuid', 'county_id'];
    protected $guarded = ['id'];
    public $timestamps = true;

    public function groupUsers()
    {
        return $this->hasMany(GroupUser::class, 'group_id');
    }

    public function subGroups()
    {
        return $this->hasMany(SubGroup::class);
    }

    public function county()
    {
        return $this->belongsTo(County::class);
    }

    protected static function booting()
    {
        parent::booting();
        static::addGlobalScope(new TenantCountyScope());
    }
}
