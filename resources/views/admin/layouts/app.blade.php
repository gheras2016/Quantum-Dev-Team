<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('messages.admin_panel')) — {{ __('messages.site_name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans text-gray-900 dark:bg-dark-300 dark:text-gray-100">
    <div x-data="{ sidebar: false }" class="flex min-h-screen">
        @include('admin.layouts.sidebar')

        {{-- Mobile overlay --}}
        <div x-show="sidebar" x-cloak @click="sidebar = false" class="fixed inset-0 z-30 bg-black/40 lg:hidden"></div>

        <div class="flex min-w-0 flex-1 flex-col">
            @include('admin.layouts.topnav')

            <main class="flex-1 p-4 sm:p-6">
                @include('partials.alerts')
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
