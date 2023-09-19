<?php

namespace App\Http\Controllers\Api\User\Auth;
use App\Http\Controllers\Controller;
use App\Repositories\Users\Auth\UserRepository;
use App\Services\UserService;
use App\User as Users;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    protected $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
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
        try {
            // Attempt to authenticate the user
            $authResult = $this->userService->authenticateUser($credentials);
            return response()->json($authResult);
        }catch (\Exception $e){
            return response()->json([
                "success" => false,
                "message" => $e,
            ]);
        }
    }

    public function logout(Request $request)
    {
        try {
            $response = $this->userService->logoutUser(auth()->user());
            return response()->json($response);
        }catch (\Exception $e){
            return response()->json([
                "success" => false,
                "message" => $e,
            ]);
        }
    }


}
