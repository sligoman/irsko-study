<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

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
        RateLimiter::for('leads', function (Request $request): Limit {
            return Limit::perMinute(2)->by($request->ip());
        });

        // Fix for older MySQL / MariaDB default max key length (1071 / 767 bytes)
        // When using utf8mb4, indexed VARCHAR columns must be <= 191 chars
        // See: https://laravel.com/docs/upgrade#strings-and-migrations
        try {
            Schema::defaultStringLength(191);
        } catch (\Throwable $e) {
            // If Schema facade isn't available yet (during some artisan commands), ignore.
        }
    }
}
