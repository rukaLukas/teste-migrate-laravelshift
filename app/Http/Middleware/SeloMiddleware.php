<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SeloMiddleware
{
    private $hash = '$2y$13$B39XqXyn7oKBgYnkxuapzOm6/qvpiKqeo.l5kHT1XRLZqVOuCmwU';

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->hasHeader('Authorization-Selo')) {
            abort(401, 'Unauthorized');
        }
        $token = $request->header('Authorization-Selo');

        if ($token !== $this->hash) {
            abort(401, 'Token não é válido para consultas do SELO.');
        }

        return $next($request);
    }
}
