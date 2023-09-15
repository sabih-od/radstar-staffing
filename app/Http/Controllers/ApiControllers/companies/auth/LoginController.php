<?php

namespace App\Http\Controllers\ApiControllers\companies\auth;

use App\Http\Controllers\Controller;
use App\Services\Company;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    protected $company;
    public function __construct(Company $company)
    {
        $this->company = $company;
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
            $authResult = $this->company->authenticateCompany($credentials);
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
            $response = $this->company->logoutCompany(Auth::guard('company_api'));
            return response()->json($response);
        }catch (\Exception $e){
            return response()->json([
                "success" => false,
                "message" => $e,
            ]);
        }

    }
}
