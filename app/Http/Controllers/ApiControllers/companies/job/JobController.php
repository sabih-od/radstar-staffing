<?php

namespace App\Http\Controllers\ApiControllers\companies\job;

use App\Http\Controllers\Controller;
use App\Job;
use App\Services\CompanyService;
use App\Services\JobService;
use App\Traits\Skills;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Auth;
use App\Repositories\Companies\Job\JobRepository;
use App\Repositories\Companies\Auth\CompanyRepository;


class JobController extends Controller
{
    use Skills;
    protected $jobRepository , $companyRepository , $jobService , $companyService;

    public function __construct
    (
        JobRepository $jobRepository ,
        CompanyRepository $companyRepository ,
        JobService $jobService ,
        CompanyService $companyService
    )

    {
        $this->jobRepository = $jobRepository;
        $this->companyRepository = $companyRepository;
        $this->jobService = $jobService;
        $this->companyService = $companyService;
    }

    public function store (Request $request)
    {
        try
        {
            $company = Auth::guard('company_api')->user();
//            job create
            $data = $this->jobService->assignJobValues($request, $company);
            $job = $this->jobRepository->create($data);
//            job update
            $updateData =  $this->jobService->setSlug($job);
            $job = $this->jobRepository->update($updateData , $job->id);
//            jobSKill Trait
            $this->storeJobSkills($request, $job->id);
//            job update
            $this->jobService->updateFullTextSearch($job);
//            company update
            $companyData =  $this->companyService->setJobsQuota($company);
            $this->companyRepository->update($companyData , $company->id);

            return response()
                ->json(
                    [
                        'success' => true,
                        'Message' => 'Job Posted Successfully',
                        'data' => '',
                    ]
                );
        }
        catch (\Exception $e)
        {
            return response()
                ->json(
                    [
                      "success" => false,
                      "message" => $e->getMessage(),
                    ]
                );
        }
    }
}
