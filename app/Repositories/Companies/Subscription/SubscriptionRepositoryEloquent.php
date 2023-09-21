<?php

namespace App\Repositories\Companies\Subscription;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Companies\Subscription\SubscriptionRepository;
use App\Subscription;

/**
 * Class SubscriptionRepositoryEloquent.
 *
 * @package namespace App\Repositories\Companies\Subscription;
 */
class SubscriptionRepositoryEloquent extends BaseRepository implements SubscriptionRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Subscription::class;
    }

    public function deleteByEmail($email)
    {
        return $this->model->where('email', 'like', $email)->delete();
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
