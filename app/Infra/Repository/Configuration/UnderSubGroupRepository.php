<?php

namespace App\Infra\Repository\Configuration;

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

    public function getPaginationList(array $params, array $with = [], int $perPage = null)
    {        
        return parent::getPaginationList($params, ['underSubGroupUsers', 'underSubGroupUsers.user'], 60);
    }

    public function formatParams(array $params): array
    {
        return [
            'name' => $this->getAttribute($params, 'name'),
        ];
    }
}
