<?php

namespace App\Http\Controllers\ApiControllers\companies\auth;

use App\Http\Controllers\Controller;
use App\Repositories\Companies\Auth\CompanyRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct(CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    public function companyLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        // Attempt to authenticate the company
        $authResult = $this->companyRepository->authenticateCompany($credentials);
        return response()->json($authResult);

    }
}
