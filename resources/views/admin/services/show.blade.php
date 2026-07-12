@extends('admin.layouts.app')

@section('title', $service->translate('title'))

@section('content')
    <x-admin.page-header :title="$service->translate('title')">
        <a href="{{ route('admin.services.edit', $service) }}" class="btn-primary text-sm">{{ __('messages.edit') }}</a>
    </x-admin.page-header>

    <div class="max-w-3xl space-y-4 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 dark:bg-dark-100 dark:ring-white/5">
        @if ($service->image)
            <img src="{{ $service->image_url }}" class="h-40 rounded-xl object-cover" alt="">
        @endif
        <dl class="grid gap-4 sm:grid-cols-2">
            <div><dt class="text-sm text-gray-500">{{ __('messages.slug') }}</dt><dd>{{ $service->slug }}</dd></div>
            <div><dt class="text-sm text-gray-500">{{ __('messages.status') }}</dt><dd><x-admin.status-badge :status="$service->status" /></dd></div>
            <div class="sm:col-span-2"><dt class="text-sm text-gray-500">{{ __('messages.description') }} ({{ __('messages.arabic') }})</dt><dd>{{ $service->translate('description', 'ar') }}</dd></div>
            <div class="sm:col-span-2"><dt class="text-sm text-gray-500">{{ __('messages.description') }} ({{ __('messages.english') }})</dt><dd>{{ $service->translate('description', 'en') }}</dd></div>
        </dl>
    </div>
@endsection
