<?php

namespace App\Repositories\Companies\Job;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Companies\Job\JobApplyRepository;
use App\JobApply;
use App\Validators\Companies\Job\JobApplyValidator;

/**
 * Class JobApplyRepositoryEloquent.
 *
 * @package namespace App\Repositories\Companies\Job;
 */
class JobApplyRepositoryEloquent extends BaseRepository implements JobApplyRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return JobApply::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
