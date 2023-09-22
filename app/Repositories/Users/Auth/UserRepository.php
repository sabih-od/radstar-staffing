<?php

namespace App\Repositories\Users\Auth;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface UserRepository.
 *
 * @package namespace App\Repositories\Users\Auth;
 */
interface UserRepository extends RepositoryInterface
{
    public function storeOTP($user_id, $otp);
    public function resetOTP($user_id, $otp, $otp_expire);
    public function resetPassword($user_id, $password);
    public function getFollowers($userIdsArray);

}
