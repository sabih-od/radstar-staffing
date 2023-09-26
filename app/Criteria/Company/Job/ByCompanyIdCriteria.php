<?php

namespace App\Criteria\Company\Job;

use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class ByCompanyIdCriteria.
 *
 * @package namespace App\Criteria\Company\Job;
 */
class ByCompanyIdCriteria implements CriteriaInterface
{
    /**
     * Apply criteria in query repository
     *
     * @param string              $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    protected $companyId;

    public function __construct($companyId)
    {
        $this->companyId = $companyId;
    }

    public function apply($model, RepositoryInterface $repository)
    {
        return $model->where('company_id', $this->companyId);
    }
}
