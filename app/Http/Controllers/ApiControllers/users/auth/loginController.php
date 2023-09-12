<?php

namespace App\Http\Controllers\ApiControllers\users\auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class loginController extends Controller
{

    public function candidateLogin(Request $request)
    {
        // Validation (customize as needed)
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        // Attempt to authenticate the user
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('MyApp')->accessToken;
            $selectedFields = $user->only('first_name', 'email', 'phone', 'date_of_birth');
            // Return a response with the user and access token
            return response()->json(['Message' => 'Logged In Successfully' , 'user' => $selectedFields, 'access_token' => $token]);
        } else {
            // Authentication failed
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    }

    public function candidateLogin2()
    {
        dd('dsdasdasd');
    }

}
