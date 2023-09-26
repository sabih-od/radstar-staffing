<?php

namespace App\Http\Controllers\Api\Company\Job;

use App\Helpers\APIResponse;
use App\Helpers\DataArrayHelper;
use App\Http\Controllers\Api\Company\Job\JobController;
use App\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class JobDetailController extends JobController

{
    /***
* @OA\Get(
*     path="/company/job/job-detail/{id}",
*     summary="Get Job Details",
*     tags={"Company"},
*     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the job",
 *         @OA\Schema(
 *             type="integer",
 *             format="int64"
    *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="success",
 *                 type="boolean",
 *                 example=true,
 *                 description="A boolean value."
    *             ),
 *         ),
 *     ),
 * )
 */
    public function getJobDetails($id)
    {
        try {
            $job = $this->jobRepository->find($id);
            $response = $this->jobService->getJobRelatedData($job);
            return APIResponse::success('Job Data',$response);
        } catch (\Exception $e) {
            return APIResponse::error($e->getMessage());
        }
    }
    /**
     * @OA\Get(
     *     path="/company/job/dropdown_data",
     *     summary=" Job DropDown Data",
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

    public function JobRelatedData(Request $request)
    {
        try {
            return APIResponse::success('Dropdowns Job Data', $this->jobService->jobRelatedData());
        } catch (\Exception $e) {
            return APIResponse::error($e->getMessage());
        }

    }
}
