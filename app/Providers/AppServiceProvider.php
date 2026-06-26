<?php

namespace App\Providers;

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
        // Use the published numbered pagination view (First · Prev · 1 2 3 …
        // · Next · Last) for the admin tables instead of Laravel's default,
        // whose desktop number block lives in vendor/ and gets purged by
        // Tailwind — leaving only Previous/Next.
        Paginator::defaultView('pagination::admin');
    }
}
