<?php

namespace App\Infra\Repository\Configuration;

use App\Abstracts\AbstractRepository;
use App\Models\UnderSubGroupUser;
use Illuminate\Database\Eloquent\Model;

class UnderSubGroupUserRepository extends AbstractRepository
{

    /**
     * @var Model
     */
    protected $model;

    public function __construct(UnderSubGroupUser $model)
    {
        $this->model = $model;
    }

    public function formatParams(array $params): array
    {
        return [
            'under_sub_group_id' => $this->getAttribute($params, 'under_sub_group_id'),
            'user_id' => $this->getAttribute($params, 'user_id'),
        ];
    }
}
