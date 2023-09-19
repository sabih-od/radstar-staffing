<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Users\Auth\UserRepository;
use App\Repositories\Users\Auth\UserRepositoryEloquent;
use App\Repositories\Companies\Auth\CompanyRepository;
use App\Repositories\Companies\Auth\CompanyRepositoryEloquent;
use App\Repositories\Companies\Job\JobRepository;
use App\Repositories\Companies\Job\JobRepositoryEloquent;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
//        $this->app->bind([
//            UserRepository::class => UserRepositoryEloquent::class,
//            CompanyRepository::class => CompanyRepositoryEloquent::class,
//        ]);
        // Bind UserRepository interface to UserRepositoryEloquent implementation
        $this->app->bind(UserRepository::class, UserRepositoryEloquent::class);
        // Bind CompanyRepository interface to CompanyRepositoryEloquent implementation
        $this->app->bind(CompanyRepository::class, CompanyRepositoryEloquent::class);
        // Bind JobRepository interface to JObRepositoryEloquent implementation
        $this->app->bind(JobRepository::class, JobRepositoryEloquent::class);
        $this->app->register(\L5Swagger\L5SwaggerServiceProvider::class);
    }

}
