<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('code') — {{ __('messages.site_name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen items-center justify-center bg-gray-50 px-4 font-sans dark:bg-dark-300">
    <div class="text-center">
        <p class="text-8xl font-extrabold text-gradient">@yield('code')</p>
        <h1 class="mt-4 text-2xl font-bold text-gray-900 dark:text-white">@yield('message')</h1>
        <a href="{{ url('/') }}" class="btn-primary mt-8">{{ __('navigation.home') }}</a>
    </div>
</body>
</html>
