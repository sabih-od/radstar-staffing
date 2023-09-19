<?php

namespace App\Http\Controllers\ApiControllers\companies\auth;

use App\Helpers\APIResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CompanyApiRegisterFormRequest;
use App\Http\Requests\Front\CompanyFrontRegisterFormRequest;
use App\Repositories\Companies\Auth\CompanyRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class RegisterController extends Controller
{
    protected $companyRepository;

    public function __construct(CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    public function register(CompanyApiRegisterFormRequest $request)
    {
        try {
            $password = Hash::make($request->input('password'));
            $array = [
                'name' => $request->input('name'),
                'last_name' => $request->input('last_name'),
                'email' => $request->input('email'),
                'password' => $password,
                // Add other fields and their values as needed
            ];
            $company = $this->companyRepository->create($array);
            $token = $company->createToken('MyApp')->accessToken;

            return APIResponse::success(
                'Company Register Successfully',
                compact('company', 'token')
            );
        } catch (\Exception $e) {
            return APIResponse::error($e->getMessage());
        }
    }
}
