<?php

namespace App\Http\Controllers\ApiControllers\users\auth;

use App\Helpers\APIResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserApiRegisterFromRequest;
use App\Http\Requests\Front\UserFrontRegisterFormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\Repositories\Users\Auth\UserRepository;


class RegisterController extends Controller
{

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @OA\post(
     *     path="/candidate-register",
     *     @OA\Response(response="200", description="An example endpoint")
     * )
     */
    public function register(UserApiRegisterFromRequest $request)
    {
        try {
            $password = Hash::make($request->input('password'));
            $array = [
                'first_name' => $request->input('first_name'),
                'middle_name' => $request->input('middle_name'),
                'last_name' => $request->input('last_name'),
                'email' => $request->input('email'),
                'password' => $password,
                // Add other fields and their values as needed
            ];
            $user = $this->userRepository->create($array);
            $token = $user->createToken('MyApp')->accessToken;

            return APIResponse::success('User Register Successfully', [
                'user' => $user,
                'access_token' => $token
            ]);
        } catch (\Exception $e) {
            return APIResponse::error($e->getMessage());
        }
    }
}
