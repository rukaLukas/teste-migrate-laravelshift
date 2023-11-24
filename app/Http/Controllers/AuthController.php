<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use App\Abstracts\AbstractController;
use Illuminate\Http\Request;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\RecoverPasswordRequest;
use Illuminate\Validation\ValidationException;

class AuthController extends AbstractController
{
    // protected $createRequest = ProfileCreateRequest::class;
    // protected $resource = ProfileResource::class;

    /**
     * @var AuthService
     */
    protected $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    /**
     * Se usuario esta autenticado na sessao
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function me(Request $request)
    {
        $userService = app()->make(UserService::class);
        return ($request->user()) ?
            $this->ok(new UserResource($userService->find($request->user()->id))) :
            $this->error();
    }

    public function recoverPassword(RecoverPasswordRequest $request): JsonResponse
    {
        try {
            $this->service->recoverPassword($request->input('email'));            
            return $this->ok(['message' => 'Email enviado com sucesso']);
        } catch (\Exception | ValidationException $e) {
            if ($e instanceof ValidationException) {
                return $this->error($this->messageErrorDefault, $e->errors());
            }
            if ($e instanceof \Exception) {
                return $this->error($e->getMessage());
            }
        }
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        try {
            $this->service->resetPassword($request);
            return $this->ok([]);
        } catch (\Exception | ValidationException $e) {
            if ($e instanceof \Exception) {
                return $this->error($this->messageErrorDefault, (array)$e->getMessage());
            }
        }
    }
}
