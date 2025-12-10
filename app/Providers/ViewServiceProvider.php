<?php

namespace App\Providers;

use App\Models\admin\Role;
use App\Models\admin\Safe;
use App\Models\admin\Admin;
use App\Models\admin\Client;
use App\Models\admin\Setting;
use App\Models\admin\Category;
use App\Models\admin\Supplier;
use App\Models\admin\SaleInvoice;
use App\Models\admin\PurcheInvoice;
use Illuminate\Support\Facades\Cache;
use App\Models\admin\SaleInvoiceReturn;
use Illuminate\Support\ServiceProvider;
use App\Models\admin\PurcheInvoiceReturn;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

        ########## Admin Count
        if (!Cache::has('AdminCount')) {
            Cache::remember('AdminCount', 60, function () {
                return Admin::count();
            });
        }
        ########## Permissions Count
        if (!Cache::has('PermissionsCount')) {
            Cache::remember('PermissionsCount', 60, function () {
                return Role::count();
            });
        }
        ######## Categories Count
        if (!Cache::has('CategoriesCount')) {
            Cache::remember('CategoriesCount', 60, function () {
                return Category::count();
            });
        }
        ######## Suppliers Count
        if (!Cache::has('SuppliersCount')) {
            Cache::remember('SuppliersCount', 60, function () {
                return Supplier::count();
            });
        }
        ######## Clients Count
        if (!Cache::has('ClientsCount')) {
            Cache::remember('ClientsCount', 60, function () {
                return Client::count();
            });
        }
        ######## Safes Count
        if (!Cache::has('SafesCount')) {
            Cache::remember('SafesCount', 60, function () {
                return Safe::count();
            });
        }
        ######## Purches Invoices Count
        if (!Cache::has('PurchesInvoicesCount')) {
            Cache::remember('PurchesInvoicesCount', 60, function () {
                return PurcheInvoice::count();
            });
        }
        ######## Selling Invoices Count
        if (!Cache::has('SellingInvoicesCount')) {
            Cache::remember('SellingInvoicesCount', 60, function () {
                return SaleInvoice::count();
            });
        }
        ######## Purches Invoices Return Count
        if (!Cache::has('PurchesInvoicesReturnCount')) {
            Cache::remember('PurchesInvoicesReturnCount', 60, function () {
                return PurcheInvoiceReturn::count();
            });
        }
        ######## Selling Invoices Return Count
        if (!Cache::has('SellingInvoicesReturnCount')) {
            Cache::remember('SellingInvoicesReturnCount', 60, function () {
                return SaleInvoiceReturn::count();
            });
        }
        ########  Selling Invoices Interim
        if (!Cache::has('PurchesInvoicesInterim')) {
            Cache::remember('PurchesInvoicesInterim', 60, function () {
                return PurcheInvoice::where('status', 'sold')->where('type', 'فاتورة مؤقتة')->count();
            });
        }

        view()->share([
            'AdminCount' => Cache::get('AdminCount'),
            'PermissionsCount' => Cache::get('PermissionsCount'),
            'CategoriesCount' => Cache::get('CategoriesCount'),
            'SuppliersCount' => Cache::get('SuppliersCount'),
            'ClientsCount' => Cache::get('ClientsCount'),
            'SafesCount' => Cache::get('SafesCount'),
            'PurchesInvoicesCount' => Cache::get('PurchesInvoicesCount'),
            'SellingInvoicesCount' => Cache::get('SellingInvoicesCount'),
            'PurchesInvoicesReturnCount' => Cache::get('PurchesInvoicesReturnCount'),
            'SellingInvoicesReturnCount' => Cache::get('SellingInvoicesReturnCount'),
            'PurchesInvoicesInterim' => Cache::get('PurchesInvoicesInterim'),
        ]);


        ///// Get Settings And Share

        /**
         * Get the settings or create a new one if it doesn't exist.
         *
         * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection
         */
        view()->share([
            'setting' => Setting::first(),
        ]);
    }
}
