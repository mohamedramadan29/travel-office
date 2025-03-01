<?php

namespace App\Providers;

use App\Models\admin\Admin;
use App\Models\admin\Brand;
use App\Models\admin\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

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
        view()->composer('admin.*', function () {
            ####### CategoryCount
            if (!Cache::has("CategoryCount")) {
                Cache::remember('CategoryCount', 60, function () {
                    return Category::count();
                });
            }

            ########## Brand Count
            if (!Cache::has('BrandCount')) {
                Cache::remember('BrandCount', 60, function () {
                    return Brand::count();
                });
            }

            ########## Admin Count
            if (!Cache::has('AdminCount')) {

                Cache::remember('AdminCount', 60, function () {
                    return Admin::count();
                });
            }
            view()->share([
                'CategoryCount' => Cache::get('CategoryCount'),
                'BrandCount' => Cache::get('BrandCount'),
                'AdminCount' => Cache::get('AdminCount'),
            ]);
        });
    }
}
