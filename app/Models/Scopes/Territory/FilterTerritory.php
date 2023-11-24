<?php
namespace App\Models\Scopes\Territory;

use Illuminate\Database\Eloquent\Builder;

interface FilterTerritory
{
    public function filter();

    public function setNext(FilterTerritory $next): void;
}