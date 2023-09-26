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
     *     path="/job-seekers/{limit}/{page}",
     *     summary=" Job Seeker",
     *     tags={"jobs"},
     *           @OA\Parameter(
     *         name="limit",
     *         in="path",
     *         description="listing limit",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *          *           @OA\Parameter(
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
    public function get(Request $request , $limit , $page)
    {
        try
        {
            $data = $this->jobSeekerService->getJobSeekers($request , $limit , $page);
            return APIResponse::success("All Job Seekers",$data);
        }
        catch (\Exception $e)
        {
            return APIResponse::error($e->getMessage());
        }

    }
}
