<?php

namespace App\Helper;

use App\Services\UserService;
use Illuminate\Support\Facades\Auth;

class AuthHelper
{
    /**
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
	public static function userLoogged()
    {
        $userService = app()->make(UserService::class);
        if (Auth::user()) {
            return $userService->find(Auth::user()->getAuthIdentifier());
        }
        return false;
	}
}
