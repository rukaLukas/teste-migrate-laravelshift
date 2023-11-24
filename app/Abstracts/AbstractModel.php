<?php

namespace App\Abstracts;

use App\Interfaces\Model\ModelInterface;
use App\Traits\HasTenantCounty;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Psy\Util\Str;

class AbstractModel extends Model implements ModelInterface
{
//    use HasTenantCounty;

    protected $appends = [
        'created_at_to_human',
        'created_at_formated',
    ];

    /**
     * @return string
     */
    public function getCreatedAtToHumanAttribute()
    {
        return Carbon::createFromTimeStamp(strtotime($this->created_at))->diffForHumans();
    }

    /**
     * @return string
     */
    public function getCreatedAtFormatedAttribute()
    {
        return Carbon::createFromTimeStamp(strtotime($this->created_at))->format('d/m/Y');
    }

    /**
     * @param Builder $queryBuilder
     * @param array $params
     * @return Builder
     */
    public function scopeQuery(Builder $queryBuilder, $params = []): Builder
    {
        if (Arr::exists($params, 'searchAllFields')) {
            foreach ($queryBuilder->getModel()->getFillable() as $attribute) {
                $queryBuilder->orWhere($attribute, 'like', '%' . strtolower($params['searchAllFields']) . '%');
            }
        }

        foreach ($queryBuilder->getModel()->getFillable() as $attribute) {
            if (Arr::exists($params, $attribute)) {
                $paramValue = Arr::get($params, $attribute);

                if (\Illuminate\Support\Str::contains($attribute,'id')) {
                    $queryBuilder->where($attribute, '=', $paramValue);
                } else {
                    $queryBuilder->where($attribute, 'like', '%' . strtolower($paramValue) . '%');
                }

            }
        }
        return $queryBuilder;
    }
}
