<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class UserService
{
    public function authenticateUser(array $credentials)
    {
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('MyApp')->accessToken;
            $selectedFields = $user->only('first_name', 'email', 'phone', 'date_of_birth');
            return [
                'success' => true,
                'message' => 'Logged In Successfully',
                'data' => $selectedFields,
                'access_token' => $token
            ];
        } else {
            return [
                'success' => false,
                'error' => 'Unauthorized',
            ];
        }
    }

    public function logoutUser($user)
    {
        $user->token()->revoke();
        return [
            'success' => true,
            'message' => 'Logout successfully',
        ];
    }
}