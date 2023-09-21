<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Traits\CompanyTrait;
use ImgUploader;

class CompanyService
{
    use CompanyTrait;
    public function authenticateCompany(array $credentials)
    {
        try {
            config(['auth.guards.company_api.driver' => 'session']);
            if (Auth::guard('company_api')->attempt($credentials)) {
                $company = Auth::guard('company_api')->user();
                $token = $company->createToken('company_token')->accessToken;
                $selectedFields = $company->only('name', 'email', 'phone');
                return [
                    'company' => $selectedFields,
                    'access_token' => $token
                ];
            }
            return new \Exception("Invalid credentials.");
        } catch (\Exception $e) {
            return $e;
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

    public function getFormFields($requestData , $company)
    {
        if ($requestData->hasFile('logo')) {
           $this->deleteCompanyLogo($company->id);
            $image = $requestData->file('logo');
            $fileName = ImgUploader::UploadImage('company_logos', $image, $requestData->input('name'), 300, 300, false);
            $company->logo = $fileName;
        }
        return
            [
            'name' => $requestData->name,
            'email' => $requestData->email,
            'password' => !empty($requestData->input('password')) ? Hash::make($requestData->input('password')) : $company->password ,
            'ceo' => $requestData->ceo,
            'industry_id' => $requestData->industry_id,
            'ownership_type_id' => $requestData->ownership_type_id,
            'description' => $requestData->description,
            'location' => $requestData->location,
            'map' => $requestData->map,
            'no_of_offices' => $requestData->no_of_offices,
            'website' => (false === strpos($requestData->website, 'http')) ? 'http://' . $requestData->website : $requestData->website ,
            'no_of_employees' => $requestData->no_of_employees,
            'established_in' => $requestData->established_in,
            'fax' => $requestData->fax,
            'phone' => $requestData->phone,
            'facebook' => $requestData->facebook,
            'twitter' => $requestData->twitter,
            'linkedin' => $requestData->linkedin,
            'google_plus' => $requestData->google_plus,
            'pinterest' => $requestData->pinterest,
            'country_id' => $requestData->country_id,
            'state_id' => $requestData->state_id,
            'city_id' => $requestData->city_id,
            'is_subscribed' => $requestData->is_subscribed,
            'logo' => isset($fileName) ? $fileName : '' ,
            'slug' => Str::slug($company->name, '-') . '-' . $company->id,
        ];

    }
}