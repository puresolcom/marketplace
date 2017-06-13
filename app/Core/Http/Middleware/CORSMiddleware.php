<?php

namespace Awok\Core\Http\Middleware;

class CORSMiddleware
{
    public function handle($request, \Closure $next)
    {
        $headers = [
            'Access-Control-Allow-Methods' => 'GET, POST, DELETE, PUT, OPTIONS',
            'Access-Control-Allow-Headers' => 'Authorization, Content-type, Access-Control-Allow-Origin',
            'Access-Control-Allow-Origin'  => '*',
        ];

        $response = $next($request);

        $response->headers->add($headers);

        return $response;
    }
}