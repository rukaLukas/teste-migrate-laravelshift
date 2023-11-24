<?php

namespace App\Providers;

use App\Models\User;
use App\Models\AuditLogin;
use App\Services\UserService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use App\Infra\Repository\UserRepository;
use Opcodes\LogViewer\Facades\LogViewer;
use Illuminate\Pagination\LengthAwarePaginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Interfaces\Repository\UserRepositoryInterface', UserRepository::class);
        $this->app->bind('App\Interfaces\Repository\UserRepositoryInterface', function() {
            return new UserRepository(new User());
        });

        $this->app->bind('App\Interfaces\Service\UserServiceInterface', UserService::class);
        $this->app->bind('Laravel\Fortify\Http\Requests\LoginRequest', \App\Http\Requests\Auth\LoginRequest::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if(env('APP_ENV') != "local")
        {
            URL::forceScheme('https');
        }

        // Paginated Collections in Laravel
        Collection::macro('paginate', function($perPage = 10, $total = null, $page = null, $pageName = 'page') {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);

            $perPage = $perPage == 10 ? env('PAGINATION_LIMIT', $perPage) : $perPage;
            return new LengthAwarePaginator(
                $this->forPage($page, $perPage),
                $total ?: $this->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });

        Event::listen('Illuminate\Auth\Events\Login', function ($event) { 
            $this->auditLogin($event->user, AuditLogin::TYPE_LOGIN);           
        });

        Event::listen('Illuminate\Auth\Events\Logout', function ($event) {
            if (!is_null($event->user)){
                $this->auditLogin($event->user, AuditLogin::TYPE_LOGOUT);
            }                                   
        });               
    }

    protected function auditLogin(User $user, int $type)
    {        
        $auditLogin = new AuditLogin();
        $auditLogin->user_id = $user->id;
        $auditLogin->type = $type;
        $auditLogin->save();
    }
}
