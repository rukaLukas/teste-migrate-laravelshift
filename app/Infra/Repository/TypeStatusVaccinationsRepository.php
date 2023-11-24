<?php

namespace App\Infra\Repository;

use App\Abstracts\AbstractRepository;
use App\Models\TypeStatusVaccination;
use Illuminate\Database\Eloquent\Model;

class TypeStatusVaccinationsRepository extends AbstractRepository
{

    /**
     * @var Model
     */
    protected $model;

    public function __construct(TypeStatusVaccination $model)
    {
        $this->model = $model;
    }

    public function formatParams(array $params): array
    {
        return [
            'name' => $this->getAttribute($params, 'name')
        ];
    }
}
