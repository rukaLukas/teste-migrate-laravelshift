<?php

namespace App\Http\Controllers\Configuration;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\GovernmentOffice;
use App\Models\ReasonCloseAlert;
use Illuminate\Http\JsonResponse;
use App\Models\ReasonDelayVaccine;
use Illuminate\Support\Facades\DB;
use App\Abstracts\AbstractController;
use App\Exceptions\GeneralValidationException;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Configuration\GoRdvRequest;
use App\Models\ReasonDelayVaccineGovernmentOffice;
use App\Services\Configuration\TargetPublicService;
use App\Services\Configuration\ReasonsVaccineDelayService;
use App\Http\Requests\Configuration\ReasonsVaccineDelayRequest;
use App\Http\Resources\Configuration\ReasonsVaccineDelayResource;
use App\Http\Resources\Configuration\ReasonsVaccineDelayUserResource;
use App\Models\County;

class ReasonsVaccineDelayController extends AbstractController
{
    protected $createRequest = ReasonsVaccineDelayRequest::class;
    protected $createGoRdvRequest = GoRdvRequest::class;
    protected $resource = ReasonsVaccineDelayResource::class;

    /**
     * @var TargetPublicService
     */
    protected $service;

    public function __construct(ReasonsVaccineDelayService $service)
    {
        $this->service = $service;
    }

    public function targetPublic(string $targetPublicId): JsonResponse
    {        
        try {
            $reasonsDelayVaccine = $this->service->getByTargetPublic($targetPublicId);
            $resource = $this->resource::collection($reasonsDelayVaccine);

            return $this->ok($resource);
        } catch (\Exception | ValidationException | GeneralValidationException $e) {
            return $this->handleException($e);
        }
    }   

    public function getByCounty(string|int $id): JsonResponse
    {                
        try {                                  
            $entity = $this->service->getByCounty($id);
        
            return $this->ok(ReasonsVaccineDelayUserResource::collection($entity));            
        } catch (\Exception | ValidationException | GeneralValidationException $e) {
            return $this->handleException($e);
        }
    }

    public function bindGovernmentOfficeReasonDelayVaccineUser(Request $request, string $id): JsonResponse
    {    
        try {
            if ($this->createGoRdvRequest) {                                
                $goRdvRequest = app($this->createGoRdvRequest);                
                $request->validate($goRdvRequest->rules());
            }
        } catch (ValidationException $e) {   
            return $this->error($this->messageErrorDefault, $e->errors());
        }
        try {
            $this->service->bindGovernmentOfficeReasonDelayVaccine($id, $request->reason_delay_vaccine_id, $request->government_offices);
            
            return $this->success($this->messageSuccessDefault, null, Response::HTTP_CREATED);            
        } catch (\Exception | ValidationException | GeneralValidationException $e) {            
            return $this->handleException($e);
        }   
    }
}
