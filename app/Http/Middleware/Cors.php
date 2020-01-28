<?php

namespace App\Http\Middleware;

use Closure;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $headers = [
            'Access-Control-Allow-Origin'      => env('CLIENT_URL') || '*',
            'Access-Control-Allow-Methods'     => 'GET, POST, PUT, DELETE, OPTIONS',
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Max-Age'           => '86400',
            'Access-Control-Allow-Headers'     => 'Origin, Authorization, X-Requested-With, Content-Type, Accept',
            'Accept'                           => 'application/json'
        ];

        if ($request->isMethod('OPTIONS'))
            return response()->json(["method" => "OPTIONS"], 200, $headers);

        $response = $next($request);
        foreach($headers as $key => $value)
            $response->header($key, $value);

        return $response;
    }
}
