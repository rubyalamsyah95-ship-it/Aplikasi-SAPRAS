<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Cek apakah lingkungan adalah production (Railway)
        if (config('app.env') === 'production' || env('RAILWAY_ENVIRONMENT')) {
            URL::forceScheme('https');
        }
    }
}