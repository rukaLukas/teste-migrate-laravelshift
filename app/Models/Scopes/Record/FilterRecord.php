<?php
namespace App\Models\Scopes\Record;

use Illuminate\Database\Eloquent\Builder;

interface FilterRecord
{
    public function filter(Builder $queryBuilder, array $params);

    public function setNext(FilterRecord $next): void;
}