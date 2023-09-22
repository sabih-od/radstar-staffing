<?php

namespace App\Http\Controllers\Api\User;

use App\Helpers\APIResponse;
use App\Http\Controllers\Controller;
use App\Services\UserService;
use Auth;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    /**
     * @OA\Get(
     *     path="/candidate/my-profile",
     *     summary=" Candidate profile",
     *     tags={"Candidate"},
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
    public function myProfile(){

        $userId = Auth::guard('user')->user()->id;
        $response = $this->userService->profile($userId);

        if ($response instanceof \Exception) {
            return APIResponse::error($response->getMessage());
        }

        return APIResponse::success('Candidate profile',$response);
    }
}