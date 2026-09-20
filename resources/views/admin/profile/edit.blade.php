@extends('admin.layouts.app')

@section('title', __('messages.profile'))

@section('content')
    <x-admin.page-header :title="__('messages.profile')" />

    <div class="grid max-w-4xl gap-6 lg:grid-cols-2">
        <form method="POST" action="{{ route('admin.profile.update') }}" class="space-y-5 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 dark:bg-dark-100 dark:ring-white/5">
            @csrf
            @method('PUT')
            <h3 class="font-semibold">{{ __('messages.profile') }}</h3>
            <x-admin.input name="name" :label="__('messages.name')" :value="$user->name" required />
            <x-admin.input name="email" type="email" :label="__('messages.email')" :value="$user->email" required />
            <button type="submit" class="btn-primary text-sm">{{ __('messages.save') }}</button>
        </form>

        <form method="POST" action="{{ route('admin.profile.password') }}" class="space-y-5 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 dark:bg-dark-100 dark:ring-white/5">
            @csrf
            @method('PUT')
            <h3 class="font-semibold">{{ __('messages.change_password') }}</h3>
            <x-admin.input name="current_password" type="password" :label="__('messages.current_password')" required />
            <x-admin.input name="password" type="password" :label="__('messages.new_password')" required />
            <x-admin.input name="password_confirmation" type="password" :label="__('messages.confirm_password')" required />
            <button type="submit" class="btn-primary text-sm">{{ __('messages.update') }}</button>
        </form>
    </div>

    {{-- Two-factor authentication --}}
    <div id="two-factor" class="mt-6 max-w-4xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 dark:bg-dark-100 dark:ring-white/5">
        <h3 class="font-semibold">{{ __('two-factor.title') }}</h3>
        <p class="mt-1 text-sm text-gray-500">{{ __('two-factor.intro') }}</p>

        @if ($twoFactorPending)
            <div class="mt-5 rounded-xl border border-gray-100 p-5 dark:border-white/10">
                <h4 class="font-medium">{{ __('two-factor.setup_title') }}</h4>
                <p class="mt-1 text-sm text-gray-500">{{ __('two-factor.setup_steps') }}</p>

                <div class="mt-4 flex flex-col gap-5 sm:flex-row sm:items-start">
                    <div class="shrink-0 rounded-lg bg-white p-3 ring-1 ring-gray-200 [&_svg]:h-44 [&_svg]:w-44">{!! $twoFactorQr !!}</div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm text-gray-500">{{ __('two-factor.manual_key') }}</p>
                        <code class="mt-1 block break-all rounded-lg bg-gray-100 px-3 py-2 font-mono text-sm dark:bg-dark-200">{{ $twoFactorSecret }}</code>

                        <form method="POST" action="{{ route('admin.two-factor.confirm') }}" class="mt-4 space-y-3">
                            @csrf
                            <x-admin.input name="code" type="text" inputmode="numeric" autocomplete="one-time-code" :label="__('two-factor.confirm_label')" />
                            <button type="submit" class="btn-primary text-sm">{{ __('two-factor.confirm_button') }}</button>
                        </form>
                        <form method="POST" action="{{ route('admin.two-factor.disable') }}" class="mt-3">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm text-gray-500 hover:underline">{{ __('two-factor.cancel_setup') }}</button>
                        </form>
                    </div>
                </div>
            </div>

            @includeWhen(! empty($recoveryCodes), 'admin.profile.partials.recovery-codes', ['recoveryCodes' => $recoveryCodes])
        @elseif ($twoFactorEnabled)
            <div class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-300">
                {{ __('two-factor.status_enabled') }}
            </div>

            @includeWhen(! empty($recoveryCodes), 'admin.profile.partials.recovery-codes', ['recoveryCodes' => $recoveryCodes])

            <form method="POST" action="{{ route('admin.two-factor.recovery-codes') }}" class="mt-5">
                @csrf
                <button type="submit" class="btn-secondary text-sm">{{ __('two-factor.regenerate_button') }}</button>
            </form>

            <form method="POST" action="{{ route('admin.two-factor.disable') }}" class="mt-5 max-w-sm space-y-3">
                @csrf
                @method('DELETE')
                <x-admin.input name="password" type="password" :label="__('two-factor.disable_password_label')" required />
                <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-red-700">
                    {{ __('two-factor.disable_button') }}
                </button>
            </form>
        @else
            <div class="mt-4 rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-600 dark:border-white/10 dark:bg-dark-200 dark:text-gray-300">
                {{ __('two-factor.status_disabled') }}
            </div>
            <form method="POST" action="{{ route('admin.two-factor.enable') }}" class="mt-4">
                @csrf
                <button type="submit" class="btn-primary text-sm">{{ __('two-factor.enable_button') }}</button>
            </form>
        @endif
    </div>
@endsection
