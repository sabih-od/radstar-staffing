<?php

Route::prefix('company')->name('company.')->group(function () {
    Route::get('/', 'Company\Auth\LoginController@showLoginForm');
    Route::get('/login', 'Company\Auth\LoginController@showLoginForm')->name('login');
    Route::post('/login', 'Company\Auth\LoginController@login');
    Route::post('/logout', 'Company\Auth\LoginController@logout')->name('logout');

    // Registration Routes...
    Route::get('/register', 'Company\Auth\RegisterController@showRegistrationForm')->name('register');
    Route::post('/register', 'Company\Auth\RegisterController@register');
    Route::get('/password/reset/request', 'Company\Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('/password/email', 'Company\Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('/enter/otp/form', 'Company\Auth\ForgotPasswordController@enterOtpForm')->name('enter.otp.form');
    Route::post('/verify/otp', 'Company\Auth\ForgotPasswordController@verifyOtp')->name('verify.otp');
    Route::get('/password/reset', 'Company\Auth\ResetPasswordController@showResetForm')->name('password.reset.form');
    Route::post('/password/reset', 'Company\Auth\ResetPasswordController@resetPassword')->name('password.reset');
});
