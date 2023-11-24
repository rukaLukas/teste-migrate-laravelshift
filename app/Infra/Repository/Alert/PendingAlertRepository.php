<?php
namespace App\Infra\Repository\Alert;

use App\Models\PendingAlert;
use Illuminate\Support\Facades\DB;
use App\Abstracts\AbstractRepository;
use Illuminate\Database\Eloquent\Model;

class PendingAlertRepository extends AbstractRepository
{

    /**
     * @var Model
     */
    protected $model;

    public function __construct(PendingAlert $model)
    {
        $this->model = $model;
    }

    public function formatParams(array $params): array
    {
        return [
            'user_id' => $this->getAttribute($params, 'user_id'),
            'alert_id' => $this->getAttribute($params, 'alert_id')
        ];
    }
}
