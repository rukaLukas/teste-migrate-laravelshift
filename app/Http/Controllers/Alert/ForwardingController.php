<?php
namespace App\Http\Controllers\Alert;

use App\Models\Vaccine;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Abstracts\AbstractController;
use App\Services\Alert\ForwardingService;
use App\Http\Requests\Alert\ForwardingRequest;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\Alert\ForwardingResource;

class ForwardingController extends AbstractController
{
    protected $createRequest = ForwardingRequest::class;
    protected $resource = ForwardingResource::class;

    /**
     * @var ForwardingService
     */
    protected $service;

    public function __construct(ForwardingService $service)
    {
        $this->service = $service;
    }

    // public function save(Request $request): JsonResponse
    // {
    //     dd("save controller");
    //     try {
    //         if ($this->createRequest) {
    //             $createRequest = app($this->createRequest);
    //             $request->validate($createRequest->rules());
    //         }
    //     } catch (ValidationException $e) {
    //         return $this->error($this->messageErrorDefault, $e->errors());
    //     }
    //     try {
    //         DB::beginTransaction();
    //             $response = $this->service->save($request);
    //         DB::commit();
           
    //         return $this->success($this->messageSuccessDefault, $response, Response::HTTP_CREATED);
    //     } catch (\Exception | ValidationException | GeneralException $e) {
    //         DB::rollBack();
    //         if ($e instanceof GeneralException) {
    //             return $this->error($this->messageErrorDefault, $e->getErrors());
    //         }
    //         if ($e instanceof \Exception) {
    //             return $this->error($this->messageErrorDefault, $e->getMessage());
    //         }
    //         if ($e instanceof ValidationException) {
    //             return $this->error($this->messageErrorDefault, $e->errors());
    //         }
    //     }
    // }
}
