<?php

namespace App\Infra\Repository\Configuration;

use App\Abstracts\AbstractRepository;
use App\Models\Deadline;
use App\Models\MenuCounty;
use Illuminate\Database\Eloquent\Model;

class MenuCountyRepository extends AbstractRepository
{

    /**
     * @var Model
     */
    protected $model;

    public function __construct(MenuCounty $model)
    {
        $this->model = $model;
    }

    public function formatParams(array $params): array
    {
        return [
            'county_id' => $this->getAttribute($params, 'county_id'),
            'menu_id' => $this->getAttribute($params, 'menu_id'),
        ];
    }
}
