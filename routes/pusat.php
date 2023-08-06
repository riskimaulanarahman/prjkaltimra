<?php

use Illuminate\Support\Facades\Route;

// // Dashboard
// Route::get('/', 'HomeController@index')->name('home');

// // Login
// Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
// Route::post('login', 'Auth\LoginController@login');
// Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// // Register
// Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
// Route::post('register', 'Auth\RegisterController@register');

// // Reset Password
// Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
// Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
// Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
// Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

// // Confirm Password
// Route::get('password/confirm', 'Auth\ConfirmPasswordController@showConfirmForm')->name('password.confirm');
// Route::post('password/confirm', 'Auth\ConfirmPasswordController@confirm');

// // Verify Email
// // Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
// // Route::get('email/verify/{id}/{hash}', 'Auth\VerificationController@verify')->name('verification.verify');
// // Route::post('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');

// Route::auto('/proposal', App\Http\Controllers\Pusat\ProposalController::class);

$maindealerRoutes = function() {

    // Dashboard
    Route::get('/', 'App\Http\Controllers\Pusat\HomeController@index')->name('home');
    Route::get('/dashboardlpj', 'App\Http\Controllers\Pusat\HomeController@index2')->name('home2');

    // Login
    Route::get('login', 'App\Http\Controllers\Pusat\Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'App\Http\Controllers\Pusat\Auth\LoginController@login');
    Route::post('logout', 'App\Http\Controllers\Pusat\Auth\LoginController@logout')->name('logout');

    // Register
    Route::get('register', 'App\Http\Controllers\Pusat\Auth\RegisterController@showRegistrationForm')->name('register');
    Route::post('register', 'App\Http\Controllers\Pusat\Auth\RegisterController@register');

    // Reset Password
    Route::get('password/reset', 'App\Http\Controllers\Pusat\Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'App\Http\Controllers\Pusat\Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('password/reset/{token}', 'App\Http\Controllers\Pusat\Auth\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/reset', 'App\Http\Controllers\Pusat\Auth\ResetPasswordController@reset')->name('password.update');

    // Confirm Password
    Route::get('password/confirm', 'App\Http\Controllers\Pusat\Auth\ConfirmPasswordController@showConfirmForm')->name('password.confirm');
    Route::post('password/confirm', 'App\Http\Controllers\Pusat\Auth\ConfirmPasswordController@confirm');

    // Verify Email
    // Route::get('email/verify', 'App\Http\Controllers\Pusat\Auth\VerificationController@show')->name('verification.notice');
    // Route::get('email/verify/{id}/{hash}', 'App\Http\Controllers\Pusat\Auth\VerificationController@verify')->name('verification.verify');
    // Route::post('email/resend', 'App\Http\Controllers\Pusat\Auth\VerificationController@resend')->name('verification.resend');

    Route::auto('/proposal', App\Http\Controllers\Pusat\ProposalController::class);
    Route::auto('/lpj', App\Http\Controllers\Pusat\LpjController::class);

    Route::post('/getrevenuelpj','App\Http\Controllers\Pusat\LpjController@getrevenuelpj');
    Route::post('/updaterevenuelpj','App\Http\Controllers\Pusat\LpjController@updaterevenuelpj');

    Route::post('/getunitentrylpj','App\Http\Controllers\Pusat\LpjController@getunitentrylpj');
    Route::post('/updatunitentrylpj','App\Http\Controllers\Pusat\LpjController@updatunitentrylpj');

    Route::post('/updatelpjapproval','App\Http\Controllers\Pusat\LpjController@updatelpjapproval');

    Route::get('/getcostunit','App\Http\Controllers\Pusat\LpjController@getcostunit');
    Route::get('/getrevenuechart','App\Http\Controllers\Pusat\LpjController@getrevenuechart');
    Route::get('/getunitentrychart','App\Http\Controllers\Pusat\LpjController@getunitentrychart');

};
