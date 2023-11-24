<?php

namespace App\Infra\Repository\Configuration;

use App\Abstracts\AbstractRepository;
use App\Models\Group;
use Illuminate\Database\Eloquent\Model;

class GroupRepository extends AbstractRepository
{

    /**
     * @var Model
     */
    protected $model;

    public function __construct(Group $model)
    {
        $this->model = $model;
    }

    public function getPaginationList(array $params, array $with = [], int $perPage = null)
    {        
        return parent::getPaginationList($params, ['groupUsers', 'groupUsers.user', 'subGroups']);
    }

    public function formatParams(array $params): array
    {
        return [
            'name' => $this->getAttribute($params, 'name'),
            'county_id' => $this->getAttribute($params, 'county_id')
        ];
    }
}
