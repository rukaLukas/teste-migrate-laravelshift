<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StateService;
use App\Services\CountyService;
use Illuminate\Http\JsonResponse;
use App\Services\AccessionService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Abstracts\AbstractController;
use App\Http\Resources\AccessionResource;
use App\Http\Resources\Selo\SeloResource;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\AccessionExternalRegisterRequest;

class AccessionController extends AbstractController
{
    protected $createRequest = AccessionExternalRegisterRequest::class;
    protected $resource = AccessionResource::class;

     /**
     * @var AccessionService
     */
    protected $service;

    public function __construct(AccessionService $service)
    {
        $this->service = $service;
    }

    /**
     * @param AccessionExternalRegisterRequest $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
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
            $this->service->register($request->all());
            DB::commit();
            return $this->success($this->messageSuccessDefault);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e);           
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @param $userId
     * @return JsonResponse
     */
    public function confirmExternal(Request $request, $id, $userId)
    {
        try {
            DB::beginTransaction();            
            $response = $this->service->confirmExternal($id, $userId, $request->all());            
            DB::commit();
            return $this->success(
                'Termo de adesão confirmado com sucesso.', $response, Response::HTTP_CREATED
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @param $userId
     * @return JsonResponse
     */
    public function confirmExternalPassword(Request $request, $id, $userId)
    {
        try {
            DB::beginTransaction();
            $response = $this->service->confirmExternalPassword($id, $userId, $request->all());
            Log::info('gestor confirma adesão', [
                "request" => $request->toArray(), 
                "id" => $id, 
                "user_id" => $userId]);
            DB::commit();
            return $this->success('Adesão concluída com sucesso.', $response, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function confirm(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $response = $this->service->confirm($id, $request->all());
            Log::info('confirmado adesão manual', [
                "request" => $request->toArray(), 
                "id" => $id]);
            DB::commit();
            return $this->success('Adesão concluída com sucesso', $response, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function reject(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $response = $this->service->reject($id, $request->all());
            DB::commit();
            return $this->success('Adesão excluída', $response, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function validateHasCounty(Request $request, $id)
    {
        $where = $this->service->getRepository()->findOneWhere(['county_id' => $id]);
        $nameCounty = '';
        $siglaState = '';
        if ($where) {
            $countyService = app()->make(CountyService::class);
            $stateService = app()->make(StateService::class);
            $county = $countyService->find($where['county_id']);
            $state = $stateService->find($county->state_id);

            $nameCounty = $county->name;
            $siglaState = $state->sigla;

        }
        return $this->ok(['exists' => !!$where, 'sigla' => $siglaState, 'name' => $nameCounty]);
    }

    /**
     * @param Request $request
     * @param $id
     * @param $userId
     * @return JsonResponse
     */
    public function validateAccessionUser(Request $request, $id, $userId)
    {
        try {
            $this->service->validateAccessionUser($id, $userId);
            return $this->ok(['ok' => true]);
        } catch (\Exception $e) {
            return $this->ok(['ok' => false]);
        }

    }

    /**
     * @param Request $request
     * @param $id
     * @param $userId
     * @return JsonResponse
     */
    public function validateAccessionUserPassword(Request $request, $id, $userId)
    {
        try {
            $this->service->validateAccessionUserPassword($id, $userId);
            return $this->ok(['ok' => true]);
        } catch (\Exception $e) {
            return $this->ok(['ok' => false]);
        }

    }

    public function selo(Request $request, string $siglaMunicipio): JsonResponse
    {
        try {
            return $this->ok(SeloResource::collection($this->service->selo($request->all(), $siglaMunicipio)));
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

}
