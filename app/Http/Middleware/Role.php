<?php

namespace App\Http\Middleware;
use Closure;

class Role
{
    public function handle($request, Closure $next)
    {
    	if (auth()->user()->role != "admin") {
            return response('Forbidden.', 403);
        }

        return $next($request);
    }
}
