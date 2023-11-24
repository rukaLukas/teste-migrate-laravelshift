<?php

namespace App\Infra\Repository\Configuration;

use App\Abstracts\AbstractRepository;
use App\Models\GroupUser;
use Illuminate\Database\Eloquent\Model;

class GroupUserRepository extends AbstractRepository
{

    /**
     * @var Model
     */
    protected $model;

    public function __construct(GroupUser $model)
    {
        $this->model = $model;
    }

    public function formatParams(array $params): array
    {
        return [
            'group_id' => $this->getAttribute($params, 'group_id'),
            'user_id' => $this->getAttribute($params, 'user_id'),
        ];
    }
}
