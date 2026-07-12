@php
    $links = [
        'home' => route('home'),
        'services' => route('services'),
        'projects' => route('projects'),
        'blog' => route('blog'),
        'team' => route('team'),
        'contact' => route('contact'),
    ];
    $routeNames = ['home' => 'home', 'services' => 'services', 'projects' => 'projects*', 'blog' => 'blog*', 'team' => 'team', 'contact' => 'contact'];
@endphp

<nav x-data="{ open: false }" class="sticky top-0 z-50 border-b border-gray-100 bg-white/90 backdrop-blur dark:border-white/5 dark:bg-dark-200/90">
    <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
        <a href="{{ route('home') }}" class="flex items-center gap-2">
            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-primary-600 to-primary-400 text-lg font-bold text-white">Q</span>
            <span class="text-lg font-bold">{{ __('messages.site_name') }}</span>
        </a>

        <div class="hidden items-center gap-8 md:flex">
            @foreach ($links as $key => $url)
                <a href="{{ $url }}" class="nav-link {{ request()->routeIs($routeNames[$key]) ? 'nav-link-active' : '' }}">
                    {{ __('navigation.'.$key) }}
                </a>
            @endforeach
            <a href="{{ route('request-project') }}" class="btn-primary text-sm">{{ __('navigation.request_project') }}</a>
        </div>

        <div class="flex items-center gap-2">
            @include('layouts.components.language-switcher')

            <button type="button" @click="$store.theme.toggle()"
                    class="rounded-lg p-2 text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/5"
                    aria-label="Toggle theme">
                <svg x-show="!$store.theme.dark" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                <svg x-show="$store.theme.dark" x-cloak class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </button>

            <button type="button" @click="open = !open" class="rounded-lg p-2 text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/5 md:hidden" aria-label="Menu">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
    </div>

    <div x-show="open" x-transition x-cloak class="border-t border-gray-100 px-4 py-3 dark:border-white/5 md:hidden">
        <div class="flex flex-col gap-1">
            @foreach ($links as $key => $url)
                <a href="{{ $url }}" class="rounded-lg px-3 py-2 nav-link {{ request()->routeIs($routeNames[$key]) ? 'nav-link-active' : '' }}">{{ __('navigation.'.$key) }}</a>
            @endforeach
            <a href="{{ route('request-project') }}" class="btn-primary mt-2 text-sm">{{ __('navigation.request_project') }}</a>
        </div>
    </div>
</nav>
