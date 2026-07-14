@extends('layouts.app')

@section('title', __('about.title'))
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags(setting('about_'.app()->getLocale(), __('messages.tagline'))), 150))

@php
    $locale = app()->getLocale();
    $about = setting('about_'.$locale);
    $message = setting('team_message_'.$locale);
@endphp

@section('content')
    @include('partials.page-hero', ['title' => __('about.title'), 'subtitle' => __('about.subtitle')])

    {{-- Intro --}}
    @if ($about)
        <section class="section">
            <div class="mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
                <h2 class="section-title">{{ __('about.intro_title') }}</h2>
                <p class="mt-6 text-lg leading-relaxed text-gray-600 dark:text-gray-300">{{ $about }}</p>
            </div>
        </section>
    @endif

    {{-- Fields of work --}}
    @if ($services->isNotEmpty())
        <section class="section bg-white dark:bg-dark-100">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <h2 class="section-title">{{ __('about.fields_title') }}</h2>
                    <p class="section-subtitle">{{ __('about.fields_subtitle') }}</p>
                </div>
                <div class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($services as $service)
                        <div class="flex items-start gap-4 rounded-2xl bg-gray-50 p-5 ring-1 ring-gray-100 transition-colors hover:ring-primary-200 dark:bg-dark-200 dark:ring-white/5">
                            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-primary-600/10 text-primary-600 dark:text-primary-400">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="{{ $service->icon ?: 'M5 13l4 4L19 7' }}"/>
                                </svg>
                            </span>
                            <div>
                                <h3 class="font-bold">{{ $service->translate('title') }}</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ \Illuminate\Support\Str::limit($service->translate('description'), 90) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Message --}}
    @if ($message)
        <section class="section">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-primary-700 to-primary-900 p-10 text-center text-white">
                    <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
                    <div class="relative">
                        <h2 class="text-2xl font-bold md:text-3xl">{{ __('about.message_title') }}</h2>
                        <p class="mx-auto mt-4 max-w-2xl text-lg leading-relaxed text-primary-50">{{ $message }}</p>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- Team --}}
    @if ($teamMembers->isNotEmpty())
        <section class="section bg-white dark:bg-dark-100">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <h2 class="section-title">{{ __('about.team_title') }}</h2>
                    <p class="section-subtitle">{{ __('about.team_subtitle') }}</p>
                </div>
                <div class="mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($teamMembers as $member)
                        @include('partials.team-card', ['member' => $member])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- CTA --}}
    <section class="section">
        <div class="mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
            <h2 class="section-title">{{ __('about.cta_title') }}</h2>
            <p class="section-subtitle">{{ __('about.cta_subtitle') }}</p>
            <a href="{{ route('request-project') }}" class="btn-primary mt-4">{{ __('about.cta_button') }}</a>
        </div>
    </section>
@endsection
