<?php

namespace App\Infra\Repository;

use App\Abstracts\AbstractRepository;
use App\Models\Occupation;
use Illuminate\Database\Eloquent\Model;

class OccupationRepository extends AbstractRepository
{

    /**
     * @var Model
     */
    protected $model;

    public function __construct(Occupation $model)
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
