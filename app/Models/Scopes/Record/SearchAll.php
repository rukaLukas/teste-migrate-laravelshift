<?php
namespace App\Models\Scopes\Record;

use App\Models\Alert;
use App\Models\Record;
use App\Models\StatusAlert;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Builder;

class SearchAll implements FilterRecord
{    
    public function filter(Builder $queryBuilder, array $params)
    {
        if (Arr::exists($params, 'searchAllFields')) {
            foreach ($queryBuilder->getModel()->getFillable() as $attribute) {
                $queryBuilder->orWhere($attribute, 'like', '%' . strtolower($params['searchAllFields']) . '%');
            }
            
            foreach ($queryBuilder->getRelation('alerts')->getModel()->getFillable() as $attribute) {
                $queryBuilder->orWhereHas('alerts', function($query) use($attribute, $params) {
                    return $query->where($attribute, 'like', '%' . strtolower($params['searchAllFields']) . '%');
                })->get();
            }          
        }      

        return $queryBuilder; 
    }

    public function setNext(FilterRecord $next): void
    {
        //
    }
}