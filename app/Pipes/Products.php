<?php

namespace App\Pipes;

use Closure;

class Products
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
        if (!request()->has('products') || !request('products')) {
            return $next($request);
        }

        return $next($request)->with('products');
    }
}