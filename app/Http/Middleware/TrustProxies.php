<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class TrustProxies
{
    public function handle(Request $request, Closure $next): Response
    {
        if (App::environment('production')) {
            $request->setTrustedProxies(
                ['*'],
                Request::HEADER_X_FORWARDED_FOR |
                Request::HEADER_X_FORWARDED_HOST |
                Request::HEADER_X_FORWARDED_PORT |
                Request::HEADER_X_FORWARDED_PROTO |
                Request::HEADER_X_FORWARDED_AWS_ELB
            );
        }

        return $next($request);
    }
}
