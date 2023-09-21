<?php

namespace App\Http\Controllers\Company\Auth;

use App\Company;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class ResetPasswordController extends Controller
{
    /*
      |--------------------------------------------------------------------------
      | Password Reset Controller
      |--------------------------------------------------------------------------
      |
      | This controller is responsible for handling password reset requests
      | and uses a simple trait to include this behavior. You're free to
      | explore this trait and override any methods you wish to tweak.
      |
     */

//use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/company-home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('company.guest');
    }

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param \Illuminate\Http\Request $request
     * @param string|null $token
     * @return \Illuminate\Http\Response
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('company_auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    protected function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $company = Company::where('email', $request->email)->first();

        if (!$company)
            return redirect()->back()->with('error', 'Compant not found');


        $this->setUserPassword($company, $request->password);

        $company->setRememberToken(Str::random(60));

        $company->save();

        event(new PasswordReset($company));

        $this->guard()->login($company);
    }

    /**
     * Set the user's password.
     *
     * @param \Illuminate\Contracts\Auth\CanResetPassword $user
     * @param string $password
     * @return void
     */
    protected function setUserPassword($user, $password)
    {
        $user->password = Hash::make($password);
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker('companies');
    }

    /**
     * Get the guard to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('company');
    }

}
