<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <-- WAJIB IMPORT INI DI ATAS

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
        // Memaksa HTTPS jika berada di production atau variabel FORCE_HTTPS bernilai true
        if (config('app.env') === 'production' || env('FORCE_HTTPS') === true) {
            URL::forceScheme('https');
        }
    }
}
