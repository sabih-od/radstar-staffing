<?php

use App\Http\Controllers\Api\Company\CompanyController;
use App\Http\Controllers\Api\location\CountryController;
use App\Http\Controllers\Api\location\StateController;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\User\Auth\RegisterController as UserRegisterController;
use App\Http\Controllers\Api\User\Auth\ForgotPasswordController as UserForgotPasswordController;
use App\Http\Controllers\Api\User\Auth\LoginController as UserLoginController;
use App\Http\Controllers\Api\User\UserController as UserController;

use App\Http\Controllers\Api\Company\Auth\RegisterController as CompanyRegisterController;
use App\Http\Controllers\Api\Company\Auth\LoginController as CompanyLoginController;
use App\Http\Controllers\Api\Company\Auth\ForgetPasswordController as CompanyForgetPasswordController;
use App\Http\Controllers\Api\Company\Job\JobController as CompanyJobController;
use App\Http\Controllers\Api\Company\Job\JobDetailController as CompanyJobDetailController;
use App\Http\Controllers\Api\Company\Job\JobSeekerController;

use App\Http\Controllers\Api\location\CityController;
use App\Http\Controllers\Api\User\Job\CandidateController;

use App\Http\Controllers\Api\Contact\ContactController;



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
Route::get('/countries', [CountryController::class, 'getCountries']);
Route::get('states/{countryId}', [StateController::class, 'getStates']);
Route::get('cities/{stateId}', [CityController::class, 'getCities']);
Route::post('contact', [ContactController::class, 'contact']);

Route::get('job-seekers/{limit}/{page}', [JobSeekerController::class, 'get']);


Route::group([
    'prefix' => 'candidate'
], function () {
    Route::post('register', [UserRegisterController::class, 'register']);
    Route::post('login', [UserLoginController::class, 'login']);

    Route::get('my-profile', [UserController::class, 'myProfile']);
    Route::post('profile/update', [UserController::class, 'updateProfile']);
    Route::post('profile/summary/update', [UserController::class, 'updateSummary']);
    Route::get('profile/Cv', [UserController::class, 'ProfileCv']);
    Route::post('profile/add/ProfileCv', [UserController::class, 'addProfileCv']);
    Route::post('profile/update/ProfileCv', [UserController::class, 'updateProfileCv']);

    Route::get('get', [CandidateController::class, 'get']);

    Route::post('password/email', [UserForgotPasswordController::class, 'sendResetLinkEmail']);
    Route::post('verify/otp', [UserForgotPasswordController::class, 'verifyOtp']);
    Route::post('password/reset', [UserForgotPasswordController::class, 'resetPassword']);


    //This for candidates which have User model
    Route::middleware(['redirectIfUser', 'auth:user'])->group(function () {
        Route::post('logout', [UserLoginController::class, 'logout']);
    });
});


Route::group([
    'prefix' => 'company'
], function () {
    // Routes for company authentication
//    Initial setup
    Route::post('register', [CompanyRegisterController::class, 'register']);
    Route::post('login', [CompanyLoginController::class, 'login']);
    Route::post('password/email', [CompanyForgetPasswordController::class, 'sendResetLinkEmail']);
    Route::post('verify/otp', [CompanyForgetPasswordController::class, 'verifyOtp']);
    Route::post('password/reset', [CompanyForgetPasswordController::class, 'resetPassword']);
 //   End Initial setup

//Get specific company detail
    Route::get('/detail/{id}/', [CompanyController::class, 'getCompanyDetail']);
 //Get specific company follower
    Route::get('/followers/{id}/{limit}/{page}', [CompanyController::class, 'getFollowers']);
//Get specific company Job
    Route::get('job-detail/{id}', [CompanyJobDetailController::class, 'getJobDetails']);

//This for companies which have Company model
    Route::middleware(['redirectIfCompany', 'auth:company_api'])->group(function () {
        Route::post('logout', [CompanyLoginController::class, 'logout']);
//        This is for company profile update
        Route::post('update', [CompanyController::class, 'update']);
        Route::group([
            'prefix' => 'job'
        ], function () {
            Route::get('all/{id}/{limit}/{page}', [CompanyJobController::class, 'get']);
//            Those users who applied for specific job
            Route::get('list-applied-users/{id}', [CompanyJobDetailController::class, 'listJobAppliedUsers']);

            Route::post('create', [CompanyJobController::class, 'create']);
            Route::get('dropdown_data', [CompanyJobDetailController::class, 'JobRelatedData']);
        });
    });


});



