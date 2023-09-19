<?php

use Illuminate\Http\Request;
use App\Http\Controllers\ApiControllers\users\auth\RegisterController as UserRegisterController;
use App\Http\Controllers\ApiControllers\users\auth\LoginController as UserLoginController;
use App\Http\Controllers\Api\Company\Auth\RegisterController as CompanyRegisterController;
use App\Http\Controllers\Api\Company\Auth\LoginController as CompanyLoginController;
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
Route::post('candidate-register', [UserRegisterController::class, 'register']);
Route::post('candidate-login', [UserLoginController::class, 'login']);


//This for candidates which have User model
Route::middleware(['redirectIfUser', 'auth:user'])->group(function () {
        Route::post('candidate-logout', [UserLoginController::class, 'logout']);
});

// Routes for company authentication
Route::post('company-register', [CompanyRegisterController::class, 'register']);
Route::post('company-login', [CompanyLoginController::class, 'login']);

//This for companies which have Company model
Route::middleware(['redirectIfCompany', 'auth:company_api'])->group(function () {
    Route::post('company-logout', [CompanyLoginController::class, 'logout']);

});
