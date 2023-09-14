<?php

namespace App\Http\Controllers\ApiControllers\companies\auth;

use App\Http\Controllers\Controller;
use App\Repositories\Companies\Auth\CompanyRepository;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct(CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
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
        // Attempt to authenticate the company
        $authResult = $this->companyRepository->authenticateCompany($credentials);
        return response()->json($authResult);
    }

    public function logout()
    {
        $response = $this->companyRepository->logoutCompany(Auth::guard('company_api'));
        return response()->json($response);
    }
}
