<?php

namespace App\Models;

use App\Abstracts\AbstractModel;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Arr;

class UnderSubGroup extends AbstractModel
{
    use HasFactory, HasUuid;

    protected $fillable = ['name', 'uuid'];
    protected $guarded = ['id'];
    public $timestamps = true;

    public function subGroup()
    {
        return $this->belongsTo(SubGroup::class);
    }

    public function underSubGroupUsers()
    {
        return $this->hasMany(UnderSubGroupUser::class, 'under_sub_group_id');
    }

    public function scopeQuery(Builder $queryBuilder, $params = []): Builder
    {        
        if (Arr::has($params, 'county_id')) {            
            $underSubGroupFilter = app()->make('App\Models\Scopes\Territory\UnderSubGroupFilter');
            return $underSubGroupFilter->filter($params);
        }
        if (Arr::has($params, 'sub_group_id')) {            
            $model = SubGroup::where('uuid', '=', $params['sub_group_id'])->first();
            return $queryBuilder->where('sub_group_id', '=', $model->id);
        }
        return $queryBuilder;
    }
}
