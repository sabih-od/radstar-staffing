<?php

namespace App\Http\Controllers\Api\User\Auth;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    protected $userService;
    public function __construct(UserService $userService )
    {
        $this->userService = $userService;
    }

    public function sendResetLinkEmail(Request $request)
    {
        $rules = [
            'email' => 'required|email'
        ];

        $customMessages = [
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.'
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
            ], 422);
        }

        $this->userService->resetEmail($request->email,'app');

        return response()->json([
            "success" => true,
            "message" => 'Send reset email successfully',
        ]);
    }


    public function verifyOtp(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'otp' => 'required',
        ];

        $customMessages = [
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'otp.required' => 'The OTP field is required.',
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
            ], 422);
        }

        $this->userService->verifyOtp($request->email,$request->otp);

        return response()->json([
            "success" => true,
            "message" => 'Verification Successfully',
        ]);
    }

    public function resetPassword(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
        ];

        $customMessages = [
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least :min characters long.',
            'confirm_password.required' => 'The confirm password field is required.',
            'confirm_password.same' => 'The confirm password must match the password field.',
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = $this->userService->resetPassword($request->email,$request->password);

        return $user;

    }
}