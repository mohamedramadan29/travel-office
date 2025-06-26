<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\website\HomeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\dashboard\BrandController;
use App\Http\Controllers\website\ProductController;
use App\Http\Controllers\website\BrandsController;
use App\Http\Controllers\website\CategoriesController;
use App\Http\Controllers\website\ProfileController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;



Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'as' => 'website.',
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
], function () {
    Route::controller(HomeController::class)->group(function () {
        Route::get('/home', 'index')->name('home');
        Route::get('/', 'index')->name('home');
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
    ########### Start Brand Controller ##########
    Route::prefix('brands')->name('brands.')->controller(BrandsController::class)->group(function(){
        Route::get('/','index')->name('index');
        Route::get('/{slug}/products','GetProductsByBrand')->name('products');
    });
    ############# End Brand Controller ##########
    ############# Start Categoreis Controller #########
    Route::prefix('categories')->name('categories.')->controller(CategoriesController::class)->group(function(){
        Route::get('/','index')->name('index');
        Route::get('/{slug}/products','GetProductsByCategory')->name('products');
    });

    ############# Start Products ##########

    Route::prefix('products')->name('product.')->controller(ProductController::class)->group(function(){
        Route::get('show/{slug}','showProduct')->name('show');
        Route::get('/{type}','getProductsByType')->name('by.type');
    });

    ############ End Products ###########

});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
