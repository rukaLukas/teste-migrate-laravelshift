<?php
namespace App\Services;

use App\Models\Accession;
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
     * @throws Exception
     */
    public function recoverPassword(string $email): bool
    {
        $entity = $this->repository->getModel()->where('email', '=', $email)->get();

        throw_if(
            count($entity) === 0,
            new NaoEncontradaException()
        );

        $this->checkIfUserCanChangePassword($entity->first());

        $status = Password::sendResetLink(['email' => $email]);

        throw_if(
            $status !== Password::RESET_LINK_SENT,
            new Exception("Não foi possível enviar e-mail de recuperação de senha.")
        );

        return true;
    }

    /**
     * Função que verifica se o usuário pode solicitar a recuperação de senha
     * @param User $user
     * @return bool
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function checkIfUserCanChangePassword(User $user): bool
    {
        $accessionService = app()->make(AccessionService::class);

        $prefeito = $accessionService->getRepository()->where(['prefeito_id' => $user->id])->first();

        if ($prefeito) {
            throw new \Exception('Prefeito não pode cadastrar senha no sistema.');
        }

        $gestor = $accessionService->getRepository()->where(['gestor_politico_id' => $user->id])->first();

        if ($gestor && $gestor->status_gestor_politico === Accession::STATUS['PENDENTE']) {
            throw new \Exception('Gestor com pendência na adesão.');
        }

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
