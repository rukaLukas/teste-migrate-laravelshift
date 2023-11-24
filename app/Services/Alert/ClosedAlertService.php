<?php
namespace App\Services\Alert;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Alert;
use Illuminate\Http\Request;
use App\Events\ClosedAlertEvent;
use App\Abstracts\AbstractService;
use App\Infra\Repository\Alert\ClosedAlertRepository;
use App\Models\AlertStep;
use App\Models\ClosedAlert;
use App\Models\Record;
use App\Models\StatusAlert;
use Illuminate\Support\Facades\Auth;

class ClosedAlertService extends AbstractService
{
    /**
     * @var ClosedAlertRepository
     */
    protected $repository;

    public function __construct(ClosedAlertRepository $repository)
    {
        $this->repository = $repository;
    }

     /**
     * @param Request $request
     * @return array
     */
    public function beforeSave(Request $request): array
    {        
        $requestClosedAlert = array_map(function($item) {
            return [
                'record_id' => Record::findByUUID($item['record_id'])->id,
                'user_id' => Auth::user()->id,//User::findByUUID($item['user_id'])->id,
                'status_alert_id' => StatusAlert::where('name', StatusAlert::STATUS[StatusAlert::ENCERRADO])->first()->id, //ENCERRADO,
                'comments' => $item['description'],
            ];
        }, array($request->all()));
        $request->merge(end($requestClosedAlert));
        
        return $request->toArray();
    }

    /**
     * save function
     *
     * @param Request $request
     * @param [type] $validationName
     * @return AlertStep
     */
    public function save(Request $request, $validationName = null): AlertStep
    {                
        $this->validate($request, 'save', $validationName);
        $params = $this->beforeSave($request);
        $model = AlertStep::Create([
            'record_id' => $params['record_id'],
            'user_id' => $params['user_id'],
            'status_alert_id' => $params['status_alert_id'],
            'reason_close_alert_id' => $params['reason_close_alert_id'],
            'comments' => $params['description'],
        ]);

        $this->afterSave($request, $model);     

        return $model;
    }

    /**
     * @param Request $request
     * @param mixed $model
     * @return void
     */
    public function afterSave(Request $request, mixed $model): void
    {                           
        event(new ClosedAlertEvent($model));
    }
}