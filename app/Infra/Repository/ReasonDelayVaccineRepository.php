<?php

namespace App\Infra\Repository;

use App\Abstracts\AbstractRepository;
use App\Models\ReasonDelayVaccine;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ReasonDelayVaccineRepository extends AbstractRepository
{

    /**
     * @var Model
     */
    protected $model;

    public function __construct(ReasonDelayVaccine $model)
    {
        $this->model = $model;
    }

    public function formatParams(array $params): array
    {
        return [
            'type_alert' => $this->getAttribute($params, 'type_alert'),
            'description' => $this->getAttribute($params, 'description'),
            'is_send_social_assistence' => $this->getAttribute($params, 'is_send_social_assistence')
        ];
    }
}
