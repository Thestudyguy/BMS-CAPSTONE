<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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

            // Check if the user account is restricted
            if (!$user->UserPrivilege || !$user->isVisible) {
                Auth::logout();
                return redirect('/login')->withErrors(['error' => 'Your account is restricted by the admin for some reasons']);
            }

            // Redirect based on role
            if ($user->role === 'Accountant') {
                return redirect('clients');
            }
        }

        return $next($request);
    }
}
