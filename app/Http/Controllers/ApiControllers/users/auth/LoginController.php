<?php

namespace App\Http\Controllers\ApiControllers\users\auth;

use App\Http\Controllers\Controller;
use App\Repositories\Users\Auth\UserRepository;
use App\User;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function candidateLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        // Attempt to authenticate the user
        $authResult = $this->userRepository->authenticateUser($credentials);
        return response()->json($authResult);

    }

    public function xyz()
    {
        dd('dsdasdasd');
    }

}
