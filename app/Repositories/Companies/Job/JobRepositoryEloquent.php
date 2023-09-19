<?php

namespace App\Repositories\Companies\Job;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Companies\Job\JobRepository;
use App\Job;
use App\Validators\Companies\Job\JobValidator;

/**
 * Class JobRepositoryEloquent.
 *
 * @package namespace App\Repositories\Companies\Job;
 */
class JobRepositoryEloquent extends BaseRepository implements JobRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Job::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
