<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // Baris ini sangat penting untuk fungsi HTTPS

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
        /**
         * Memaksa Laravel menggunakan skema HTTPS untuk semua link aset (CSS/JS)
         * dan URL yang dihasilkan oleh asset() atau route() saat berada di Railway (Production).
         */
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}