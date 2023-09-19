<?php

namespace App\Http\Controllers\Api\Company\Auth;

use App\Http\Controllers\Controller;
use App\Services\CompanyService;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    protected $companyService;
    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
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
            // Attempt to authenticate the company
            $authResult = $this->companyService->authenticateCompany($credentials);
            return response()->json($authResult);
        }catch (\Exception $e){
            return response()->json([
                "success" => false,
                "message" => $e->getMessage(),
            ]);
        }
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
