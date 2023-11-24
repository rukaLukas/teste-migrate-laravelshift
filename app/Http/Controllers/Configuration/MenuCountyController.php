<?php

namespace App\Http\Controllers\Configuration;

use App\Abstracts\AbstractController;
use App\Exceptions\GeneralException;
use App\Http\Resources\Configuration\MenuCountyResource;
use App\Services\Configuration\DeadlineService;
use App\Services\Configuration\MenuCountyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class MenuCountyController extends AbstractController
{
    protected $resource = MenuCountyResource::class;

    /**
     * @var MenuCountyService
     */
    protected $service;

    public function __construct(MenuCountyService $service)
    {
        $this->service = $service;
    }

    public function addToAllStates(Request $request)
    {
        try {
            DB::beginTransaction();
            $response = $this->service->addToAllStates($request->all());
            DB::commit();
            return $this->success($this->messageSuccessDefault, $response, Response::HTTP_CREATED);
        } catch (\Exception | ValidationException | GeneralException $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }
    public function addToAllCounties(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $response = $this->service->addToAllCounties($request->all(), $id);
            DB::commit();
            return $this->success($this->messageSuccessDefault, $response, Response::HTTP_CREATED);
        } catch (\Exception | ValidationException | GeneralException $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }
}
