<?php

namespace App\Interfaces\Model;

use Illuminate\Database\Eloquent\Builder;

interface ModelInterface
{
	public function scopeQuery(Builder $queryBuilder, $params = []);
}
