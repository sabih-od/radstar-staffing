<?php

namespace App\Http\Controllers\Api\Company\Job;

use App\Helpers\APIResponse;
use App\Helpers\DataArrayHelper;
use App\Http\Controllers\Api\Company\Job\JobController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class JobDetailController extends JobController

{
    public function JobRelatedData(Request $request)
    {
        try {
            return APIResponse::success('Dropdowns Job Data', $this->jobService->jobRelatedData());
        } catch (\Exception $e) {
            return APIResponse::error($e->getMessage());
        }

    }
}
