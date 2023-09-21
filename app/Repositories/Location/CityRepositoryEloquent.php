<?php

namespace App\Repositories\Location;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Location\CityRepository;
use App\City;
use App\Validators\Location\CityValidator;

/**
 * Class CityRepositoryEloquent.
 *
 * @package namespace App\Repositories\Location;
 */
class CityRepositoryEloquent extends BaseRepository implements CityRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return City::class;
    }

    public function getStateId($stateId){
        return $this->model->query()->where('state_id', $stateId)->get();
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
