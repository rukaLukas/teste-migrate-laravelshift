<?php

namespace App\Http\Controllers\Configuration;

use Illuminate\Http\JsonResponse;
use App\Abstracts\AbstractController;
use App\Exceptions\GeneralValidationException;
use Illuminate\Validation\ValidationException;
use App\Services\Configuration\GovernmentOfficeService;
use App\Http\Resources\Configuration\GovernmentOfficeResource;
use App\Http\Requests\Configuration\GovernmentOfficeCreateRequest;
use App\Http\Requests\Configuration\GovernmentOfficeUpdateRequest;

class GovernmentOfficeController extends AbstractController
{
    protected $createRequest = GovernmentOfficeCreateRequest::class;
    protected $updateRequest = GovernmentOfficeUpdateRequest::class;
    protected $resource = GovernmentOfficeResource::class;

    /**
     * @var GovernmentOfficeService
     */
    protected $service;

    public function __construct(GovernmentOfficeService $service)
    {
        $this->service = $service;
    }

    public function default(): JsonResponse
    {                
        try {                
            $entity = $this->service->getDefault();
            
            return $this->ok(['data' => GovernmentOfficeResource::collection($entity)]);            
        } catch (\Exception | ValidationException | GeneralValidationException $e) {
            return $this->handleException($e);
        }
    }

    public function getByCounty(string|int $id): JsonResponse
    {                
        try {                
            $entity = $this->service->getByCounty($id);
            
            return $this->ok(['data' => GovernmentOfficeResource::collection($entity)]);            
        } catch (\Exception | ValidationException | GeneralValidationException $e) {
            return $this->handleException($e);
        }
    }
}
