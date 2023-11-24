<?php
namespace App\Infra\Repository\Alert;

use App\Models\AlertStep;
use App\Models\DelayedVaccine;
use App\Models\PendingAlert;
use App\Models\VaccineScheduledAlert;
use Illuminate\Support\Facades\DB;
use App\Abstracts\AbstractRepository;
use Illuminate\Database\Eloquent\Model;

class TechnicalAnalysisRepository extends AbstractRepository
{

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var DelayedVaccine
     */
    private $delayedVaccine;

    /**
     * @var VaccineScheduledAlert
     */
    private $vaccineScheduledAlert;

    public function __construct(AlertStep $model)
    {
        $this->model = $model;
        $this->delayedVaccine = $delayedVaccine;
        $this->vaccineScheduledAlert = $vaccineScheduledAlert;
    }

    public function formatParams(array $params): array
    {
        return [
            'user_id' => $this->getAttribute($params, 'user_id'),
            'alert_id' => $this->getAttribute($params, 'alert_id')
        ];
    }
}
