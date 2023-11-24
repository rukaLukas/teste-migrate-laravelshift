<?php

namespace App\Http\Controllers;

use App\Abstracts\AbstractController;
use App\Exceptions\GeneralException;
use App\Exceptions\GeneralValidationException;
use App\Http\Requests\Alert\step\VaccineRoomRequest;
use App\Http\Requests\CaseStepAssignToRequest;
use App\Http\Resources\AlertStepResource;
use App\Mail\Record\VencimentoPrazoEncaminhamento;
use App\Models\AlertStep;
use App\Models\Record;
use App\Models\StatusAlert;
use App\Models\User;
use App\Models\Vaccine;
use App\Services\Alert\Step\VaccineRoomService;
use App\Services\Alert\TechnicalAnalysisService;
use App\Services\AlertService;
use App\Services\AlertStepService;
use App\Services\DelayedVaccineService;
use App\Services\VaccineScheduledAlertService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class AlertStepController extends AbstractController
{
    protected $vaccineRoomRequest = VaccineRoomRequest::class;
    protected $resource = AlertStepResource::class;

    /**
     * @var AlertService
     */
    protected $service;

    /**
     * @var VaccineRoomService
     */
    protected $vaccineRoomService;

    /**
     * @var TechnicalAnalysisService
     */
    private $technicalAnalysisService;

    /**
     * @var DelayedVaccineService
     */
    private $delayedVaccineService;

    /**
     * @var VaccineScheduledAlertService
     */
    private $vaccineScheduledAlertService;

    public function __construct(
        AlertStepService             $service,
        VaccineRoomService           $vaccineRoomService,
        TechnicalAnalysisService     $technicalAnalysisService,
        DelayedVaccineService        $delayedVaccineService,
        VaccineScheduledAlertService $vaccineScheduledAlertService
    )
    {
        $this->service = $service;
        $this->vaccineRoomService = $vaccineRoomService;
        $this->technicalAnalysisService = $technicalAnalysisService;
        $this->delayedVaccineService = $delayedVaccineService;
        $this->vaccineScheduledAlertService = $vaccineScheduledAlertService;
    }

    public function vaccineRoom(Request $request): JsonResponse
    {
        try {
            if ($this->vaccineRoomRequest) {
                $vaccineRoomRequest = app($this->vaccineRoomRequest);
                $request->validate($vaccineRoomRequest->rules());
            }

            DB::beginTransaction();
            $response = $this->vaccineRoomService->save($request);
            DB::commit();

            return $this->success($this->messageSuccessDefault, new $this->resource($response), Response::HTTP_CREATED);
        } catch (\Exception | ValidationException | GeneralValidationException $e) {
            return $this->handleException($e);
        }
    }

    public function assignTo(Request $request): JsonResponse
    {
        try {
            $assignRequest = app(CaseStepAssignToRequest::class);
            $request->validate($assignRequest->rules());
        } catch (ValidationException $e) {
            return $this->error($this->messageErrorDefault, $e->errors());
        }
        try {
            DB::beginTransaction();
            $response = $this->service->assignTo($request);
            DB::commit();
            return $this->success($this->messageSuccessDefault, $response, Response::HTTP_OK);
        } catch (\Exception | ValidationException | GeneralException $e) {
            DB::rollBack();
            if ($e instanceof GeneralException) {
                return $this->error($this->messageErrorDefault, $e->getErrors());
            }
            if ($e instanceof \Exception) {
                return $this->error($this->messageErrorDefault, $e->getMessage());
            }
            if ($e instanceof ValidationException) {
                return $this->error($this->messageErrorDefault, $e->errors());
            }
        }
    }

    public function technicalAnalysis(Request $request, string $id)
    {
        try {
            $recordId = Arr::get($request->all(), 'record_id');
            $alertStepId = Arr::get($request->all(), 'alert_step_id');
            $userId = Arr::get($request->all(), 'user_id');
            $delayVaccines = Arr::get($request->all(), 'delay_vaccines');
            $deployVaccines = Arr::get($request->all(), 'deploy_vaccines');
            $type = Arr::get($request->all(), 'type');
            $record = Record::findByUUID($recordId);

            $alertStepNew = [
                'record_id' => $record->id,
                'user_id' => User::findByUUID($userId)->id,
            ];

            if ($type === 'concluido') {
                foreach ($deployVaccines as $vaccine) {
                    if ($vaccine['date_deploy']) {
                        $params = [
                            'vaccine_id' => Vaccine::findByUUID($vaccine['vaccine_id'])->id,
                            'user_id' => User::findByUUID($userId)->id,
                            'record_id' => Record::findByUUID($recordId)->id,
                            'previous_application' => $vaccine['date_deploy'],
                            'next_application' => $vaccine['date_return']
                        ];
                        $this->vaccineScheduledAlertService->getRepository()->save($params);
                    }
                }
                $alertStepNew['status_alert_id'] = StatusAlert::CONCLUIDO;
                $this->service->getRepository()->save($alertStepNew);
            }

            if ($type === 'encaminhamento') {
                foreach ($delayVaccines as $vaccine) {
                    $params = [
                        'alert_step_id' => AlertStep::findByUUID($id)->id,
                        'vaccine_id' => $vaccine['vaccine_id'],
                        'user_id' => User::findByUUID($userId)->id,
                        'record_id' => Record::findByUUID($recordId)->id,
                    ];
                    $this->delayedVaccineService->getRepository()->save($params);
                }
                $alertStepNew['status_alert_id'] = StatusAlert::ENCAMINHAMENTO;
                $this->service->getRepository()->save($alertStepNew);
                $this->sendEmailToResponsible($record);
            }
            return $this->success($this->messageSuccessDefault);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function sendEmailToResponsible(Record $record)
    {
        if ($record->lastAlert->mother_email) {
            Mail::to($record->lastAlert->mother_email)->send(
                new VencimentoPrazoEncaminhamento($record)
            );
        }
        if ($record->lastAlert->father_email) {
            Mail::to($record->lastAlert->father_email)->send(
                new VencimentoPrazoEncaminhamento($record)
            );
        }
    }

    public function startTechnicalAnalysis(Request $request, string $id)
    {
        try {
            DB::beginTransaction();
            $response = $this->service->startTechnicalAnalysis($request, $id);
            DB::commit();
            return $this->success($this->messageSuccessDefault, $response, Response::HTTP_OK);
        } catch (\Exception | ValidationException | GeneralException $e) {
            DB::rollBack();
            if ($e instanceof GeneralException) {
                return $this->error($this->messageErrorDefault, $e->getErrors());
            }
            if ($e instanceof \Exception) {
                return $this->error($this->messageErrorDefault, $e->getMessage());
            }
            if ($e instanceof ValidationException) {
                return $this->error($this->messageErrorDefault, $e->errors());
            }
        }
    }
}
