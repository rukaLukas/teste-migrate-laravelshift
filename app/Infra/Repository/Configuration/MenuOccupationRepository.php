<?php

namespace App\Infra\Repository\Configuration;

use App\Abstracts\AbstractRepository;
use App\Models\MenuOccupation;
use Illuminate\Database\Eloquent\Model;

class MenuOccupationRepository extends AbstractRepository
{

    /**
     * @var Model
     */
    protected $model;

    public function __construct(MenuOccupation $model)
    {
        $this->model = $model;
    }

    public function formatParams(array $params): array
    {
        return [
            'occupation_id' => $this->getAttribute($params, 'occupation_id'),
            'menu_id' => $this->getAttribute($params, 'menu_id'),
        ];
    }
}
