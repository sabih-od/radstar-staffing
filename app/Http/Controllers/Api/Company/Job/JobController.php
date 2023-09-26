<?php

namespace App\Http\Controllers\Api\Company\Job;

use App\Criteria\Company\Job\ByCompanyIdCriteria;
use App\Helpers\APIResponse;
use App\Http\Controllers\Controller;
use App\Services\CompanyService;
use App\Services\JobService;
use App\Traits\Skills;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Companies\Job\JobRepository;
use App\Repositories\Companies\Job\JobApplyRepository;
use App\Repositories\Companies\Auth\CompanyRepository;
use Illuminate\Support\Facades\DB;


class JobController extends Controller
{
    use Skills;

    protected $jobRepository, $jobApplyRepository, $companyRepository, $jobService, $companyService;

    public function __construct
    (
        JobRepository $jobRepository,
        JobApplyRepository $jobApplyRepository,
        CompanyRepository $companyRepository,
        JobService $jobService,
        CompanyService $companyService
    )

    {
        $this->jobRepository = $jobRepository;
        $this->jobApplyRepository = $jobApplyRepository;
        $this->companyRepository = $companyRepository;
        $this->jobService = $jobService;
        $this->companyService = $companyService;
    }

    /**
     * @OA\Get(
     *     path="/company/job/all/{id}/{limit}/{page}",
     *     summary="Get Company Job ",
     *     tags={"Company"},
     *
     *       @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="company ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *    @OA\Parameter(
     *         name="limit",
     *         in="path",
     *         description="listing limit",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *        @OA\Parameter(
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
    public function get($id ,$limit,$page)
    {
        try {


//            Criteria is for filter querying of job model
            $this->jobRepository->pushCriteria(new ByCompanyIdCriteria($id));
            $jobs = $this->jobRepository->paginate($limit = $limit, $columns = ['*']);

            return APIResponse::success('My jobs', $jobs);
        } catch (\Exception $e) {
            return APIResponse::error($e->getMessage());
        }
    }

    /**
     * @OA\Post(
     *     path="/company/job/create",
     *     summary="Create Company Job",
     *     tags={"Company"},
     *     requestBody={
     *         "description": "Create Company Job",
     *         "required": true,
     *         "content": {
     *             "application/json": {
     *                 "schema": {
     *                     "type": "object",
     *                     "properties": {
     *                         "title": {
     *                             "type": "string",
     *                             "example": "Software Engineer oihooo"
     *                         },
     *                         "description": {
     *                             "type": "string",
     *                             "example": "We are looking for a talented software engineer to join our team."
     *                         },
     *                         "benefits": {
     *                             "type": "string",
     *                             "example": "Healthcare, flexible work hours, competitive salary"
     *                         },
     *                         "country_id": {
     *                             "type": "integer",
     *                             "example": 1
     *                         },
     *                         "state_id": {
     *                             "type": "integer",
     *                             "example": 2
     *                         },
     *                         "city_id": {
     *                             "type": "integer",
     *                             "example": 3
     *                         },
     *                         "is_freelance": {
     *                             "type": "boolean",
     *                             "example": false
     *                         },
     *                         "career_level_id": {
     *                             "type": "integer",
     *                             "example": 4
     *                         },
     *                         "salary_from": {
     *                             "type": "number",
     *                             "format": "double",
     *                             "example": 600000
     *                         },
     *                         "salary_to": {
     *                             "type": "number",
     *                             "format": "double",
     *                             "example": 800000
     *                         },
     *                         "salary_currency": {
     *                             "type": "string",
     *                             "example": "USD"
     *                         },
     *                         "hide_salary": {
     *                             "type": "boolean",
     *                             "example": false
     *                         },
     *                         "functional_area_id": {
     *                             "type": "integer",
     *                             "example": 5
     *                         },
     *                         "job_type_id": {
     *                             "type": "integer",
     *                             "example": 6
     *                         },
     *                         "job_shift_id": {
     *                             "type": "integer",
     *                             "example": 7
     *                         },
     *                         "num_of_positions": {
     *                             "type": "integer",
     *                             "example": 3
     *                         },
     *                         "gender_id": {
     *                             "type": "integer",
     *                             "example": 8
     *                         },
     *                         "expiry_date": {
     *                             "type": "string",
     *                             "format": "date",
     *                             "example": "2023-12-31"
     *                         },
     *                         "degree_level_id": {
     *                             "type": "integer",
     *                             "example": 9
     *                         },
     *                         "job_experience_id": {
     *                             "type": "integer",
     *                             "example": 10
     *                         },
     *                         "salary_period_id": {
     *                             "type": "integer",
     *                             "example": 11
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


    public function create(Request $request)
    {
        try {
            DB::beginTransaction();

            $company = Auth::guard('company_api')->user();
//            job create
            $data = $this->jobService->assignJobValues($request, $company);
            $job = $this->jobRepository->create($data);
//            job update
            $updateData = $this->jobService->setSlug($job);
            $job = $this->jobRepository->update($updateData, $job->id);
//            jobSKill Trait
            $this->storeJobSkills($request, $job->id);
//            job update
            $this->jobService->updateFullTextSearch($job);
//            company update
            $companyData = $this->companyService->setJobsQuota($company);
            $this->companyRepository->update($companyData, $company->id);

            DB::commit();

            return APIResponse::success("Job Posted Successfully",$job);
        } catch (\Exception $e) {
            DB::rollBack();

            return APIResponse::error($e->getMessage());
        }
    }
}
