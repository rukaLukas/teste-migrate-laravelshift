<?php

namespace App\Services;


use App\Abstracts\AbstractService;
use App\Events\RecordCreated;
use App\Infra\Repository\AlertStepRepository;
use App\Models\Alert;
use App\Models\AlertStep;
use App\Models\ReasonNotAppliedVaccine;
use App\Models\Record;
use App\Models\StatusAlert;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class AlertStepService extends AbstractService
{
    /**
     * @var AlertStepRepository
     */
    protected $repository;

    /**
     * @var RecordService
     */
    private $recordService;

    public function __construct(AlertStepRepository $repository, RecordService $recordService)
    {
        $this->repository = $repository;
        $this->recordService = $recordService;
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
            $vaccineScheduledAlerts = array_map(function ($o) use ($model) {
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

        $reasonsDelayVaccine = array_map(function ($o) {
            return \App\Models\ReasonDelayVaccine::findByUUID($o)->id;
        }, $request->reasons_delay_vaccine ?? []);

        if ($reasonsDelayVaccine) {
            $model->reasonDelayVaccines()->attach($reasonsDelayVaccine);
        }

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
        $this->syncRecord($model->record, $request);

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
        $alert = Alert::findByUUID($request->alert_id);
        // TODO: Validar se todas vacinas foram aplicadas
        $reasonNotAppliedVaccine = isset($request->reason_not_applied_vaccine_id) ?
            ReasonNotAppliedVaccine::findByUUID($request->reason_not_applied_vaccine_id)->id :
            null;

        $model = AlertStep::create([
            'record_id' => $alert->record_id,
            'status_alert_id' => StatusAlert::findByUUID($request->step)->id,
            'user_id' => auth()->user()->id,
            'reason_not_applied_vaccine_id' => $reasonNotAppliedVaccine,
            //'comments' => $request->comments ? $request->comments : NULL
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

    public function startTechnicalAnalysis(Request $request, string $id)
    {
        $params = [
            'record_id' => $this->recordService->find($request->input('record_id'))->id,
            'user_id' => Auth::id(),
            'status_alert_id' => StatusAlert::ANALISE_TECNICA,
        ];

        $request->merge($params);
        return $this->save($request);
    }
}
