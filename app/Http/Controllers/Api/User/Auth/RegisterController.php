<?php

namespace App\Http\Controllers\Api\User\Auth;

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
     * @OA\Post(
     *     path="/candidate/register",
     *     summary="Register Candidate",
     *     tags={"Candidate"},
     *     requestBody={
     *         "description": "Register Candidate",
     *         "required": true,
     *         "content": {
     *             "application/json": {
     *                 "schema": {
     *                     "type": "object",
     *                     "properties": {
     *                       "first_name": {
     *                             "type": "string",
     *                             "example": "John",
     *                         },
     *                      "middle_name": {
     *                             "type": "string",
     *                             "example": "Smith",
     *                         },
     *                       "last_name": {
     *                             "type": "string",
     *                             "example": "JS",
     *                         },
     *                         "email": {
     *                             "type": "string",
     *                             "example": "john@gmail.com",
     *                         },
     *                         "password": {
     *                             "type": "string",
     *                             "example": "12345678",
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
