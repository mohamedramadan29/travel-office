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
    ExpencesController,
    PurchesInvoicesController,
    SafesController,
    SellingInvoicesController,
    DoubleInvoiceController,
    ExpencesCategoriesController,
    IncomeReportController,
    PurchesInvoiceReturnController,
    ReportController,
    SellingInvoicesReturnController,
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
            Route::get('admins/pdf/report',[AdminController::class,'AdminsPdf'])->name('admins.pdf');
            Route::get('admins/excel/report',[AdminController::class,'AdminsExcel'])->name('admins.excel');
        });
        ################### End Admins Routes ###########################
        ##################### Start Suppliers Routes ####################
        Route::group(['middleware' => 'can:suppliers'], function () {
            Route::resource('suppliers', SuppliersController::class);
            Route::get('suppliers/status/{id}', [SuppliersController::class, 'ChangeStatus'])->name('suppliers.status');
            Route::get('suppliers/transactions/{id}', [SuppliersController::class, 'transactions'])->name('suppliers.transactions');
            Route::post('suppliers/add_transaction/{invoice_id}', [SuppliersController::class, 'AddTransaction'])->name('supplier.add_transaction');
            Route::get('suppliers/pdf/report',[SuppliersController::class,'SuppliersPdf'])->name('suppliers.pdf');
            Route::get('suppliers/excel/report',[SuppliersController::class,'SuppliersExcel'])->name('suppliers.excel');
        });
        ##################### End Suppliers Routes ######################

        ##################### Start Clients Routes ####################
        Route::group(['middleware' => 'can:clients'], function () {
            Route::resource('clients', ClientsController::class);
            Route::get('clients/status/{id}', [ClientsController::class, 'ChangeStatus'])->name('clients.status');
            Route::get('clients/transactions/{id}', [ClientsController::class, 'transactions'])->name('clients.transactions');
            Route::post('clients/add_transaction/{invoice_id}', [ClientsController::class, 'AddTransaction'])->name('client.add_transaction');
            Route::get('clients/pdf/report',[ClientsController::class,'ClientsPdf'])->name('clients.pdf');
            Route::get('clients/excel/report',[ClientsController::class,'ClientsExcel'])->name('clients.excel');
        });
        ##################### End Clients Routes ######################

         ##################### Start Safes Routes ####################
         Route::group(['middleware' => 'can:safes'], function () {
            Route::resource('safes', SafesController::class);
            Route::get('safes/status/{id}', [SafesController::class, 'ChangeStatus'])->name('safes.status');
            Route::post('safes/add_balance/{id}', [SafesController::class, 'AddBalance'])->name('safes.add_balance');
            Route::post('safes/remove_balance/{id}', [SafesController::class, 'RemoveBalance'])->name('safes.remove_balance');
            Route::get('safes/movements/{id}',[SafesController::class,'SafeMovement'])->name('safes.movements');
            Route::get('safes/pdf/report',[SafesController::class,'SafesPdf'])->name('safes.pdf');
            Route::get('safes/excel/report',[SafesController::class,'SafesExcel'])->name('safes.excel');
        });
        ##################### End Safes Routes ################################
        ##################### Start Purches Invoices Controller ###############
        Route::group(['middleware' => 'can:purches_invoices'], function () {
            Route::get('purches_invoices_type/{type}',[PurchesInvoicesController::class, 'PurchesInvoice'])->name('purches_invoices_type.type');
            Route::resource('purches_invoices', PurchesInvoicesController::class);
            Route::match(['get','post'],'invoice/convert_to_official_purches/{id}',[PurchesInvoicesController::class, 'ConvertToOfficial'])->name('convert_to_official_purches');
            Route::match(['get','post'],'invoice/return/{id}',[PurchesInvoicesController::class, 'ReturnInvoice'])->name('return_invoice');
            Route::get('purches_invoices/pdf/report',[PurchesInvoicesController::class,'PurchesInvoicesPdf'])->name('purches_invoices.pdf');
            Route::get('purches_invoices/excel/report',[PurchesInvoicesController::class,'PurchesInvoicesExcel'])->name('purches_invoices.excel');
           ######## Official Export  && Interim Export ########
            Route::get('purches_invoices/pdf/report/{type}',[PurchesInvoicesController::class,'PurchesInvoicesPdfType'])->name('purches_invoices.pdf.type');
            Route::get('purches_invoices/excel/report/{type}',[PurchesInvoicesController::class,'PurchesInvoicesExcelType'])->name('purches_invoices.excel.type');

        });
        ##################### End Purches Invoices Controller #################

          ##################### Start Purches Invoices Return Controller ###############
          Route::group(['middleware' => 'can:purches_invoices_return'], function () {
            Route::resource('purches_invoices_return', PurchesInvoiceReturnController::class);
            ############ Purches Return Invoices ###################
            Route::get('purches_invoices_return/pdf/report',[PurchesInvoiceReturnController::class,'PurchesInvoicesReturnPdf'])->name('purches_invoices_return.pdf');
            Route::get('purches_invoices_return/excel/report',[PurchesInvoiceReturnController::class,'PurchesInvoicesReturnExcel'])->name('purches_invoices_return.excel');

        });
        ##################### End Purches Invoices Return  Controller #################

           ##################### Start Selling Invoices Controller ###############
           Route::group(['middleware' => 'can:selling_invoices'], function () {
            Route::get('selling_invoices_type/{type}',[SellingInvoicesController::class, 'SellingInvoice'])->name('selling_invoices_type.type');
            Route::resource('selling_invoices', SellingInvoicesController::class);
            Route::match(['get','post'],'invoice/convert_to_official/{id}',[SellingInvoicesController::class, 'ConvertToOfficial'])->name('convert_to_official');
            Route::match(['get','post'],'selling_invoice/return/{id}',[SellingInvoicesController::class, 'ReturnInvoice'])->name('selling_return_invoice');
            Route::get('selling_invoices/interim/index', [SellingInvoicesController::class, 'InterimInvoices'])->name('selling_invoices.interim');
            Route::get('selling_invoices/pdf/report',[SellingInvoicesController::class,'SellingInvoicesPdf'])->name('selling_invoices.pdf');
            Route::get('selling_invoices/excel/report',[SellingInvoicesController::class,'SellingInvoicesExcel'])->name('selling_invoices.excel');
            //   Route::get('safes/status/{id}', [SafesController::class, 'ChangeStatus'])->name('safes.status');
        });
        ##################### End Selling Invoices Controller #################
          ##################### Start Selling Invoices Return Controller ###############
          Route::group(['middleware' => 'can:selling_invoices_return'], function () {
            Route::resource('selling_invoices_return', SellingInvoicesReturnController::class);
        });
        ##################### End Selling Invoices Return  Controller #################

        ##################### Start Double Invoices Controller ###############
        Route::group(['middleware' => 'can:double_invoices'], function () {
            Route::get('double_invoices/create', [DoubleInvoiceController::class,'create'])->name('double_invoices.create');
            Route::post('double_invoices/store', [DoubleInvoiceController::class,'store'])->name('double_invoices.store');
        });
        #################### End Double Invoices Controller ##################

        ###################### Start Expences Controller ######################
        Route::group(['middleware' => 'can:expenses'], function () {
            Route::resource('expenses', ExpencesController::class);
            Route::get('expenses/pdf/report',[ExpencesController::class,'ExpensesPdf'])->name('expenses.pdf');
            Route::get('expenses/excel/report',[ExpencesController::class,'ExpensesExcel'])->name('expenses.excel');
        });
        ###################### End Expences Controller ##########################

        ###################### Start Expences Categories Controller ######################
        Route::group(['middleware' => 'can:expenses_categories'], function () {
            Route::resource('expenses_categories', ExpencesCategoriesController::class);
            Route::get('expenses_categories/pdf/report',[ExpencesCategoriesController::class,'ExpencesCategoriesPdf'])->name('expenses_categories.pdf');
            Route::get('expenses_categories/excel/report',[ExpencesCategoriesController::class,'ExpencesCategoriesExcel'])->name('expenses_categories.excel');
        });
        ###################### End Expences Categories Controller ##########################


        ################# Start Categories Routes #####################
        Route::group(['middleware' => 'can:categories'], function () {
            Route::resource('categories', CategoryController::class);
            Route::get('categories-all', [CategoryController::class, 'CategoryAll'])->name('categories.all');
            Route::get('categories/pdf/report',[CategoryController::class,'CategoriesPdf'])->name('categories.pdf');
            Route::get('categories/excel/report',[CategoryController::class,'CategoriesExcel'])->name('categories.excel');
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
        ################# Start Report Controller ##################

        Route::group(['middleware' => 'can:reports','prefix'=>'reports','as'=>'reports.'], function () {
            Route::controller(ReportController::class)->group(function(){
                Route::get('expenses-report','ExpensesReport')->name('expenses_report');
                Route::get('PurchesInvoicesReport','PurchesInvoicesReport')->name('PurchesInvoicesReport');
                Route::get('SalesInvoicesReport','SalesInvoicesReport')->name('SalesInvoicesReport');
            });
        });
        ################# End Report Controller ####################

        ################# Start Income Report Controller ##################
        Route::group(['middleware' => 'can:reports','prefix'=>'reports','as'=>'reports.'], function () {
            Route::controller(IncomeReportController::class)->group(function(){
                Route::get('income-report','IncomeReport')->name('income_report');
                Route::get('income-report/{month}','IncomeReportMonthly')->name('income_report.monthly');
            });
        });
        ################# End Income Report Controller ##################

    });
});
