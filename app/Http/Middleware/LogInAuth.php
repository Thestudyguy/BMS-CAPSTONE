<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class LogInAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            if (!$user->UserPrivilege || !$user->isVisible) {
                Auth::logout();
                return redirect('/login')->withErrors(['error' => 'Your account is restricted by the admin for some reasons']);
            }
            if ($user->Role === 'Accountant' && !$request->is('journals')) {
                return redirect('/journals');
            }
        } else {
            return redirect('/login');
        }

        return $next($request);
    }
}
