<?php

namespace App\Repositories\Companies\Job;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Companies\Job\JobRepository;
use App\Job;
use App\Validators\Companies\Job\JobValidator;
use App\Criteria\Company\Job\ByCompanyIdCriteria;


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

    /**
     * Boot up the repository, pushing criteria
     */

    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
//        $this->pushCriteria(ByCompanyIdCriteria::class);

    }

    public function model()
    {
        return Job::class;
    }


    
}
