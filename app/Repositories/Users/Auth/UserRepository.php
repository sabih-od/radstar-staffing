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
    public function authenticateUser(array $credentials);
    public function logoutUser($user);
}
