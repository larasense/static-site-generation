<?php

namespace Larasense\StaticSiteGeneration\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Larasense\StaticSiteGeneration\Facades\StaticSite;

class SSGMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($response = StaticSite::get($request)){
            return $response;
        }
        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        /*
        if (!app()->environment('production')) {
            return;
        }
        */
        if (!StaticSite::checkEnvironment($request)){
            return;
        }

        if ($request->getMethod() !== 'GET' || $response->headers->get('X-SSG') === 'true') {
            return;
        }

        if (!is_null($request->getQueryString())) {
            return;
        }

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            return;
        }

        StaticSite::process($request, $response);

    }
}

