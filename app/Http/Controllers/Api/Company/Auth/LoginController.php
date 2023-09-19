<?php

namespace App\Http\Controllers\Api\Company\Auth;

use App\Helpers\APIResponse;
use App\Http\Controllers\Controller;
use App\Services\CompanyService;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * @OA\PathItem(path="/company")
     */

    protected $companyService;
    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    /**
     * @OA\Post(
     *     path="/login",
     *     summary="Login for company",
     *     tags={"Authentication"},
     *     requestBody={
     *         "description": "User login credentials",
     *         "required": true,
     *         "content": {
     *             "application/json": {
     *                 "schema": {
     *                     "type": "object",
     *                     "properties": {
     *                         "email": {
     *                             "type": "string",
     *                             "example": "asd@asd.com",
     *                         },
     *                         "password": {
     *                             "type": "string",
     *                             "example": "asdasdasd",
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
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $authResult = $this->companyService->authenticateCompany($credentials);

        if($authResult instanceof \Exception)
            return APIResponse::error($authResult->getMessage());

        return APIResponse::success("Logged In Successfully", $authResult);
    }

    public function logout()
    {
        try {
            $response = $this->companyService->logoutCompany(Auth::guard('company_api'));
            return response()->json($response);
        }catch (\Exception $e){
            return response()->json([
                "success" => false,
                "message" => $e,
            ]);
        }

    }
}
