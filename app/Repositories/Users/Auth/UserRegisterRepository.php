<?php

namespace App\Repositories\Users\Auth;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface UserRegisterRepository.
 *
 * @package namespace App\Repositories\Users\Auth;
 */
interface UserRegisterRepository extends RepositoryInterface
{
    public function createUser(array $data);
}
