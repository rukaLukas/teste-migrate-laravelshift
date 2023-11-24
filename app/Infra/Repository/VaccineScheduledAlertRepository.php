<?php

namespace App\Infra\Repository;

use App\Models\Alert;
use App\Models\Record;
use App\Models\Vaccine;
use App\Abstracts\AbstractRepository;
use App\Models\VaccineScheduledAlert;
use Illuminate\Database\Eloquent\Model;

class VaccineScheduledAlertRepository extends AbstractRepository
{

    /**
     * @var Model
     */
    protected $model;

    public function __construct(VaccineScheduledAlert $model)
    {
        $this->model = $model;
    }

    public function formatParams(array $params): array
    {
        return [
            "record_id" => $this->getAttribute($params, 'record_id'),
            'alert_id' => Alert::where(['record_id' => $params['record_id']])->first()->id,
            "vaccine_id" => $this->getAttribute($params, 'vaccine_id'),
			"previous_application" => $this->getAttribute($params, 'previous_application') ?? '2023-06-20', //TODO
			"next_application" => $this->getAttribute($params, 'next_application'),
        ];
    }
}
