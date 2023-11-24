<?php

namespace App\Interfaces\Repository;

use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    /**
     * @param array $params
     * @return array
     */
    public function formatParams(array $params) :array;
}
