<?php

namespace App\Repositories\ContactMessage;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\ContactMessage\ContactMessageRepository;
use App\ContactMessage;
use App\Validators\ContactMessage\ContactMessageValidator;

/**
 * Class ContactMessageRepositoryEloquent.
 *
 * @package namespace App\Repositories\ContactMessage;
 */
class ContactMessageRepositoryEloquent extends BaseRepository implements ContactMessageRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ContactMessage::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
