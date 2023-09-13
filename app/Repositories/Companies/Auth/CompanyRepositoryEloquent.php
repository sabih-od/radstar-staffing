<?php

namespace App\Repositories\Companies\Auth;

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

    public function authenticateCompany(array $credentials)
    {
        if (Auth::guard('company')->attempt($credentials)) {
            $company = Auth::guard('company')->user();
            $token = $company->createToken('company_token')->accessToken;
            $selectedFields = $company->only('name', 'email', 'phone');
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

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
