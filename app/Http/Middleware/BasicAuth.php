<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class BasicAuth
{ 
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {                       
        header('Cache-Control: no-cache, must-revalidate, max-age=0');
        $hasCredentials = !(empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW']));
        if ($hasCredentials) {
            $AUTH_USER = $_SERVER['PHP_AUTH_USER'];
            $AUTH_PASS = $_SERVER['PHP_AUTH_PW'];
            $user = User::where('email', $AUTH_USER)->firstOrFail();
            
            if (!is_null($user) && $user->occupation_id == 1 && $user->password == (Hash::check($AUTH_PASS, $user->password))) {
                return $next($request);                
            }         
        }
        
        header('HTTP/1.1 401 Authorization Required');
        header('WWW-Authenticate: Basic realm="Access denied"');
        exit;
    }
}
