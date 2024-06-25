<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ActiveUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()->access != 0) {
            if (session('appUser') == false) {
                auth()->logout();
                $request->session()->invalidate();
            $request->session()->regenerateToken();
                return redirect()->route('login')->withError('Your Account Was Blocked');
            } else {
                auth()->logout();
                $request->session()->invalidate();
            $request->session()->regenerateToken();
                return view('mobileHome');
            }
        }
        return $next($request);
    }
}
