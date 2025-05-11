<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\website\HomeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\website\ProfileController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;



Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'as' => 'website.',
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
], function () {
    Route::controller(HomeController::class)->group(function () {
        Route::get('/home', 'index')->name('website.home');
    });
    ############## Start Register Controller #############

    Route::controller(RegisterController::class)->group(function () {
        Route::get('register', 'showRegistrationForm')->name('register.show');
        Route::post('register', 'register')->name('register.post');
    });
    ############## End Register Controller #############
    ############## Start Login Controller #############
    Route::controller(LoginController::class)->group(function () {
        Route::get('login', 'showLoginForm')->name('login.show');
        Route::post('login', 'login')->name('login.post');
        Route::post('logout','logout')->name('logout.post');
    });
    ############## End Login Controller #############
    ############## Start Profile Controller #############
    Route::group(['middleware' => 'auth:web'], function () {
        Route::controller(ProfileController::class)->group(function () {
            Route::get('profile', 'index')->name('profile');
        });
    });
    ############## End Profile Controller #############
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
