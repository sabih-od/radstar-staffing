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
use App\Repositories\Companies\Auth\CompanyRepository;
use Illuminate\Support\Facades\DB;


class JobController extends Controller
{
    use Skills;

    protected $jobRepository, $companyRepository, $jobService, $companyService;

    public function __construct
    (
        JobRepository     $jobRepository,
        CompanyRepository $companyRepository,
        JobService        $jobService,
        CompanyService    $companyService
    )

    {
        $this->jobRepository = $jobRepository;
        $this->companyRepository = $companyRepository;
        $this->jobService = $jobService;
        $this->companyService = $companyService;
    }

    public function get()
    {
        try
        {
//            Criteria is for filter querying of job model
            $this->jobRepository->pushCriteria(ByCompanyIdCriteria::class);
            $jobs = $this->jobRepository->paginate(null, ['*']);

            return APIResponse::success('My jobs' , $jobs);
        } catch (\Exception $e) {
            return APIResponse::error($e->getMessage());
        }
    }

    /**
     * @OA\Post(
     *     path="/company/job/create",
     *     summary="Create Company Job ",
     *     tags={"Company"},
     *     requestBody={
     *         "description": "Create Company Job",
     *         "required": true,
     *         "content": {
     *             "application/json": {
     *                 "schema": {
     *                     "type": "object",
     *                     "properties": {
     *                         "email": {
     *                             "type": "string",
     *                             "example": "john@gmail.com",
     *                         },
     *                         "password": {
     *                             "type": "string",
     *                             "example": "12345678",
     *                         },
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

            return APIResponse::success("Job Posted Successfully");
        } catch (\Exception $e) {
            DB::rollBack();

            return APIResponse::error($e->getMessage());
        }
    }
}
