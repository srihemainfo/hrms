<?php

namespace App\Http\Middleware;

use Closure;

class IsAdmin
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
        // dd(auth()->user());
        if (! auth()->user()->is_admin && !auth()->user()->name == 'SubAdmin') {
            abort(403);
        }
        return $next($request);
    }
}
