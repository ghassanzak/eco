<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    // public const APP_FILE_USERS = 'assets/users/';

    // public const  APP_FILE_PRODUCTS = 'assets/products/';
    // public const APP_FILE_CATEGORY = 'assets/category/';
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
        Schema::defaultStringLength(191);
    }
}
