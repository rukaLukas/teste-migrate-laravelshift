<?php

namespace App\Infra\Repository;

use App\Abstracts\AbstractRepository;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Model;

class ProfileRepository extends AbstractRepository
{

    /**
     * @var Model
     */
    protected $model;

    public function __construct(Profile $model)
    {
        $this->model = $model;
    }

    public function formatParams(array $params): array
    {
        return [
            'name' => $this->getAttribute($params, 'name')
        ];
    }
}
