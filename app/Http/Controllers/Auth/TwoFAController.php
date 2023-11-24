<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use App\Actions\Fortify\EnableTwoFactorAuthentication;
use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticationController;

class TwoFAController extends TwoFactorAuthenticationController
{
    /**
     * Enable two factor authentication for the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Actions\Fortify\EnableTwoFactorAuthentication  $enable
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function enable(Request $request, EnableTwoFactorAuthentication $enable)
    {
        $enable($request->user());

        return $request->wantsJson()
                    ? new JsonResponse('', 200)
                    : back()->with('status', 'two-factor-authentication-enabled');
    }
}
