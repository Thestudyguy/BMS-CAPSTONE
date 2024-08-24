<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class LogInAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check() && (!Auth::user()->UserPrivilege || !Auth::user()->isVisible)) {
            Auth::logout();
            return redirect('/login')->withErrors(['error' => 'Your account is restricted by the admin for some reasons']);
        }

        return $next($request);
    }
}
