<?php

namespace App\Repositories\Users\Auth;

use Carbon\Carbon;
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

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function storeOTP($user_id, $otp)
    {
        return $this->update([
            'otp' => $otp,
            'otp_expire' => Carbon::now()->addMinutes(5)
        ], $user_id);
    }

    public function resetOTP($user_id, $otp, $otp_expire)
    {
        return $this->update([
            'otp' => $otp,
            'otp_expire' => $otp_expire
        ], $user_id);
    }

    public function resetPassword($user_id, $password)
    {
        return $this->update([
            'password' => $password
        ], $user_id);
    }

}
