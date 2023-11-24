<?php
namespace App\Services;


use App\Models\Alert;
use App\Models\Record;
use App\Models\AlertStep;
use App\Models\StatusAlert;
use App\Models\VaccineRoom;
use App\Events\AlertCreated;
use Illuminate\Http\Request;
use App\Events\RecordCreated;
use Illuminate\Http\JsonResponse;
use App\Abstracts\AbstractService;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use Illuminate\Support\Facades\File;
use App\Models\TypeStatusVaccination;
use Illuminate\Database\Eloquent\Model;
use App\Infra\Repository\AlertRepository;
use App\Infra\Repository\RecordRepository;
use App\Validations\Alert\AlertEnabledToSave;
use App\Interfaces\Repository\AlertRepositoryInterface;
use App\Models\DelayedVaccine;
use App\Models\TargetPublic;
use App\Models\Vaccine;

class AlertService extends AbstractService
{
    /**
     * @var AlertRepositoryInterface
     */
    protected $repository;

    protected $recordRepository;

    public function __construct(AlertRepository $repository, RecordRepository $recordRepository)
    {
        $this->repository = $repository;
        $this->recordRepository = $recordRepository;
    }

    /**
     * list function
     *
     * @param Request $request
     * @return mixed
     */
    public function list(): mixed
    {
       return $this->repository->listConsolided();
    }

    /**
     * search function
     *
     * @param int $userId
     * @param string $search
     * @return mixed
     */
    public function search(string $search): mixed
    {
        return $this->repository->search($search);
    }

     /**
     * @param Request $request
     * @return array
     */
    public function beforeSave(Request $request): array
    {
        // get record or create
        $record = isset($request['record_id'])
        ? Record::findByUUID($request['record_id'])
        : Record::Create([
                    'cpf' => $request['cpf'],
                    'suscard' => $request['suscard'],
                ]);
        $request['record_id'] = $record->id;

        return $request->toArray();
    }

    /**
     * @param Request $request
     * @return Model
     */
    public function save(Request $request, $validationName = null): Model
    {        
        $this->validate($request, 'save', $validationName);
        $params = $this->beforeSave($request);
        $model = $this->repository->save($params);
        
        if ($request->vaccine_scheduled_alerts) {
            $vaccineScheduledAlerts = array_map(function($o) use ($model) {
                return [
                    'alert_id' => $model->id,
                    'vaccine_id' => \App\Models\Vaccine::findByUUID($o['vaccination_step'])->id,
                    'previous_application' => $o['previous_application'],
                    'next_application' => $o['next_application'],
                ];
            }, $request->vaccine_scheduled_alerts);

            $model->vaccineScheduledAlerts()->createMany($vaccineScheduledAlerts);
        }
        
        if ($request->vaccine_card_pictures) {
            $model->vaccineCardPictures()->createMany($request->vaccine_card_pictures);
        }
        
        $reasonsDelayVaccine = array_map(function($o) {
            return \App\Models\ReasonDelayVaccine::findByUUID($o)->id;
        }, $request->reasons_delay_vaccine);

        $model->reasonDelayVaccines()->attach($reasonsDelayVaccine);
        
        if ($request->is_new_visit || !isset($request->is_new_visit)) $this->afterSave($request, $model);        

        
        return $model;
    }

    /**
     * update function
     *
     * @param Request $request
     * @param [type] $validationName
     * @return Model
     */
    public function update(Request $request, string|int $id, $validationName = null): Model
    {
        $entity = $this->getRepository()->find($id);     
        $params = $this->beforeSave($request);
        $params = array_merge($params, ['uuid' => $id]);     
        $this->repository->update($entity, $params);

        if (!is_null($request->reason_not_has_vac_card_pic)) {
            $entity->vaccineCardPictures()->delete();
        }

        if ($request->vaccine_card_pictures) {
            $entity->vaccineCardPictures()->delete();
            $entity->vaccineCardPictures()->createMany($request->vaccine_card_pictures);
        }       

        $reasonsDelayVaccine = array_map(function($o) {
            return \App\Models\ReasonDelayVaccine::findByUUID($o)->id;
        }, $request->reasons_delay_vaccine);

        $entity->reasonDelayVaccines()->detach();
        $entity->reasonDelayVaccines()->attach($reasonsDelayVaccine);
        
        return $entity;
    }

