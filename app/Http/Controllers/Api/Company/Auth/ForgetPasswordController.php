<?php

namespace App\Http\Controllers\Api\Company\Auth;

use App\Helpers\APIResponse;
use App\Http\Controllers\Controller;
use App\Services\CompanyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ForgetPasswordController extends Controller
{
    protected $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }


    /**
     * @OA\Post(
     *     path="/company/password/email",
     *      summary="Send Forget passord reset link",
     *     tags={"Company"},
     *     requestBody={
     *         "required": true,
     *         "content": {
     *             "application/json": {
     *                 "schema": {
     *                     "type": "object",
     *                     "properties": {
     *                         "email": {
     *                             "type": "string",
     *                             "example": "johnsmith@gmail.com",
     *                         },
     *                     },
     *                 },
     *             },
     *         },
     *     },
     *     responses={
     *         @OA\Response(
     *             response=200,
     *             description="OK",
     *             @OA\JsonContent(
     *                 @OA\Property(
     *                     property="success",
     *                     type="boolean",
     *                     example=true,
     *                     description="A boolean value."
     *                 ),
     *             ),
     *         ),
     *     },
     * )
     */

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
            return APIResponse::error('Validation errors', [], 422);
        }

        $response = $this->companyService->resetEmail($request->email, 'app');

        if ($response instanceof \Exception) {
            return APIResponse::error($response->getMessage());
        }

        return APIResponse::success('Send reset email successfully');

    }

    /**
     * @OA\Post(
     *     path="/company/verify/otp",
     *     summary="Verify Forget passord Otp",
     *     tags={"Company"},
     *     requestBody={
     *         "description": "Login Candidate",
     *         "required": true,
     *         "content": {
     *             "application/json": {
     *                 "schema": {
     *                     "type": "object",
     *                     "properties": {
     *                         "email": {
     *                             "type": "string",
     *                             "example": "johnsmith@gmail.com",
     *                         },
     *                          "otp": {
     *                             "type": "integer",
     *                             "example": 4321,
     *                         },
     *                     },
     *                 },
     *             },
     *         },
     *     },
     *     responses={
     *         @OA\Response(
     *             response=200,
     *             description="OK",
     *             @OA\JsonContent(
     *                 @OA\Property(
     *                     property="success",
     *                     type="boolean",
     *                     example=true,
     *                     description="A boolean value."
     *                 ),
     *             ),
     *         ),
     *     },
     * )
     */


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
            return APIResponse::error('Validation errors', [], 422);

        }

        $this->companyService->verifyOtp($request->email, $request->otp);

        return APIResponse::success('Verification Successfully');
    }

    /**
     * @OA\Post(
     *     path="/company/password/reset",
     *     summary="Reset Company Password",
     *     tags={"Company"},
     *     requestBody={
     *         "description": "Login Candidate",
     *         "required": true,
     *         "content": {
     *             "application/json": {
     *                 "schema": {
     *                     "type": "object",
     *                     "properties": {
     *                         "email": {
     *                             "type": "string",
     *                             "example": "johnsmith@gmail.com",
     *                         },
     *                          "password": {
     *                             "type": "string",
     *                             "example": 12345678,
     *                         },
     *      "confirm_password": {
     *                             "type": "string",
     *                             "example": 12345678,
     *                         },
     *                     },
     *                 },
     *             },
     *         },
     *     },
     *     responses={
     *         @OA\Response(
     *             response=200,
     *             description="OK",
     *             @OA\JsonContent(
     *                 @OA\Property(
     *                     property="success",
     *                     type="boolean",
     *                     example=true,
     *                     description="A boolean value."
     *                 ),
     *             ),
     *         ),
     *     },
     * )
     */


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
            return APIResponse::error('Validation errors', [], 422);

        }

        $user = $this->companyService->resetPassword($request->email, $request->password);

//        return $user;

        return APIResponse::success('Password Reset Successfully', $user);

    }
}
