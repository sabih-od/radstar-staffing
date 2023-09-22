<?php

namespace App\Http\Controllers\Api\Company\Job;

use App\Helpers\APIResponse;
use App\Helpers\DataArrayHelper;
use App\Http\Controllers\Controller;
use App\Repositories\Companies\Auth\CompanyRepository;
use App\Services\JobSeekerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobSeekerController extends Controller
{
    public function __construct(JobSeekerService $jobSeekerService)
    {
        $this->jobSeekerService = $jobSeekerService;
    }

    /**
     * @OA\Get(
     *     path="/company/job-seekers",
     *     summary=" Job Seeker",
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
    public function get(Request $request)
    {
        try
        {
            $data = $this->jobSeekerService->getJobSeekers($request);
            return APIResponse::success("All Job Seekers",$data);
        }
        catch (\Exception $e)
        {
            return APIResponse::error($e->getMessage());
        }

    }
}
