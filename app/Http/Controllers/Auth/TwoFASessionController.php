<?php
namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\TwoFALoginRequest;
use Laravel\Fortify\Events\RecoveryCodeReplaced;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse;
use Laravel\Fortify\Http\Responses\FailedTwoFactorLoginResponse;
use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticatedSessionController;

class TwoFASessionController extends TwoFactorAuthenticatedSessionController
{
    public function solveChallenge(TwoFALoginRequest $request)
    {
        $user = $request->challengedUser();
        
        if ($code = $request->validRecoveryCode()) {
            $user->replaceRecoveryCode($code);

            event(new RecoveryCodeReplaced($user, $code));
        } elseif (! $request->hasValidCode()) {
            return app(FailedTwoFactorLoginResponse::class);
        }

        $this->guard->login($user, $request->remember());

        $request->session()->regenerate();

        return app(TwoFactorLoginResponse::class);
    }
}
