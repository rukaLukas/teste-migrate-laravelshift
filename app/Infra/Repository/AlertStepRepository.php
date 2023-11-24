<?php

namespace App\Infra\Repository;

use Illuminate\Support\Facades\Auth;
use App\Abstracts\AbstractRepository;
use App\Models\AlertStep;
use Illuminate\Database\Eloquent\Model;

class AlertStepRepository extends AbstractRepository
{

    /**
     * @var Model
     */
    protected $model;

    public function __construct(AlertStep $model)
    {
        $this->model = $model;
    }

    public function formatParams(array $params): array
    {
        return [
            'record_id' =>$this->getAttribute($params, 'record_id'),
            'status_alert_id' => $this->getAttribute($params, 'status_alert_id'),
            'reason_close_alert_id' => $this->getAttribute($params, 'reason_close_alert_id'),
            'user_id' =>  Auth::id(),
            'comments' => $this->getAttribute($params, 'comments')
        ];
    }
}
