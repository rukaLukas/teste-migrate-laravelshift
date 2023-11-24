<?php

namespace App\Infra\Repository;

use App\Models\Breed;
use App\Models\ReasonDelayVaccine;
use Illuminate\Support\Facades\Auth;
use App\Abstracts\AbstractRepository;
use Illuminate\Database\Eloquent\Model;

class BreedRepository extends AbstractRepository
{

    /**
     * @var Model
     */
    protected $model;

    public function __construct(Breed $model)
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
