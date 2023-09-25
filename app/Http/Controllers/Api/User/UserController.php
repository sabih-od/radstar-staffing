<?php

namespace App\Http\Controllers\Api\User;

use App\Helpers\APIResponse;
use App\Http\Controllers\Controller;
use App\Services\Users\UserProfileService;
use App\Repositories\Users\Auth\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $userProfileService;
    protected $userRepository;
    protected $candidate;

    public function __construct(UserProfileService $userProfileService, UserRepository $userRepository)
    {
        $this->userProfileService = $userProfileService;
        $this->userRepository = $userRepository;
        $this->candidate = $this->userRepository->find(Auth::guard('user')->user()->id);
    }

    /**
     * @OA\Get(
     *     path="/candidate/my-profile",
     *     summary=" Candidate profile",
     *     tags={"Candidate"},
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
    public function myProfile(){

        $response = $this->userProfileService->profile($this->candidate);

        if ($response instanceof \Exception) {
            return APIResponse::error($response->getMessage());
        }

        return APIResponse::success('Candidate profile',$response);
    }


    /**
     * @OA\Post(
     *     path="/candidate/profile/update",
     *     summary="Candidate update profile",
     *     tags={"Candidate"},
     *     requestBody={
     *         "required": true,
     *         "content": {
     *             "application/json": {
     *                 "schema": {
     *                     "type": "object",
     *                     "properties": {
     *                         "first_name": {
     *                             "type": "string",
     *                             "example": "john"
     *                         },
     *                         "middle_name": {
     *                             "type": "string",
     *                             "example": "smith"
     *                         },
     *                         "last_name": {
     *                             "type": "string",
     *                             "example": "john"
     *                         },
     *                         "name": {
     *                             "type": "string",
     *                             "example": "test"
     *                         },
     *                         "email": {
     *                             "type": "string",
     *                             "example": "john@gmail.com"
     *                         },
     *                         "password": {
     *                             "type": "string"
     *                         },
     *                         "father_name": {
     *                             "type": "string"
     *                         },
     *                         "date_of_birth": {
     *                             "type": "string"
     *                         },
     *                         "gender_id": {
     *                             "type": "integer"
     *                         },
     *                         "marital_status_id": {
     *                             "type": "integer"
     *                         },
     *                         "nationality_id": {
     *                             "type": "integer"
     *                         },
     *                         "national_id_card_number": {
     *                             "type": "string"
     *                         },
     *                         "country_id": {
     *                             "type": "integer"
     *                         },
     *                         "state_id": {
     *                             "type": "integer"
     *                         },
     *                         "city_id": {
     *                             "type": "integer"
     *                         },
     *                         "phone": {
     *                             "type": "string"
     *                         },
     *                         "mobile_num": {
     *                             "type": "string"
     *                         },
     *                         "job_experience_id": {
     *                             "type": "integer"
     *                         },
     *                         "career_level_id": {
     *                             "type": "integer"
     *                         },
     *                         "industry_id": {
     *                             "type": "integer"
     *                         },
     *                         "functional_area_id": {
     *                             "type": "integer"
     *                         },
     *                         "current_salary": {
     *                             "type": "number"
     *                         },
     *                         "expected_salary": {
     *                             "type": "number"
     *                         },
     *                         "salary_currency": {
     *                             "type": "string"
     *                         },
     *                         "street_address": {
     *                             "type": "string"
     *                         }
     *                     }
     *                 }
     *             }
     *         }
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
     *                 )
     *             )
     *         )
     *     }
     * )
     */
    public function updateProfile(Request $request){

        $response = $this->userProfileService->updateProfile($request,$this->candidate);

        if ($response instanceof \Exception) {
            return APIResponse::error($response->getMessage());
        }

        return APIResponse::success('You have updated your profile successfully',$response);
    }

    /**
     * @OA\Post(
     *     path="/candidate/profile/summary/update",
     *     summary=" Candidate summary update",
     *     tags={"Candidate"},
     *     requestBody={
     *         "required": true,
     *         "content": {
     *             "application/json": {
     *                 "schema": {
     *                     "type": "object",
     *                     "properties": {
     *                         "summary": {
     *                             "type": "string",
     *                             "example": "lorem ipsem"
     *                          }
     *                     }
     *                 }
     *             }
     *         }
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
     *                 )
     *             )
     *         )
     *     }
     * )
     */
    public function updateSummary(Request $request){
        $summary = $request->input('summary');
        $response = $this->userProfileService->updateProfileSummary($this->candidate,$summary);

        if ($response instanceof \Exception) {
            return APIResponse::error($response->getMessage());
        }

        return APIResponse::success('You have updated your profile summary successfully',$response);
    }

    /**
     * @OA\Get(
     *     path="/candidate/profile/Cv",
     *     summary=" Candidate profile cv",
     *     tags={"Candidate"},
     *     responses={
     *         @OA\Response(
     *             response=200,
     *             description="OK",
     *         ),
     *     },
     * )
     */
    public function ProfileCv(){

        $response = $this->userProfileService->ProfileCv($this->candidate);
        if ($response instanceof \Exception) {
            return APIResponse::error($response->getMessage());
        }

        return APIResponse::success('Profile CVs',$response);
    }

    /**
     * @OA\Post(
     *     path="/candidate/profile/add/ProfileCv",
     *     summary="Get Candidate Profile CV",
     *     tags={"Candidate"},
     *     requestBody={
     *         "required": true,
     *         "content": {
     *             "application/json": {
     *                 "schema": {
     *                     "type": "object",
     *                     "properties": {
     *                         "cv_file": {
     *                             "type": "string",
     *                             "example": "resume.pdf"
     *                         }
     *                     "properties": {
     *                         "title": {
     *                             "type": "string",
     *                             "example": "resume"
     *                         }
     *                     "properties": {
     *                         "is_default": {
     *                             "type": "integrer",
     *                             "example": "1,0"
     *                         }
     *                     }
     *                 }
     *             }
     *         }
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
     *                 )
     *             )
     *         )
     *     }
     * )
     */
    public function addProfileCv(Request $request){

        $response = $this->userProfileService->addProfileCv($this->candidate,$request);

        if ($response instanceof \Exception) {
            return APIResponse::error($response->getMessage());
        }

        return APIResponse::success('Profile CV add successfully',$response);
    }

    public function updateProfileCv(Request $request){

        $response = $this->userProfileService->updateProfileCv($this->candidate,$request);

        if ($response instanceof \Exception) {
            return APIResponse::error($response->getMessage());
        }

        return APIResponse::success('Profile CV update successfully',$response);
    }
}