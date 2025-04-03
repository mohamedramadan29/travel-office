<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\dashboard\FaqController;
use App\Http\Controllers\dashboard\AdminController;
use App\Http\Controllers\dashboard\BrandController;
use App\Http\Controllers\dashboard\RolesController;
use App\Http\Controllers\dashboard\WorldController;
use App\Http\Controllers\dashboard\CouponController;
use App\Http\Controllers\dashboard\WelcomeController;
use App\Http\Controllers\dashboard\CategoryController;
use App\Http\Controllers\dashboard\auth\AuthController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\dashboard\auth\ResetPasswordController;
use App\Http\Controllers\dashboard\auth\ForgetPasswordController;
use App\Http\Controllers\dashboard\SettingController;

Route::group([
    'prefix' => LaravelLocalization::setLocale() . '/dashboard',
    'as' => 'dashboard.',
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
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
            Route::resource('admins', AdminController::class);
            Route::get('admins/status/{id}', [AdminController::class, 'ChangeStatus'])->name('admins.status');
        });
        ################### End Admins Routes ###########################

        ################ Start World Routes #######################
        Route::group(['middleware' => 'can:global_shipping', 'prefix' => 'world', 'as' => 'world.'], function () {
            Route::controller(WorldController::class)->group(function () {
                Route::get('countries', 'AllCountry')->name('countries');
                Route::get('countries/status/{id}', 'UpdateStatus')->name('update_status');
                Route::get('governorates/{country_id}', 'GovernorateByCountry')->name('governorates');
                Route::get('governorates/status/{id}', 'UpdateGovernrateStatus')->name('update_status_governorates');
                Route::post('governorate/change_price/{id}', 'GovernrateChangePrice')->name('GovernrateChangePrice');
            });
        });
        ################# End World Routes #######################
        ################# Start Categories Routes #####################
        Route::group(['middleware' => 'can:categories'], function () {
            Route::resource('categories', CategoryController::class);
            Route::get('categories-all', [CategoryController::class, 'CategoryAll'])->name('categories.all');
        });
        ################# End Categories Routes #######################

        ################# Start Brands Routes #####################
        Route::group(['middleware' => 'can:brands'], function () {
            Route::resource('brands', BrandController::class);
            Route::get('brands-all', [BrandController::class, 'BrandsAll'])->name('brands.all');
        });
        ################# End Brands Routes #######################

        ################# Start Coupons  Routes #####################
        Route::group(['middleware' => 'can:coupons'], function () {
            Route::resource('coupons', CouponController::class);
            Route::get('coupons-all', [CouponController::class, 'CouponsAll'])->name('coupons.all');
        });
        ################# End Coupons Routes #######################

        ################ Start Faqs Routes #######################
        Route::group(['middleware' => 'can:faqs'], function () {
            Route::resource('faqs', FaqController::class);
            Route::get('faqs-all', [FaqController::class, 'FaqsAll'])->name('faqs.all');
        });
        ################# End Faqs Routes #######################

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
