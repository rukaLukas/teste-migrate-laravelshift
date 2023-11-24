<?php

namespace App\Infra\Repository\Configuration;

use App\Abstracts\AbstractRepository;
use App\Models\SubGroupUser;
use Illuminate\Database\Eloquent\Model;

class SubGroupUserRepository extends AbstractRepository
{

    /**
     * @var Model
     */
    protected $model;

    public function __construct(SubGroupUser $model)
    {
        $this->model = $model;
    }

    public function formatParams(array $params): array
    {
        return [
            'sub_group_id' => $this->getAttribute($params, 'sub_group_id'),
            'user_id' => $this->getAttribute($params, 'user_id'),
        ];
    }
}
