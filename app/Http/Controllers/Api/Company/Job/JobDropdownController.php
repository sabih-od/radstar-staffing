<?php

namespace App\Http\Controllers\Api\Company\Job;

use App\Helpers\APIResponse;
use App\Helpers\DataArrayHelper;
use App\Http\Controllers\Api\Company\Job\JobController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class JobDropdownController extends JobController

{

    /**
     * @OA\Get(
     *     path="/company/job/dropdown_data",
     *     summary=" Job Related Data",
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
