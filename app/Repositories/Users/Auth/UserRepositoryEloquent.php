<?php

namespace App\Repositories\Users\Auth;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Users\Auth\UserRepository;
use App\User;
use App\Validators\Users\Auth\UserValidator;
use Illuminate\Support\Facades\Auth;


/**
 * Class UserRepositoryEloquent.
 *
 * @package namespace App\Repositories\Users\Auth;
 */
class UserRepositoryEloquent extends BaseRepository implements UserRepository
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

    public function authenticateUser(array $credentials)
    {
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('MyApp')->accessToken;
            $selectedFields = $user->only('first_name', 'email', 'phone', 'date_of_birth');
            return [
                'success' => true,
                'message' => 'Logged In Successfully',
                'data' => $selectedFields,
                'access_token' => $token
            ];
        } else {
            return [
                'success' => false,
                'error' => 'Unauthorized',
            ];
        }
    }

    public function logoutUser($user)
    {
        $user->token()->revoke();
        return [
        'success' => true,
        'message' => 'Logout successfully',
       ];
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
