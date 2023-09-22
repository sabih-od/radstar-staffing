<?php

namespace App\Http\Controllers\Auth;

use App\Company;
use App\Http\Controllers\Controller;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    /*
      |--------------------------------------------------------------------------
      | Password Reset Controller
      |--------------------------------------------------------------------------
      |
      | This controller is responsible for handling password reset emails and
      | includes a trait which assists in sending these notifications from
      | your application to your users. Feel free to explore this trait.
      |
     */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->middleware('guest');
        $this->userService = $userService;
    }

    //user module
    public function showLinkRequestForm()
    {
        return view('user_auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $response = $this->userService->resetEmail($request->email, 'web');

        if ($response instanceof \Exception) {
            flash(__($response->getMessage()))->error();
            return redirect()->back();
        }

        flash(__('Send reset email successfully'))->success();
        return redirect()->back();
    }

    public function enterOtpForm(Request $request)
    {
        $userId = decrypt($request['id']);
        $user = $this->userService->optExist($userId);

        if ($user) {
            return view('user_auth.passwords.verify-otp', compact('user'));
        } else {
            return back()->with('error', 'something went wrong');
        }
    }

    public function verifyOtp(Request $request)
    {
        $users = $this->userService->verifyOtp($request->email, $request->otp);
        if ($users instanceof \Exception) {
            flash(__($users->getMessage()))->error();
            return redirect()->back();
        }
        $encryptedId = $users->getDate();


        flash(__('Verification Successfully'))->success();
        return redirect()->route('user.password.reset.form', ['token' => $encryptedId]);
    }

}
