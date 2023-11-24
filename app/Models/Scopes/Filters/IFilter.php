<?php 
namespace App\Models\Scopes\Filters;

use Illuminate\Database\Eloquent\Builder;

interface IFilter
{
    public function filter(Builder $queryBuilder): Builder;
}