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

    public function companyGuard()
    {
        return Auth::guard('company_api')->user();
    }

    /**
     * @OA\Post(
     *     path="/company/update",
     *     summary="Update Company",
     *     tags={"Company"},
     *          *     requestBody={
     *         "description": "Company Updated Successfully",
     *         "required": true,
     *         "content": {
     *             "application/json": {
     *                 "schema": {
     *                     "type": "object",
     *                     "properties": {
     *                         "name": {
     *                             "type": "string",
     *                             "example": "Star llc",
     *                         },
     *                         "email": {
     *                             "type": "string",
     *                             "example": "star@gmail.com",
     *                         },
     *                         "password": {
     *                             "type": "string",
     *                             "example": "6434897454dff",
     *                         },
     *                         "ceo": {
     *                             "type": "string",
     *                             "example": "Smith",
     *                         },
     *                         "industry_id": {
     *                             "type": "integer",
     *                             "example": 1,
     *                         },
     *                         "ownership_type_id": {
     *                             "type": "integer",
     *                             "example": 2,
     *                         },
    "description": {
     *                             "type": "string",
     *                             "example": "Lorem ispum ",
     *                         },
     *                         "location": {
     *                             "type": "string",
     *                             "example": "Company Location",
     *                         },
     *                         "no_of_offices": {
     *                             "type": "integer",
     *                             "example": 5,
     *                         },
     *                         "website": {
     *                             "type": "string",
     *                             "example": "http://companywebsite.com",
     *                         },
     *                         "no_of_employees": {
     *                             "type": "integer",
     *                             "example": 50,
     *                         },
     *                         "established_in": {
     *                             "type": "integer",
     *                             "example": 1995,
     *                         },
     *                         "fax": {
     *                             "type": "string",
     *                             "example": "Company Fax",
     *                         },
     *                         "phone": {
     *                             "type": "string",
     *                             "example": "+1-202-555-0105",
     *                         },
     *                         "facebook": {
     *                             "type": "string",
     *                             "example": "https://www.facebook.com/",
     *                         },
     *                         "twitter": {
     *                             "type": "string",
     *                             "example": "https://twitter.com/",
     *                         },
     *                         "linkedin": {
     *                             "type": "string",
     *                             "example": "https://www.linkedin.com/login",
     *                         },
     *                         "google_plus": {
     *                             "type": "string",
     *                             "example": "",
     *                         },
     *                         "pinterest": {
     *                             "type": "string",
     *                             "example": "",
     *                         },
     *                         "country_id": {
     *                             "type": "integer",
     *                             "example": 1,
     *                         },
     *                         "state_id": {
     *                             "type": "integer",
     *                             "example": 1,
     *                         },
     *                         "city_id": {
     *                             "type": "integer",
     *                             "example": 3,
     *                         },
     *                         "is_subscribed": {
     *                             "type": "integer",
     *                             "example": 1,
     *                         },
     *                         "logo": {
     *                             "type": "",
     *                             "example": "http://example.com/path/to/logo.jpg",
     *                         },
     *
     *                     },
     *                 },
     *             },
     *         },
     *     },
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

    /**
     * @OA\Get(
     *     path="/company/{id}",
     *     summary="Get Company Details by ID",
     *     tags={"Company"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the company to fetch details for",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Company details",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Company not found",
     *     ),
     * )
     */

    public function getCompanyDetail($id)
    {
        try
        {
            $company = $this->companyRepository->find($id);
            return APIResponse::success('Company Details', $company);
        }
        catch (\Exception $e) {
            return APIResponse::error($e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/company/followers/{id}/{limit}/{page}",
     *     summary=" Company followers",
     *     tags={"Company"},
     *       @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Company ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *      @OA\Parameter(
     *         name="limit",
     *         in="path",
     *         description="listing limit",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *      @OA\Parameter(
     *         name="page",
     *         in="path",
     *         description="listing page",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
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
    public function getFollowers($id,$limit,$page)
    {
        try
        {
            $company = $this->companyRepository->find($id);

            if($company)
            {
                $userIdsArray = $company->getFollowerIdsArray();
                $users = $this->userRepository->getFollowers($userIdsArray,$limit,$page);

                $followers = $company->countFollowers();

                $response = $this->companyService->getFollowersAndCount($users, $followers);

                return APIResponse::success($response);

            }
        }
        catch (\Exception $e)
        {
            if($e->getCode()===0)
            {
                return APIResponse::error('no company found');
            }
            return APIResponse::error($e->getMessage());
        }

    }



}
