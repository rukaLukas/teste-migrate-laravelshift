<?php

namespace App\Infra\Repository\Configuration;

use App\Abstracts\AbstractRepository;
use App\Models\TargetPublic;
use Illuminate\Database\Eloquent\Model;

class TargetPublicRepository extends AbstractRepository
{

    /**
     * @var Model
     */
    protected $model;

    public function __construct(TargetPublic $model)
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
