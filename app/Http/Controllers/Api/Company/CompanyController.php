<?php

namespace App\Http\Controllers\Api\Company;

use App\Company;

use App\Criteria\Company\Job\ByCompanyIdCriteria;
use App\Helpers\APIResponse;
use App\Http\Controllers\Controller;
use App\Repositories\Companies\Auth\CompanyRepository;
use App\Repositories\Companies\Subscription\SubscriptionRepository;

use App\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Newsletter;
use App\Services\CompanyService;
use App\Services\SubscriptionService;


class CompanyController extends Controller
{
    protected $companyService , $companyRepository , $subscriptionRepository , $subscriptionService;

    public function __construct
    (
        CompanyService    $companyService ,
        CompanyRepository $companyRepository,
        SubscriptionRepository $subscriptionRepository,
        SubscriptionService $subscriptionService
    )
    {
        $this->companyService = $companyService;
        $this->companyRepository = $companyRepository;
        $this->subscriptionRepository = $subscriptionRepository;
        $this->subscriptionService = $subscriptionService;
    }

    public function update(Request $request)update
    {
        try {
            $company = $this->companyRepository->find(Auth::guard('company_api')->user()->id);
            $updatedData = $this->companyService->getFormFields($request, $company);
            $company = $this->companyRepository->update($updatedData, $company->id);

            $this->subscriptionRepository->deleteByEmail($company->email);
            if((bool)$company->is_subscribed)
            {
                $subscriptionData = $this->subscriptionService->getFields($company);
                $subscription = $this->subscriptionRepository->create($subscriptionData);
                Newsletter::subscribeOrUpdate($subscription->email, ['FNAME'=>$subscription->name]);
            }
            else
            {
                Newsletter::unsubscribe($company->email);
            }
            return APIResponse::success('Company Profile Updated Sucessfully', $company);
        } catch (\Exception $e) {
            return APIResponse::error($e->getMessage());
        }

    }


}