    /**
     * @param Request $request
     * @param mixed $model
     * @return void
     */
    public function afterSave(Request $request, mixed $model): void
    {        
        $this->syncRecord($model->record, $request);        
        $this->setStatusAlert($request);         
        $this->setDelayedVaccines($request);

        if ($request->bae == Alert::FORA_DA_ESCOLA) {
            event(new RecordCreated($model));
        }
    }

     /**
     * @param Request $request
     * @return Model
     */
    public function saveStep(Request $request, $validationName = null): Model
    {
        $model = AlertStep::create([
            'record_id' => is_int($request->record_id) ? Record::find($request->record_id)->id : Record::findByUUID($request->record_id)->id,
            'status_alert_id' => StatusAlert::findByUUID($request->step)->id,
            'user_id' => auth()->user()->id,
        ]);    
                
        return $model;
    }

    /**
     * @param int|string $id
     */
    public function beforeDelete(int|string $id): void
    {
        $model = $this->find($id);
        $vaccineCardPictures = $model->vaccineCardPictures()->get()->toArray();
        $collection = collect($vaccineCardPictures)->map(function ($item) {
            return str_replace(env('APP_URL') . '/', '', $item['image']);
        });
        $files = $collection->toArray();
        File::delete($files);
    }

    /**
     * syncRecord function
     *
     * update data into record table, such as cpf, and suscard
     *
     * @param Record $model
     * @param Request $request
     * @return void
     */
    private function syncRecord(Record $record, Request $request)
    {
        $fields = ["cpf", "suscard"];
        foreach ($fields as $field) {
            if (empty($record->$field) && $record->$field != $request->$field) {
                $record->$field = $request->$field;
                $record->save();
            }
        }
    }

    /**
     * @param Request $request
     * @return Request
     */
    private function setStatusAlert(Request $request): void
    {              
        $typeStatusVaccination = TypeStatusVaccination::findByUUID($request['type_status_vaccination_id']);    
        if ($typeStatusVaccination->name == TypeStatusVaccination::TYPE_STATUS[TypeStatusVaccination::VACINACAO_EM_DIA]) {            
            $this->saveStatusAlertStep($request, StatusAlert::VISITA);            
        }
        if ($typeStatusVaccination->name !== TypeStatusVaccination::TYPE_STATUS[TypeStatusVaccination::VACINACAO_EM_DIA]) {             
            $this->saveStatusAlertStep($request, StatusAlert::ALERTA);            
        }
        if ($typeStatusVaccination->name == TypeStatusVaccination::TYPE_STATUS[TypeStatusVaccination::NAO_VACINADA] ||
            $typeStatusVaccination->name == TypeStatusVaccination::TYPE_STATUS[TypeStatusVaccination::SEM_CARTEIRINHA]) {                
                $this->saveStatusAlertStep($request, StatusAlert::ENCAMINHAMENTO);                
        }                                
    }

    private function setDelayedVaccines(Request $request): void
    {        
         // if status vaccine is not vaccined or not has vaccine card, so save all vaccines related to target_public as not applied        
         $typeStatusVaccination = TypeStatusVaccination::findByUUID($request->type_status_vaccination_id);      
         
         if ($typeStatusVaccination->name == TypeStatusVaccination::TYPE_STATUS[TypeStatusVaccination::NAO_VACINADA] 
            || $typeStatusVaccination->name == TypeStatusVaccination::TYPE_STATUS[TypeStatusVaccination::SEM_CARTEIRINHA]) {
                
                $vaccinesTargetPublic = Vaccine::Where('target_public_id', TargetPublic::findByUUID($request->target_public_id)->id)->get();                
                $vaccinesTargetPublic->each(function($vaccine) use ($request) {
                    DelayedVaccine::create([
                        'alert_step_id' => AlertStep::Where('record_id', $request->record_id)
                            ->where('status_alert_id', 
                                StatusAlert::where('name', strtolower(StatusAlert::STATUS[StatusAlert::ALERTA]))->first()->id)
                            ->first()->id,
                        'vaccine_id' => $vaccine->id
                    ]);                
                });
         }     
    }

    private function saveStatusAlertStep(Request $request, int $statusAlert)
    {        
        $statusAlert = StatusAlert::STATUS[$statusAlert];
        $statusAlert = StatusAlert::where('name', $statusAlert)->first();
        $request['step'] = $statusAlert->uuid;

        return $this->saveStep($request);
    }
}
