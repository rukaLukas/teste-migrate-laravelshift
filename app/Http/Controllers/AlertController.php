<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Alert;
use App\Events\AlertCreated;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\AlertService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exceptions\GeneralException;
use Illuminate\Support\Facades\File;
use App\Abstracts\AbstractController;
use App\Http\Resources\AlertResource;
use App\Http\Requests\AlertStepRequest;
use App\Http\Requests\AlertCreateRequest;
use App\Http\Requests\AlertUpdateRequest;
use App\Exceptions\GeneralValidationException;
use App\Services\VaccineScheduledAlertService;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\Alert\AgentAlertListResource;
use App\Services\Case\AlertService as CaseAlertService;
use App\Http\Requests\Alert\UpdateReasonsDelayVaccineRequest;

class AlertController extends AbstractController
{
    protected $createRequest = AlertCreateRequest::class;
    protected $updateRequest = AlertUpdateRequest::class;
    protected $alertStepRequest = AlertStepRequest::class;
    protected $listResource = AgentAlertListResource::class;    
    protected $updateReasonsDelayVaccineRequest = UpdateReasonsDelayVaccineRequest::class;
    protected $resource = AlertResource::class;
    protected $validationName = 'Alert';

    /**
     * @var AlertService
     */
    protected $service;

    /**
     * @var AlertService
     */
    protected $caseAlertService;

    /**
     * @var AlertService
     */
    protected $vaccineScheduledService;

    public function __construct(AlertService $service
                                ,VaccineScheduledAlertService $vaccineScheduledService
                                ,CaseAlertService $caseAlertService)
    {
        $this->service = $service;
        $this->caseAlertService = $caseAlertService;
        $this->vaccineScheduledService = $vaccineScheduledService;
    }

    public function list(Request $request, ...$params): JsonResponse
    {
        try {
            $alerts = $request->searchAllFields != "" ?
                $this->service->search($request->searchAllFields) :
                $this->service->list();
            $resource = $this->listResource::collection($alerts);

            return $this->ok($resource);
        } catch (\Exception $e) {
            if ($e instanceof \Exception) {
                return $this->error($this->messageErrorDefault, $e->getMessage());
            }
        }
    }

    public function search(Request $request, ...$params): JsonResponse
    {
        try {
            $alerts = $request->searchAllFields != "" ?
                $this->service->search($request->searchAllFields) :
                $this->service->list();
            $resource = $this->listResource::collection($alerts);

            return $this->ok($resource);
        } catch (\Exception $e) {
            if ($e instanceof \Exception) {
                return $this->error($this->messageErrorDefault, $e->getMessage());
            }
        }
    }

    public function save(Request $request): JsonResponse
    {
        try {
            if ($this->createRequest) {
                $createRequest = app($this->createRequest);
                $request->validate($createRequest->rules());
            }
        } catch (ValidationException $e) {
            return $this->error($this->messageErrorDefault, $e->errors());
        }
        try {
            DB::beginTransaction();                                                
                $response = $this->service->save($request);                
            DB::commit();
            Log::info('Registro criado', ["request" => $request->toArray()]);
            event(new AlertCreated($response));
            
            return $this->success($this->messageSuccessDefault, new $this->resource($response), Response::HTTP_CREATED);
        } catch (\Exception | ValidationException | GeneralValidationException $e) {
            return $this->handleException($e);
        }
    }

    public function updateAlert(Request $request, string $id): JsonResponse
    {
        try {
            if ($this->updateRequest) {
                $updateRequest = app($this->updateRequest);
                $request->validate($updateRequest->rules());
            }
        } catch (ValidationException $e) {
            return $this->error($this->messageErrorDefault, $e->errors());
        }
        try {
            DB::beginTransaction();
                $response = $this->service->update($request, $id);               
            DB::commit();
            return $this->success($this->messageSuccessDefault, $response, Response::HTTP_OK);
        } catch (\Exception | ValidationException | GeneralException $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }

    public function updateReasonsDelayVaccine(Request $request, int $id): JsonResponse
    {
        try {
            if ($this->updateReasonsDelayVaccineRequest) {
                $updateReasonsDelayVaccineRequest = app($this->updateReasonsDelayVaccineRequest);
                $request->validate($updateReasonsDelayVaccineRequest->rules());
            }
        } catch (ValidationException $e) {
            return $this->error($this->messageErrorDefault, $e->errors());
        }
        try {
            DB::beginTransaction();
                $alert = $this->caseAlertService->updateReasonsDelayVaccine($request, $id);
            DB::commit();
            return $this->success($this->messageSuccessDefault, $alert, Response::HTTP_OK);
        } catch (\Exception | ValidationException | GeneralException $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }

    public function step(Request $request, string $id): JsonResponse
    {
        try {
            if ($this->alertStepRequest) {
                $alertStepRequest = app($this->alertStepRequest);
                $request->validate($alertStepRequest->rules());
            }
        } catch (ValidationException $e) {
            return $this->error($this->messageErrorDefault, $e->errors());
        }
        try {
            DB::beginTransaction();           
                $request->merge(['record_id' => $id]);                              
                $this->service->saveStep($request);                
            DB::commit();
                        
            return $this->success($this->messageSuccessDefault, null, Response::HTTP_CREATED);
        } catch (\Exception | ValidationException | GeneralValidationException $e) {
            return $this->handleException($e);
        }
    }

    // public function delete(int $id)
    // {
    //     $model = $this->find($id);
    //     $vaccineCardPictures = $model->vaccineCardPictures()->get()->toArray();
    //     $collection = collect($vaccineCardPictures)->map(function ($item) {
    //         return str_replace(env('APP_URL') . '/', '', $item['image']);
    //     });
    //     $files = $collection->toArray();
    //     File::delete($files);
    // }
}
