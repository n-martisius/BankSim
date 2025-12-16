<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * No-op throttle middleware.
 *
 * This middleware accepts the usual throttle parameters but simply forwards
 * the request. It's useful to disable rate limiting globally without
 * touching framework/vendor files or individual route declarations.
 */
class DisableThrottle
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed ...$params
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$params)
    {
        // Intentionally do nothing related to throttling.
        return $next($request);
    }
}
