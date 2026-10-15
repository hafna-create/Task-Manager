<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
\Illuminate\Pagination\Paginator::useBootstrapFive();

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
        //
    }
}
