<?php

namespace App\Providers;

use App\Models\SocialLink;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useTailwind();

        // Share the active social links with the public footer without
        // querying the database directly from Blade (as the legacy app did).
        View::composer('layouts.components.footer', function ($view) {
            $view->with('socialLinks', SocialLink::active()->ordered()->get());
        });
    }
}
