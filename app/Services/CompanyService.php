<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CompanyService
{
    public function authenticateCompany(array $credentials)
    {
        config(['auth.guards.company_api.driver' => 'session']);
        if (Auth::guard('company_api')->attempt($credentials)) {
            $company = Auth::guard('company_api')->user();
            $token = $company->createToken('company_token')->accessToken;
            $selectedFields = $company->only('name', 'email', 'phone');
            return [
                'success' => true,
                'message' => 'Logged In Successfully',
                'data' => $selectedFields,
                'access_token' => $token
            ];
        } else {
            return [
                'success' => false,
                'error' => 'Unauthorized',
            ];
        }
    }

    public function logoutCompany($company)
    {
        $company->user()->token()->revoke();
        return [
            'success' => true,
            'message' => 'Logout successfully',
        ];
    }

    public function setJobsQuota($company)
    {
        return ['availed_jobs_quota' => $company->availed_jobs_quota + 1];
    }

}