<?php

namespace App\Infra\Repository;

use App\Models\State;
use App\Abstracts\AbstractRepository;
use Illuminate\Database\Eloquent\Model;

class StateRepository extends AbstractRepository
{

    /**
     * @var Model
     */
    protected $model;

    public function __construct(State $model)
    {
        $this->model = $model;
    }

    public function formatParams(array $params): array
    {
        return [
            'name' => $this->getAttribute($params, 'name'),
            'region_id' => $this->getAttribute($params, 'region_id'),
            'sigla' => $this->getAttribute($params, 'sigla'),
            'codigo_uf' => $this->getAttribute($params, 'codigo_uf'),
        ];
    }
}
