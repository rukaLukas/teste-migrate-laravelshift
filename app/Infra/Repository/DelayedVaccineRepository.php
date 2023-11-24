<?php

namespace App\Infra\Repository;

use App\Abstracts\AbstractRepository;
use App\Models\DelayedVaccine;
use App\Models\Vaccine;
use Illuminate\Database\Eloquent\Model;

class DelayedVaccineRepository extends AbstractRepository
{

    /**
     * @var Model
     */
    protected $model;

    public function __construct(DelayedVaccine $model)
    {
        $this->model = $model;
    }

    public function formatParams(array $params): array
    {
        return [
            "alert_step_id" => $this->getAttribute($params, 'alert_step_id'),
            "vaccine_id" => Vaccine::Where('uuid', '=', $this->getAttribute($params, 'vaccine_id'))->first()->id, //$this->getAttribute($params, 'vaccine_id'),
        ];
    }
}
