@extends('layouts.app')

@section('title', __('services.title'))

@section('content')
    @include('partials.page-hero', ['title' => __('services.title'), 'subtitle' => __('services.subtitle')])

    <section class="section">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @if ($services->isEmpty())
                <p class="text-center text-gray-500">{{ __('services.no_services') }}</p>
            @else
                <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($services as $service)
                        <div class="card group">
                            <div class="mb-4 text-primary-600 transition-transform duration-300 group-hover:scale-110 dark:text-primary-400">
                                <svg class="h-11 w-11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="{{ $service->icon ?: 'M13 10V3L4 14h7v7l9-11h-7z' }}"/></svg>
                            </div>
                            <h3 class="text-xl font-bold">{{ $service->translate('title') }}</h3>
                            <p class="mt-2 text-gray-600 dark:text-gray-300">{{ $service->translate('description') }}</p>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="mt-16 rounded-2xl bg-primary-50 p-10 text-center dark:bg-dark-100">
                <h2 class="text-2xl font-bold">{{ __('services.cta_title') }}</h2>
                <p class="mx-auto mt-2 max-w-xl text-gray-600 dark:text-gray-300">{{ __('services.cta_subtitle') }}</p>
                <a href="{{ route('request-project') }}" class="btn-primary mt-6">{{ __('navigation.request_project') }}</a>
            </div>
        </div>
    </section>
@endsection
