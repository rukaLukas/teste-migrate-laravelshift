<?php

namespace App\Infra\Repository;

use App\Abstracts\AbstractRepository;
use App\Models\UnderSubGroup;
use Illuminate\Database\Eloquent\Model;

class UnderSubGroupRepository extends AbstractRepository
{

    /**
     * @var Model
     */
    protected $model;

    public function __construct(UnderSubGroup $model)
    {
        $this->model = $model;
    }

    public function formatParams(array $params): array
    {
        return [
            'name' => $this->getAttribute($params, 'name'),
            'sub_group_id' => $this->getAttribute($params, 'sub_group_id'),            
        ];
    }
}
