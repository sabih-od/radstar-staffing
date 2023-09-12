<?php

use Illuminate\Http\Request;
use App\Http\Controllers\ApiControllers\users\auth\registerController;
use App\Http\Controllers\ApiControllers\users\auth\loginController;

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
Route::post('candidate-register', [registerController::class, 'candidateRegister']);
Route::post('candidate-login', [loginController::class, 'candidateLogin']);


//This for candidates which have User model
Route::middleware('auth:user')->group( function () {
    Route::get('hee', [loginController::class, 'xyz']);
});

//// Routes for company authentication
//Route::post('company-register', [CompanyController::class, 'register']);
//Route::post('company-login', [CompanyController::class, 'login']);

//This for companies which have Company model
Route::middleware('auth:company')->group(function () {
    // Company-specific routes here
});
