<?php

namespace App\Http\Controllers\ApiControllers\users\auth;

use App\Http\Controllers\Controller;
use App\Repositories\Users\Auth\UserRepository;
use App\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Validation failed',
            'errors' => $validator->errors(),
        ], 422));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        // Attempt to authenticate the user
        $authResult = $this->userRepository->authenticateUser($credentials);
        return response()->json($authResult);
    }

    public function logout(Request $request)
    {
        $response = $this->userRepository->logoutUser($request->user());
        return response()->json($response);
    }


}
