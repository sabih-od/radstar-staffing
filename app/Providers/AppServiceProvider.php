<?php

namespace App\Providers;


use App\Repositories\Location\CityRepository;
use App\Repositories\Location\CityRepositoryEloquent;
use App\Repositories\Location\CountryRepository;
use App\Repositories\Location\CountryRepositoryEloquent;
use App\Repositories\Location\StateRepository;
use App\Repositories\Location\StateRepositoryEloquent;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Users\Auth\UserRepository;
use App\Repositories\Users\Auth\UserRepositoryEloquent;
use App\Repositories\Companies\Auth\CompanyRepository;
use App\Repositories\Companies\Auth\CompanyRepositoryEloquent;
use App\Repositories\Companies\Job\JobRepository;
use App\Repositories\Companies\Job\JobRepositoryEloquent;
use App\Repositories\Companies\Subscription\SubscriptionRepository;
use App\Repositories\Companies\Subscription\SubscriptionRepositoryEloquent;
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
        // Bind SubscriptionRepository interface to SubscriptionRepositoryEloquent implementation
        $this->app->bind(SubscriptionRepository::class, SubscriptionRepositoryEloquent::class);
//        $this->app->register(\L5Swagger\L5SwaggerServiceProvider::class);
        // Bind CountryRepository interface to CountryRepositoryInterface implementation
        $this->app->bind(CountryRepository::class, CountryRepositoryEloquent::class);
        // Bind StateRepository interface to StateRepositoryEloquent implementation
        $this->app->bind(StateRepository::class, StateRepositoryEloquent::class);
        // Bind CityRepository interface to CityRepositoryEloquent implementation
        $this->app->bind(CityRepository::class, CityRepositoryEloquent::class);
    }

}
