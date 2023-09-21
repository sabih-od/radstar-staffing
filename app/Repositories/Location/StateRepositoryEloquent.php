<?php

namespace App\Repositories\Location;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Location\StateRepository;
use App\State;
use App\Validators\Location\StateValidator;

/**
 * Class StateRepositoryEloquent.
 *
 * @package namespace App\Repositories\Location;
 */
class StateRepositoryEloquent extends BaseRepository implements StateRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return State::class;
    }

    public function getCountryId($countryId){
       return $this->model->query()->where('country_id', $countryId)->get();
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
