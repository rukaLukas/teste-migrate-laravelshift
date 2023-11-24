<?php

namespace App\Infra\Repository\Configuration;

use App\Abstracts\AbstractRepository;
use App\Models\SubGroup;
use Illuminate\Database\Eloquent\Model;

class SubGroupRepository extends AbstractRepository
{

    /**
     * @var Model
     */
    protected $model;

    public function __construct(SubGroup $model)
    {
        $this->model = $model;
    }

    public function getPaginationList(array $params, array $with = [], int $perPage = null)
    {
        return parent::getPaginationList($params, ['subGroupUsers', 'subGroupUsers.user']);
    }

    public function formatParams(array $params): array
    {
        return [
            'name' => $this->getAttribute($params, 'name'),
            'group_id' => $this->getAttribute($params, 'group_id'),
        ];
    }
}
