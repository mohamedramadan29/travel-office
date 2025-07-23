<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrap();
        foreach (config('permissions') as $config_permission => $value) {
            Gate::define($config_permission, function ($auth) use ($config_permission) {
                return $auth->hasAccess($config_permission);
            });
        }
    }
}
