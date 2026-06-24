<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <-- Pastikan baris ini ada

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
        // Paksa semua URL menggunakan HTTPS jika tidak di komputer lokal
        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
        }
    }
}
