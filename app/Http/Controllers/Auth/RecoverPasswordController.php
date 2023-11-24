<?php
namespace App\Http\Controllers\Auth;

use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Abstracts\AbstractController;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\RecoverPasswordRequest;
use App\Exceptions\GeneralValidationException;
use Illuminate\Validation\ValidationException;

class RecoverPasswordController extends AbstractController
{
    /**
     * @var AuthService
     */
    protected $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    public function recoverPassword(RecoverPasswordRequest $request): JsonResponse
    {                               
        try {
            $this->service->recoverPassword($request->input('email'));            
            return $this->ok(['type'=> 'success', 'message' => 'Email enviado com sucesso']);       
        } catch (\Exception | ValidationException | GeneralValidationException $e) {            
            return $this->handleException($e);
        }
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        try {
            $this->service->resetPassword($request);
            return $this->ok(['type'=> 'success', 'message' => 'Nova senha criada com sucesso.']);
        } catch (\Exception | ValidationException $e) {
            if ($e instanceof \Exception) {
                return $this->error($this->messageErrorDefault, (array)$e->getMessage());
            }
        }
    }
}
