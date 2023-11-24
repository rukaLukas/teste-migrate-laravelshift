<?php
namespace App\Infra\Repository\Alert;

use App\Abstracts\AbstractRepository;
use App\Models\ClosedAlert;
use Illuminate\Database\Eloquent\Model;

class ClosedAlertRepository extends AbstractRepository
{

    /**
     * @var Model
     */
    protected $model;

    public function __construct(ClosedAlert $model)
    {
        $this->model = $model;
    }

    public function formatParams(array $params): array
    {
        return [
            'user_id' => $this->getAttribute($params, 'user_id'),
            'alert_id' => $this->getAttribute($params, 'alert_id'),
            'reason_close_alert_id' => $this->getAttribute($params, 'reason_close_alert_id'),
            'description' => $this->getAttribute($params, 'description'),
        ];
    }
}
