<?php

namespace App\Http\Controllers\Company\Auth;

use App\Company;
use App\Http\Controllers\Controller;
use App\Traits\PHPCustomMail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;


class ForgotPasswordController extends Controller
{
    use PHPCustomMail;

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

//    use SendsPasswordResetEmails;

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
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */

    public function sendResetLinkEmail(Request $request)
    {

        $request->validate(['email' => 'required|email']);

        $company = Company::where('email', $request->email)->first();

        if (!$company) {
            flash(__('Sorry!! Company not found'))->error();
            return redirect()->back()->with('error', 'company not found');
        }
        $encryptedId = encrypt($company->id);
        $otp = (string)rand(1111, 9999);

        $company->otp = $otp;
        $company->otp_expire = Carbon::now()->addMinutes(2);
        $company->save();


        $to = $company->email;
        $from = 'no-reply@radstarstaffing.com';
        $subject = "Forgot Password";
        $message = view('vendor.company.otp-email', compact('otp', 'encryptedId'));
//        $message = 'Dear ' . $user->name . ', <a href="' . route('verification.email') . '">Click Here</a> to Verify .';

//        $this->customMail();
        send_mail($from, $to, $subject, $message);

        flash(__('Otp Sanded Successfully! Please check your email'))->success();
        return redirect()->back();

    }

    public function verifyOtp(Request $request)
    {
        $company = Company::where('email', $request->email)->first();
        $encryptedId = encrypt($company->id);
        if ($company) {
            if ($company->otp == $request->otp || $company->otp_expire <= Carbon::now()) {
                $company->otp = null;
                $company->otp_expire = null;
                $company->save();
                flash(__('Verification Successfully'))->success();
                return redirect()->route('company.password.reset.form', ['token' => $encryptedId]);
            } else {
                flash(__('Otp is expired please regenerate'))->error();
                return redirect()->route('password.request');
            }
        }

    }

    public function enterOtpForm(Request $request)
    {
        $companyId = decrypt($request['id']);

        $company = Company::where('id', $companyId)->first();

        return view('company_auth.passwords.verify-otp', compact('company'));
    }

    public function showLinkRequestForm()
    {
        return view('company_auth.passwords.email');
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

}
