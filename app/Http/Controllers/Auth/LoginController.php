<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |----------------------------------------------------------------------
    | Login Controller
    |----------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Override the default attemptLogin to customize login logic.
     *
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        $credentials = $this->credentials($request);
        $user = \App\Models\User::where($this->username(), $request->input($this->username()))->first();

        if ($user && $user->UserPrivilege) {
            return $this->guard()->attempt($credentials, $request->filled('remember'));
        }

        if ($user && $user->Role === 'Accountant') {
            return redirect()->route('journals');
        }

        return false;
    }

    /**
     * Handle the post-login redirection.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Auth\AuthManager $user
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function authenticated(Request $request, $user)
    {
        if ($user->Role === 'Accountant') {
            return redirect()->route('journals');
        }

        return redirect()->intended($this->redirectTo);
    }

    /**
     * Handle a failed login attempt.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    /**
     * Define the username field to be used during authentication.
     *
     * @return string
     */
    public function username()
    {
        return 'UserName'; // Make sure this is the correct field for username in your table
    }
}
