<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasQueryTrait
{
    public function scopeQuery(Builder $queryBuilder, $params = [])
    {
        return $queryBuilder;
    }
}
