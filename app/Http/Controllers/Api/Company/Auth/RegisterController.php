<?php

namespace App\Http\Controllers\Api\Company\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\CompanyFrontRegisterFormRequest;
use App\Repositories\Companies\Auth\CompanyRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class RegisterController extends Controller
{
    public function __construct(CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    public function register(CompanyFrontRegisterFormRequest $request)
    {
        $password = Hash::make($request->input('password'));
        $array = [
            'name' => $request->input('name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => $password,
            // Add other fields and their values as needed
        ];
        $company = $this->companyRepository->create($array);
        $token =  $company->createToken('MyApp')->accessToken;
        return response()
            ->json(
                [
                    'success' => true,
                    'Message' => 'Company Register Successfully',
                    'data' => $company,
                    'access_token' => $token
                ]
            );
    }
}
