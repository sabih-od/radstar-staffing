<?php

namespace App\Http\Controllers\Api\Company;

use App\Company;

use App\Criteria\Company\Job\ByCompanyIdCriteria;
use App\Helpers\APIResponse;
use App\Http\Controllers\Controller;
use App\Repositories\Companies\Auth\CompanyRepository;
use App\Repositories\Companies\Subscription\SubscriptionRepository;
use App\Repositories\Users\Auth\UserRepository;
use App\Subscription;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Newsletter;
use App\Services\CompanyService;
use App\Services\SubscriptionService;


class CompanyController extends Controller
{
    protected
        $companyRepository,
        $userRepository,
        $subscriptionRepository,
        $subscriptionService,
        $companyService;

    public function __construct
    (
        CompanyRepository $companyRepository,
        SubscriptionRepository $subscriptionRepository,
        UserRepository $userRepository,
        CompanyService $companyService ,
        SubscriptionService $subscriptionService

    )
    {
        $this->companyRepository = $companyRepository;
        $this->subscriptionRepository = $subscriptionRepository;
        $this->userRepository = $userRepository;
        $this->companyService = $companyService;
        $this->subscriptionService = $subscriptionService;
    }
    /**
     * @OA\Post(
     *     path="/company/update",
     *     summary="Update Company",
     *     tags={"Company"},
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
    public function companyGuard()
    {
        return Auth::guard('company_api')->user();
    }

    public function update(Request $request)
    {
        try {
            $company = $this->companyRepository->find($this->companyGuard()->id);
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

    public function getFollowers()
    {
        try
        {
            $company = $this->companyRepository->find($this->companyGuard()->id);
            $userIdsArray = $company->getFollowerIdsArray();
            $users = $this->userRepository->getFollowers($userIdsArray);

            $followers = $this->companyGuard()->countFollowers();

            $response = $this->companyService->getFollowersAndCount($users, $followers);

            return APIResponse::success('My Company Followers', $response);
        }
        catch (\Exception $e)
        {
            return APIResponse::error($e->getMessage());
        }

    }



}
