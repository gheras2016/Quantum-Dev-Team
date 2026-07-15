@extends('admin.layouts.app')

@section('title', __('messages.settings'))

@section('content')
    <x-admin.page-header :title="__('messages.settings')" />

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="max-w-3xl space-y-6">
        @csrf
        @method('PUT')

        {{-- Branding / logo --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 dark:bg-dark-100 dark:ring-white/5">
            <h3 class="mb-4 font-semibold">{{ __('settings.branding') }}</h3>
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start">
                <div class="shrink-0">
                    @if (! empty($values['site_logo']))
                        <img src="{{ media_url($values['site_logo']) }}" alt="logo"
                             class="h-20 w-auto max-w-[220px] rounded-xl object-contain ring-1 ring-gray-200 dark:ring-white/10">
                    @else
                        <span class="flex h-20 w-20 items-center justify-center rounded-xl bg-gradient-to-br from-primary-600 to-primary-400 text-3xl font-bold text-white">Q</span>
                    @endif
                </div>
                <div class="flex-1">
                    <label for="site_logo" class="form-label">{{ __('settings.site_logo') }}</label>
                    <input id="site_logo" name="site_logo" type="file" accept=".png,.jpg,.jpeg,.webp,.svg,.gif" class="form-input">
                    <p class="mt-1 text-xs text-gray-400">{{ __('settings.logo_hint') }}</p>
                    @error('site_logo')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    @if (! empty($values['site_logo']))
                        <label class="mt-3 flex items-center gap-2 text-sm">
                            <input type="checkbox" name="remove_logo" value="1" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            {{ __('settings.remove_logo') }}
                        </label>
                    @endif
                </div>
            </div>
        </div>

        @foreach ($schema as $group => $fields)
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 dark:bg-dark-100 dark:ring-white/5">
                <h3 class="mb-4 font-semibold capitalize">{{ __('settings.'.$group) }}</h3>
                <div class="grid gap-5 sm:grid-cols-2">
                    @foreach ($fields as $key => $type)
                        <div class="{{ $type === 'textarea' ? 'sm:col-span-2' : '' }}">
                            <label for="{{ $key }}" class="form-label">{{ __('settings.'.$key) }}</label>
                            @if ($type === 'textarea')
                                <textarea id="{{ $key }}" name="{{ $key }}" rows="2" class="form-textarea">{{ old($key, $values[$key] ?? '') }}</textarea>
                            @else
                                <input id="{{ $key }}" name="{{ $key }}" type="{{ $type }}" value="{{ old($key, $values[$key] ?? '') }}" class="form-input">
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <button type="submit" class="btn-primary text-sm">{{ __('messages.save') }}</button>
    </form>
@endsection
