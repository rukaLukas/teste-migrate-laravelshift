<?php

namespace App\Traits;

use App\Helper\AuthHelper;
use App\Models\Occupation;
use App\Scopes\TenantCountyScope;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;

trait HasTenantCounty
{
    /**
     * @return mixed
     */
    public static function bootHasTenantCounty(): void
    {
        if (Auth::check()) {
            $user = AuthHelper::userLoogged();
            if ($user->occupation_id !== Occupation::GESTOR_NACIONAL) {
                static::addGlobalScope(new TenantCountyScope());
            }
        }
    }
}
