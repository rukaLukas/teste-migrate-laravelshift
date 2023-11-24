<?php

namespace App\Models;

use App\Abstracts\AbstractModel;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubGroup extends AbstractModel
{
    use HasFactory, HasUuid;

    protected $fillable = ['name', 'uuid', 'group_id'];
    protected $guarded = ['id'];
    public $timestamps = true;

    public function subGroupUsers()
    {
        return $this->hasMany(SubGroupUser::class, 'sub_group_id');
    }

    public function underSubGroups()
    {
        return $this->hasMany(UnderSubGroup::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function scopeQuery(Builder $queryBuilder, $params = []): Builder
    {
        if (isset($params['group_id']) && !empty($params['group_id'])) {
            $model = Group::where('uuid', '=', $params['group_id'])->first();
            if (!$model) {
                $model = Group::where('id', '=', $params['group_id'])->first();
            }
            return $queryBuilder->where('group_id', '=', $model->id);
        }
        return $queryBuilder;
    }
}
