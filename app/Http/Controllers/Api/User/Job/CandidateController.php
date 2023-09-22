<?php

namespace App\Http\Controllers\Api\User\Job;

use App\Helpers\APIResponse;
use App\Http\Controllers\Controller;
use App\Job;
use App\JobApply;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    public function get(){
        try {
            $jobs = JobApply::where('user_id',\Auth::id())->with('job','job.jobSkills.jobSkill')->get();
            return APIResponse::success('My jobs', $jobs);
        }catch (\Exception $e){
            return APIResponse::error($e->getMessage());

        }
    }
}
