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

        // Site-wide SEO structured data (schema.org JSON-LD) for the public head.
        View::composer('partials.structured-data', function ($view) {
            $base = url('/');
            $logo = setting('site_logo') ? media_url(setting('site_logo')) : asset('images/og-default.png');

            $organization = array_filter([
                '@type' => 'Organization',
                '@id' => $base.'#organization',
                'name' => __('messages.site_name'),
                'url' => $base,
                'logo' => $logo,
                'email' => setting('site_email') ?: null,
                'telephone' => setting('site_phone') ?: null,
                'sameAs' => SocialLink::active()->ordered()->pluck('url')->filter()->values()->all() ?: null,
            ]);

            $website = [
                '@type' => 'WebSite',
                '@id' => $base.'#website',
                'name' => __('messages.site_name'),
                'url' => $base,
                'inLanguage' => app()->getLocale(),
                'publisher' => ['@id' => $base.'#organization'],
            ];

            $view->with('structuredData', [
                '@context' => 'https://schema.org',
                '@graph' => [$organization, $website],
            ]);
        });
    }
}
