<?php

namespace App\Repositories\Companies\Auth;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Companies\Auth\CompanyRepository;
use App\Company;
use App\Validators\Companies\Auth\CompanyValidator;

/**
 * Class CompanyRepositoryEloquent.
 *
 * @package namespace App\Repositories\Companies\Auth;
 */
class CompanyRepositoryEloquent extends BaseRepository implements CompanyRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Company::class;
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
