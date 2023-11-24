<?php 
namespace App\Models\Scopes\Filters;

use Illuminate\Database\Eloquent\Builder;

class AlertsPendingFilter implements IFilter
{  
    public function filter(Builder $queryBuilder): Builder
    {                        
        $queryBuilder->whereHas('alerts', function($q) {
            $q->where('user_id', '=', NULL);
        });

        return $queryBuilder;
    }
}