<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class XAuthorizationHeader
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->hasHeader('X-Authorization') && !$request->hasHeader('Authorization')) {
            $request->headers->set('Authorization', $request->header('X-Authorization'));
        }

        return $next($request);
    }
}
