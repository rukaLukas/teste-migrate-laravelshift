<?php
namespace App\Service;

use Exception;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Abstracts\AbstractService;
use App\Exceptions\GeneralException;
use Illuminate\Support\Facades\Hash;
use App\Infra\Repository\UserRepository;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use App\Exceptions\NaoEncontradaException;
use Illuminate\Foundation\Http\FormRequest;
use App\Validations\User\UsersEnabledToSave;
use Illuminate\Validation\ValidationException;
use App\Validations\User\UsersEnabledToResetPassword;

class AuthService extends AbstractService
{
    /**
     * @var UserRepositoryInterface
     */
    protected $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }
    
    /**
     * @param string $email
     * @return bool
     * @throws NaoEncontradaException
     */
    public function recoverPassword(string $email): bool
    {
        $entity = $this->repository->getModel()->where('email', '=', $email)->get();
        
        if (count($entity) === 0) {
            $exception = new NaoEncontradaException();
            throw $exception->validationException();
        }

        $status = Password::sendResetLink(['email' => $email]);

        throw_if(
            $status !== Password::RESET_LINK_SENT,
            new Exception("Não foi possível enviar e-mail de recuperação de senha")
        );
        
        return true;
    }

    /**
     * @param string $request
     * @return void
     */
    public function resetPassword(FormRequest $request): void
    {
        $this->beforeResetPasswdSave($request);
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
        
                $user->save();
        
                event(new PasswordReset($user));
            }
        );

        throw_if(
            $status !== Password::PASSWORD_RESET,
            new Exception("Não foi possível criar uma nova senha")
        );
    }

    /**
     * @param Request $request
     * @throws \Throwable
     */
    public function beforeResetPasswdSave(Request $request): void
    {
        $user = new User($request->all());
        $userEnabledToSave = new UsersEnabledToResetPassword();
        throw_if(
            !$userEnabledToSave->validate($user)->isValid(),
            new GeneralException($userEnabledToSave->getErrors())
        );
    }
}
