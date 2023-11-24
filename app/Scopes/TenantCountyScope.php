<?php

namespace App\Scopes;

use App\Models\Group;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class TenantCountyScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {        
        if (in_array('county_id', $model->getFillable())) {
            if (Auth::user() && Auth::user()->occupation->name != 'Gestor nacional' && Auth::user()->county_id) {                
                $builder->where('county_id', '=', Auth::user()->county_id);
            }  
                    
        }
    }
}