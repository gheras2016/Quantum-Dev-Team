<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.admin_login') }} — {{ __('messages.site_name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen items-center justify-center bg-gray-100 px-4 font-sans dark:bg-dark-300">
    <div class="w-full max-w-md">
        <div class="mb-6 text-center">
            <div class="mb-3 flex justify-center">
                <x-site-logo height="h-24" box="h-24 w-24" rounded="rounded-2xl" text="text-5xl" />
            </div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('messages.admin_login') }}</h1>
            <p class="mt-1 text-sm text-gray-500">{{ __('messages.please_login_to_continue') }}</p>
        </div>

        <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-gray-100 dark:bg-dark-100 dark:ring-white/5">
            @include('partials.alerts')

            <form method="POST" action="{{ route('admin.login') }}" class="space-y-5">
                @csrf
                <x-admin.input name="email" type="email" :label="__('messages.email')" required autofocus />
                <x-admin.input name="password" type="password" :label="__('messages.password')" required />

                <label class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                    {{ __('messages.remember_me') }}
                </label>

                <button type="submit" class="btn-primary w-full">{{ __('messages.login') }}</button>
            </form>
        </div>
    </div>
</body>
</html>
