@extends('admin.layouts.app')

@section('title', __('messages.settings'))

@section('content')
    <x-admin.page-header :title="__('messages.settings')" />

    <form method="POST" action="{{ route('admin.settings.update') }}" class="max-w-3xl space-y-6">
        @csrf
        @method('PUT')

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
