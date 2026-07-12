@extends('layouts.app')

@section('title', __('home.hero_title'))

@section('content')
    {{-- Hero --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-primary-700 via-primary-800 to-primary-950 text-white">
        <div class="absolute inset-0 bg-grid-pattern opacity-20"></div>
        <div class="absolute -end-24 -top-24 h-72 w-72 rounded-full bg-primary-500/30 blur-3xl animate-float"></div>
        <div class="absolute -bottom-24 -start-24 h-72 w-72 rounded-full bg-primary-400/20 blur-3xl animate-float" style="animation-delay:2s"></div>

        <div class="relative mx-auto max-w-7xl px-4 py-24 text-center sm:px-6 md:py-32 lg:px-8">
            <div class="mx-auto max-w-3xl animate-fade-in-up">
                <h1 class="text-4xl font-bold leading-tight md:text-6xl">{{ setting('hero_title_'.app()->getLocale(), __('home.hero_title')) }}</h1>
                <p class="mx-auto mt-6 max-w-2xl text-lg text-primary-100 md:text-xl">{{ setting('hero_subtitle_'.app()->getLocale(), __('home.hero_subtitle')) }}</p>
                <div class="mt-10 flex flex-col justify-center gap-4 sm:flex-row">
                    <a href="{{ route('request-project') }}" class="btn-white">{{ __('home.hero_cta') }}</a>
                    <a href="{{ route('projects') }}" class="btn border-2 border-white/70 text-white hover:bg-white hover:text-primary-700">{{ __('home.hero_cta_secondary') }}</a>
                </div>
            </div>

            <div class="mx-auto mt-10 grid max-w-3xl grid-cols-3 gap-4 border-t border-white/20 pt-8 sm:mt-16 sm:gap-8 sm:pt-10">
                @foreach (['projects' => $stats['projects'], 'clients' => $stats['clients'], 'years' => $stats['years']] as $key => $value)
                    <div>
                        <div class="text-2xl font-bold sm:text-3xl md:text-4xl">{{ $value }}+</div>
                        <div class="mt-1 text-xs text-primary-100 sm:text-sm">{{ __('home.stats_'.$key) }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Services --}}
    @if ($services->isNotEmpty())
        <section class="section">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <h2 class="section-title">{{ __('home.services_title') }}</h2>
                    <p class="section-subtitle">{{ __('home.services_subtitle') }}</p>
                </div>
                <div class="mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($services as $service)
                        <div class="card group">
                            <div class="mb-4 text-primary-600 transition-transform duration-300 group-hover:scale-110 dark:text-primary-400">
                                <svg class="h-11 w-11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="{{ $service->icon ?: 'M13 10V3L4 14h7v7l9-11h-7z' }}"/></svg>
                            </div>
                            <h3 class="text-xl font-bold">{{ $service->translate('title') }}</h3>
                            <p class="mt-2 text-gray-600 dark:text-gray-300">{{ \Illuminate\Support\Str::limit($service->translate('description'), 120) }}</p>
                        </div>
                    @endforeach
                </div>
                <div class="mt-10 text-center">
                    <a href="{{ route('services') }}" class="btn-secondary">{{ __('messages.view_all') }}</a>
                </div>
            </div>
        </section>
    @endif

    {{-- Why choose us --}}
    <section class="section bg-white dark:bg-dark-100">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="section-title">{{ __('home.why_choose_us_title') }}</h2>
                <p class="section-subtitle">{{ __('home.why_choose_us_subtitle') }}</p>
            </div>
            <div class="mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @php
                    $features = [
                        ['speed', 'from-primary-600 to-primary-400', 'M13 10V3L4 14h7v7l9-11h-7z'],
                        ['quality', 'from-emerald-500 to-emerald-400', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['flexibility', 'from-purple-500 to-purple-400', 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'],
                        ['experience', 'from-orange-500 to-orange-400', 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707'],
                        ['support', 'from-pink-500 to-pink-400', 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857'],
                        ['communication', 'from-cyan-500 to-cyan-400', 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ];
                @endphp
                @foreach ($features as [$key, $gradient, $path])
                    <div class="card text-center">
                        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-gradient-to-br {{ $gradient }} text-white">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $path }}"/></svg>
                        </div>
                        <h3 class="text-lg font-bold">{{ __('home.'.$key.'_title') }}</h3>
                        <p class="mt-2 text-gray-600 dark:text-gray-300">{{ __('home.'.$key.'_desc') }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Featured projects --}}
    @if ($featuredProjects->isNotEmpty())
        <section class="section">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <h2 class="section-title">{{ __('home.projects_title') }}</h2>
                    <p class="section-subtitle">{{ __('home.projects_subtitle') }}</p>
                </div>
                <div class="mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($featuredProjects as $project)
                        @include('partials.project-card', ['project' => $project])
                    @endforeach
                </div>
                <div class="mt-10 text-center">
                    <a href="{{ route('projects') }}" class="btn-secondary">{{ __('messages.view_all') }}</a>
                </div>
            </div>
        </section>
    @endif

    {{-- Team --}}
    @if ($teamMembers->isNotEmpty())
        <section class="section bg-white dark:bg-dark-100">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <h2 class="section-title">{{ __('home.team_title') }}</h2>
                    <p class="section-subtitle">{{ __('home.team_subtitle') }}</p>
                </div>
                <div class="mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($teamMembers as $member)
                        @include('partials.team-card', ['member' => $member])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Process --}}
    <section class="section">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="section-title">{{ __('home.process_title') }}</h2>
                <p class="section-subtitle">{{ __('home.process_subtitle') }}</p>
            </div>
            <div class="mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([1, 2, 3, 4] as $step)
                    <div class="card text-center">
                        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-gradient-to-br {{ $step === 4 ? 'from-emerald-500 to-emerald-400' : 'from-primary-600 to-primary-400' }} text-xl font-bold text-white">
                            {{ $step === 4 ? '✓' : $step }}
                        </div>
                        <h3 class="text-lg font-bold">{{ __('home.step'.$step.'_title') }}</h3>
                        <p class="mt-2 text-gray-600 dark:text-gray-300">{{ __('home.step'.$step.'_desc') }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Testimonials --}}
    @if ($testimonials->isNotEmpty())
        <section class="section bg-white dark:bg-dark-100">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <h2 class="section-title">{{ __('blog.testimonials_title') }}</h2>
                    <p class="section-subtitle">{{ __('blog.testimonials_subtitle') }}</p>
                </div>
                <div class="mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($testimonials as $t)
                        <figure class="card flex flex-col">
                            <div class="mb-3 text-amber-400">{{ str_repeat('★', $t->rating) }}<span class="text-gray-300 dark:text-gray-600">{{ str_repeat('★', 5 - $t->rating) }}</span></div>
                            <blockquote class="flex-1 text-gray-600 dark:text-gray-300">“{{ $t->translate('content') }}”</blockquote>
                            <figcaption class="mt-5 flex items-center gap-3">
                                @if ($t->avatar)
                                    <img src="{{ $t->avatar_url }}" alt="{{ $t->author_name }}" class="h-11 w-11 rounded-full object-cover">
                                @else
                                    <span class="flex h-11 w-11 items-center justify-center rounded-full bg-primary-100 font-semibold text-primary-600 dark:bg-primary-500/15">{{ mb_substr($t->author_name, 0, 1) }}</span>
                                @endif
                                <div>
                                    <p class="font-semibold">{{ $t->author_name }}</p>
                                    <p class="text-sm text-gray-400">{{ collect([$t->author_title, $t->author_company])->filter()->join(' — ') }}</p>
                                </div>
                            </figcaption>
                        </figure>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- FAQ --}}
    @if ($faqs->isNotEmpty())
        <section class="section">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <h2 class="section-title">{{ __('blog.faq_title') }}</h2>
                    <p class="section-subtitle">{{ __('blog.faq_subtitle') }}</p>
                </div>
                <div class="mt-10 space-y-3">
                    @foreach ($faqs as $faq)
                        <div x-data="{ open: false }" class="overflow-hidden rounded-xl bg-white ring-1 ring-gray-100 dark:bg-dark-100 dark:ring-white/5">
                            <button type="button" @click="open = !open" class="flex w-full items-center justify-between gap-4 px-5 py-4 text-start font-medium">
                                <span>{{ $faq->translate('question') }}</span>
                                <svg class="h-5 w-5 shrink-0 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" x-transition x-cloak class="px-5 pb-4 text-gray-600 dark:text-gray-300">{{ $faq->translate('answer') }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- CTA --}}
    <section class="relative overflow-hidden bg-gradient-to-r from-primary-700 to-primary-900 text-white">
        <div class="absolute inset-0 bg-grid-pattern opacity-20"></div>
        <div class="relative mx-auto max-w-3xl px-4 py-20 text-center sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold md:text-4xl">{{ __('home.cta_title') }}</h2>
            <p class="mt-4 text-lg text-primary-100">{{ __('home.cta_subtitle') }}</p>
            <a href="{{ route('request-project') }}" class="btn-white mt-8">{{ __('home.cta_button') }}</a>
        </div>
    </section>
@endsection
