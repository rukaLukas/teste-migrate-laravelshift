<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\CaseStepService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Abstracts\AbstractController;
use App\Http\Resources\CaseStepResource;
use App\Http\Requests\CaseStepCreateRequest;
use App\Http\Requests\CaseStepAssignToRequest;
use Illuminate\Validation\ValidationException;


class CaseStepController extends AbstractController
{
    protected $createRequest = CaseStepCreateRequest::class;
    protected $resource = CaseStepResource::class;

    /**
     * @var ProfileService
     */
    protected $service;

    public function __construct(CaseStepService $service)
    {
        $this->service = $service;
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
}
