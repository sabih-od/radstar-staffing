<?php

namespace App\Repositories\Users\Auth;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Users\Auth\UserRegisterRepository;
use App\Entities\Users\Auth\UserRegister;
use App\User;
/**
 * Class UserRegisterRepositoryEloquent.
 *
 * @package namespace App\Repositories\Users\Auth;
 */
class UserRegisterRepositoryEloquent extends BaseRepository implements UserRegisterRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return User::class;
    }

    public function createUser(array $data)
    {
        return $this->model->create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'phone' => isset($data['phone']) ? $data['phone'] : '',
        ]);
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
