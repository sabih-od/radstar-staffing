<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfCompanyNotAuthenticated
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'company')
    {
        if (!Auth::guard($guard)->check()) {
            return redirect('/login');
        }

        /*//inactive check
        if (!Auth::guard($guard)->user()->is_active) {
            return redirect('/login')->withErrors(['email' => 'Your account is inactive. Contact administration to activate your account.']);
        }*/

        return $next($request);
    }

}
