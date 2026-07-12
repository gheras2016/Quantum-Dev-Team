<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}"
      class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', __('messages.tagline'))">
    <link rel="canonical" href="{{ url()->current() }}">

    <title>@yield('title', __('messages.site_name')) — {{ __('messages.site_name') }}</title>

    {{-- Open Graph / Twitter --}}
    <meta property="og:site_name" content="{{ __('messages.site_name') }}">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('title', __('messages.site_name'))">
    <meta property="og:description" content="@yield('meta_description', __('messages.tagline'))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('og_image', asset('images/og-default.png'))">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', __('messages.site_name'))">
    <meta name="twitter:description" content="@yield('meta_description', __('messages.tagline'))">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 font-sans text-gray-900 transition-colors duration-300 dark:bg-dark-300 dark:text-gray-100">
    @include('layouts.components.navbar')

    <main>
        @yield('content')
    </main>

    @include('layouts.components.footer')

    @stack('scripts')
</body>
</html>
