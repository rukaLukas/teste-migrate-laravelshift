<?php

namespace App\Infra\Repository\Configuration;

use App\Abstracts\AbstractRepository;
use App\Models\Deadline;
use App\Models\Menu;
use Illuminate\Database\Eloquent\Model;

class MenuRepository extends AbstractRepository
{

    /**
     * @var Model
     */
    protected $model;

    public function __construct(Menu $model)
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
