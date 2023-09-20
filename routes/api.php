<?php
use App\Http\Controllers\Api\location\CountryController;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\User\Auth\RegisterController as UserRegisterController;
use App\Http\Controllers\Api\User\Auth\LoginController as UserLoginController;
use App\Http\Controllers\Api\Company\Auth\RegisterController as CompanyRegisterController;
use App\Http\Controllers\Api\Company\Auth\LoginController as CompanyLoginController;
use App\Http\Controllers\Api\Company\Job\JobController as CompanyJobController;
use App\Http\Controllers\Api\Company\Job\JobDetailController as CompanyJobDetailController;
use App\Http\Controllers\Api\Company\CompanyController;

use Illuminate\Support\Facades\Route;

/*
  |--------------------------------------------------------------------------
  | API Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register API routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | is assigned the "api" middleware group. Enjoy building your API!
  |
 */
//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

// Routes for user authentication
Route::get('/countries',[CountryController::class, 'getCountries']);

Route::group([
    'prefix' => 'candidate'
], function () {
    Route::post('register', [UserRegisterController::class, 'register']);
    Route::post('login', [UserLoginController::class, 'login']);


    //This for candidates which have User model
    Route::middleware(['redirectIfUser', 'auth:user'])->group(function () {
        Route::post('logout', [UserLoginController::class, 'logout']);
    });
});



Route::group([
    'prefix' => 'company'
], function () {
    // Routes for company authentication
    Route::post('register', [CompanyRegisterController::class, 'register']);
    Route::post('login', [CompanyLoginController::class, 'login']);

//This for companies which have Company model
    Route::middleware(['redirectIfCompany', 'auth:company_api'])->group(function () {
        Route::post('logout', [CompanyLoginController::class, 'logout']);
        Route::post('update', [CompanyController::class, 'update']);

        Route::group([
            'prefix' => 'job'
        ], function () {
            Route::get('get', [CompanyJobController::class, 'get']);
            Route::post('create', [CompanyJobController::class, 'create']);
            Route::get('job_related_data', [CompanyJobDetailController::class, 'JobRelatedData']);

        });
    });


});



