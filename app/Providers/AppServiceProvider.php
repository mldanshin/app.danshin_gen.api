<?php

namespace App\Providers;

use App\Auth\CompositeGuard;
use App\Auth\AccessTokenGuard;
use Illuminate\Support\Facades\Auth;
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

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Auth::extend('access-token', function ($app, $name, array $config) {
            return new AccessTokenGuard($app['request']);
        });

        Auth::extend('composite', function ($app, $name, array $config) {
            return new CompositeGuard($app['request']);
        });
    }
}
