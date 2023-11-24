<?php

namespace App\Infra\Repository;

use App\Models\VaccineRoom;
use App\Abstracts\AbstractRepository;
use Illuminate\Database\Eloquent\Model;

class VaccineRoomRepository extends AbstractRepository
{

    /**
     * @var Model
     */
    protected $model;

    public function __construct(VaccineRoom $model)
    {
        $this->model = $model;
    }

    public function formatParams(array $params): array
    {
        return [
            'name' => $this->getAttribute($params, 'name'),
            'postalcode' => $this->getAttribute($params, 'postalcode'),
            'street' => $this->getAttribute($params, 'street'),
            'state' => $this->getAttribute($params, 'state'),
            'city' => $this->getAttribute($params, 'city'),
            'district' => $this->getAttribute($params, 'district'),
        ];
    }
}
