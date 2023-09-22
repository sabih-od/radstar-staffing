<?php

namespace App\Repositories\Companies\Auth;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface CompanyRepository.
 *
 * @package namespace App\Repositories\Companies\Auth;
 */
interface CompanyRepository extends RepositoryInterface
{
    public function storeOTP($user_id, $otp);

    public function resetOTP($user_id, $otp, $otp_expire);

    public function resetPassword($user_id, $password);

}
