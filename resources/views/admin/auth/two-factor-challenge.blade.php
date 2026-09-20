<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('two-factor.challenge_title') }} — {{ __('messages.site_name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen items-center justify-center bg-gray-100 px-4 font-sans dark:bg-dark-300">
    <div class="w-full max-w-md">
        <div class="mb-6 text-center">
            <div class="mb-3 flex justify-center">
                <x-site-logo height="h-20" box="h-20 w-20" rounded="rounded-2xl" text="text-4xl" />
            </div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('two-factor.challenge_title') }}</h1>
            <p class="mt-1 text-sm text-gray-500" x-data x-show="true">{{ __('two-factor.challenge_subtitle') }}</p>
        </div>

        <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-gray-100 dark:bg-dark-100 dark:ring-white/5"
             x-data="{ recovery: false }">
            @include('partials.alerts')

            {{-- TOTP code --}}
            <form method="POST" action="{{ route('admin.two-factor.verify') }}" class="space-y-5" x-show="!recovery">
                @csrf
                <x-admin.input name="code" type="text" inputmode="numeric" autocomplete="one-time-code"
                               :label="__('two-factor.code_label')" autofocus />
                <button type="submit" class="btn-primary w-full">{{ __('two-factor.verify_button') }}</button>
            </form>

            {{-- Recovery code --}}
            <form method="POST" action="{{ route('admin.two-factor.verify') }}" class="space-y-5" x-show="recovery" x-cloak>
                @csrf
                <x-admin.input name="recovery_code" type="text" :label="__('two-factor.recovery_label')" />
                <button type="submit" class="btn-primary w-full">{{ __('two-factor.verify_button') }}</button>
            </form>

            <button type="button" @click="recovery = !recovery"
                    class="mt-4 w-full text-center text-sm text-primary-600 hover:underline dark:text-primary-400">
                <span x-show="!recovery">{{ __('two-factor.use_recovery') }}</span>
                <span x-show="recovery" x-cloak>{{ __('two-factor.use_code') }}</span>
            </button>

            <a href="{{ route('admin.login') }}" class="mt-2 block text-center text-xs text-gray-400 hover:underline">
                {{ __('two-factor.back_to_login') }}
            </a>
        </div>
    </div>
</body>
</html>
