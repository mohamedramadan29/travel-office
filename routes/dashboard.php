<?php

use Livewire\Livewire;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dashboard\{
    AdminController,
    RolesController,
    SettingController,
    WelcomeController,
    CategoryController,
    UserController,
    SuppliersController,
    ClientsController,
};
use App\Http\Controllers\dashboard\auth\AuthController;
//use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\dashboard\auth\ResetPasswordController;
use App\Http\Controllers\dashboard\auth\ForgetPasswordController;

Route::group([
    'prefix' =>'/dashboard',
    'as' => 'dashboard.',
], function () {

    ##################### Auth Login Controller  ########################
    Route::controller(AuthController::class)->group(function () {
        Route::get('login', 'show_login')->name('login.show');
        Route::post('register_login', 'register_login');
        Route::post('logout', 'logout')->name('logout');
    });
    ############################### End Auth Login Controller ###############
    ################### Reset Password #############
    Route::controller(ForgetPasswordController::class)->group(function () {
        Route::get('password/email', 'showemailform')->name('password.email');
        Route::post('password/email', 'sendotp')->name('password.email.post');
        Route::get('password/verify/{email}', 'showotpform')->name('password.otp.show');
        Route::get('password/verify', 'otpverify')->name('password.otp.post');
    });
    Route::controller(ResetPasswordController::class)->group(function () {
        Route::get('password/reset/{email}', 'ShowResetForm')->name('password.reset');
        Route::post('password/reset', 'resetpassword')->name('password.reset.post');
    });

    ############################### Start Admin Auth Route  ###############
    Route::group(['middleware' => 'auth:admin'], function () {

        ############################### Start Welcome  Controller ###############

        Route::controller(WelcomeController::class)->group(function () {

            Route::get('welcome', 'index')->name('welcome');
        });

        ############################### End  Welcome  Controller ###############
        ##################### Start Role Permissions ####################
        Route::group(['middleware' => 'can:roles', 'prefix' => 'role', 'as' => 'roles.'], function () {
            Route::controller(RolesController::class)->group(function () {
                Route::get('index', 'index')->name('index');
                Route::get('create', 'create')->name('create');
                // Route::post('store', 'store')->name('store')->middleware('can:roles');
                Route::post('store', 'store')->name('store');
                Route::get('edit/{id}', 'edit')->name('edit');
                Route::post('update/{id}', 'update')->name('update');
                Route::get('destroy/{id}', 'destroy')->name('destroy');
            });
        });

        ##################### End Role Permissions #########################

        ##################### Start Admins Routes #########################
        Route::group(['middleware' => 'can:admins'], function () {
            Route::resource('admins', AdminController::class)->except(['show']);
            Route::get('admins/status/{id}', [AdminController::class, 'ChangeStatus'])->name('admins.status');
        });
        ################### End Admins Routes ###########################
        ##################### Start Suppliers Routes ####################
        Route::group(['middleware' => 'can:suppliers'], function () {
            Route::resource('suppliers', SuppliersController::class);
            Route::get('suppliers/status/{id}', [SuppliersController::class, 'ChangeStatus'])->name('suppliers.status');
        });
        ##################### End Suppliers Routes ######################

        ##################### Start Clients Routes ####################
        Route::group(['middleware' => 'can:clients'], function () {
            Route::resource('clients', ClientsController::class);
            Route::get('clients/status/{id}', [ClientsController::class, 'ChangeStatus'])->name('clients.status');
        });
        ##################### End Clients Routes ######################


        ################# Start Categories Routes #####################
        Route::group(['middleware' => 'can:categories'], function () {
            Route::resource('categories', CategoryController::class);
            Route::get('categories-all', [CategoryController::class, 'CategoryAll'])->name('categories.all');
        });
        ################# End Categories Routes #######################

        ################ Start Settings Routes #######################
        Route::group(['middleware' => 'can:settings', 'prefix' => 'settings', 'as' => 'settings.'], function () {
            Route::controller(SettingController::class)->group(function () {
                Route::get('setting', 'index')->name('index');
                Route::put('settings/{id}', 'update')->name('update');
            });
        });
        ################# End Settings Routes #######################

    });
});
