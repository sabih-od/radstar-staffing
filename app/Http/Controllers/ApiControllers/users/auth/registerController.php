<?php

namespace App\Http\Controllers\ApiControllers\users\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\UserFrontRegisterFormRequest;
use Illuminate\Http\Request;

use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Repositories\Users\Auth\UserRegisterRepository;


class registerController extends Controller
{

    public function __construct(UserRegisterRepository $userRegisterRepository)
    {
        $this->userRegisterRepository = $userRegisterRepository;
    }

    public function candidateRegister(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);
        $user = $this->userRegisterRepository->createUser($request->all());
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        return response()
            ->json(
                [
                'Message' => 'Register Successfully',
                'user' => $user,
                'access_token' => $success
               ]
               );
    }
}
