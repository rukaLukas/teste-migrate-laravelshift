<?php

namespace App\Infra\Repository;

use App\Abstracts\AbstractRepository;
use App\Models\Region;
use Illuminate\Database\Eloquent\Model;

class RegionRepository extends AbstractRepository
{

    /**
     * @var Model
     */
    protected $model;

    public function __construct(Region $model)
    {
        $this->model = $model;
    }

    public function formatParams(array $params): array
    {
        return [
            'name' => $this->getAttribute($params, 'name'),
        ];
    }
}
