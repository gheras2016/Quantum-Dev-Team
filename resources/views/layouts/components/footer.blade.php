@php
    $navKeys = ['home', 'services', 'projects', 'team', 'contact'];
    $icons = [
        'linkedin' => 'M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z',
        'github' => 'M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z',
    ];
    $defaultIcon = 'M12 2C6.477 2 2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.879V14.89h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.989C18.343 21.129 22 16.99 22 12c0-5.523-4.477-10-10-10z';
@endphp

<footer class="bg-dark-200 text-gray-300">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-14 sm:px-6 md:grid-cols-3 lg:px-8">
        <div>
            <div class="mb-4 flex items-center gap-2">
                <x-site-logo height="h-14" box="h-14 w-14" text="text-2xl" />
                <span class="text-lg font-bold text-white">{{ __('messages.site_name') }}</span>
            </div>
            <p class="mb-5 max-w-xs text-sm text-gray-400">{{ __('messages.tagline') }}</p>
            <div class="flex gap-3">
                @foreach ($socialLinks ?? [] as $link)
                    <a href="{{ $link->url }}" target="_blank" rel="noopener"
                       class="flex h-9 w-9 items-center justify-center rounded-lg bg-white/5 transition-colors hover:bg-primary-600"
                       aria-label="{{ $link->platform }}">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="{{ $icons[$link->platform] ?? $defaultIcon }}"/></svg>
                    </a>
                @endforeach
            </div>
        </div>

        <div>
            <h3 class="mb-4 font-semibold text-white">{{ __('navigation.home') }}</h3>
            <ul class="space-y-2 text-sm">
                @foreach ($navKeys as $key)
                    <li><a href="{{ route($key === 'home' ? 'home' : $key) }}" class="transition-colors hover:text-primary-400">{{ __('navigation.'.$key) }}</a></li>
                @endforeach
            </ul>
        </div>

        <div>
            <h3 class="mb-4 font-semibold text-white">{{ __('contact.contact_info') }}</h3>
            <ul class="space-y-3 text-sm text-gray-400">
                <li><a href="mailto:{{ setting('site_email', 'info@quantumdevteam.com') }}" class="hover:text-primary-400">{{ setting('site_email', 'info@quantumdevteam.com') }}</a></li>
                <li dir="ltr" class="{{ app()->getLocale() === 'ar' ? 'text-right' : '' }}">{{ setting('site_phone', '+966 50 000 0000') }}</li>
                <li>{{ __('contact.address') }}: {{ setting('site_address_'.app()->getLocale(), 'Saudi Arabia') }}</li>
            </ul>
        </div>
    </div>

    <div class="border-t border-white/10 py-6 text-center text-sm text-gray-500">
        &copy; {{ date('Y') }} {{ __('messages.site_name') }}.
    </div>
</footer>
