<?php

namespace App\Repositories\Companies\Subscription;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface SubscriptionRepository.
 *
 * @package namespace App\Repositories\Companies\Subscription;
 */
interface SubscriptionRepository extends RepositoryInterface
{
    public function deleteByEmail($email);

}
