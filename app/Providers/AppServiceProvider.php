<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator;
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
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        \Carbon\Carbon::setLocale('id');

        Paginator::useBootstrapFive();

        \Spatie\Flash\Flash::levels([
            'success' => 'alert-success',
            'info' => 'alert-info',
            'error' => 'alert-danger',
        ]);
    }
}
