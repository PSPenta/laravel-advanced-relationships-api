<?php

namespace App\Pipes;

use Closure;

class Roles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!request()->has('roles') || !request('roles')) {
            return $next($request);
        }

        return $next($request)->with('roles');
    }
}