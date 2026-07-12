@extends('layouts.app')

@section('title', $project->translate('title'))
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($project->translate('description')), 150))
@section('og_type', 'article')
@if ($project->image)
    @section('og_image', $project->image_url)
@endif

@section('content')
    <section class="border-b border-gray-100 bg-white dark:border-white/5 dark:bg-dark-100">
        <div class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8">
            <a href="{{ route('projects') }}" class="mb-6 inline-flex items-center gap-1 text-sm text-primary-600 hover:underline dark:text-primary-400">
                <span>{{ app()->getLocale() === 'ar' ? '→' : '←' }}</span> {{ __('projects.title') }}
            </a>
            <h1 class="text-3xl font-bold md:text-4xl">{{ $project->translate('title') }}</h1>

            <div class="mt-4 flex flex-wrap gap-4 text-sm text-gray-500 dark:text-gray-400">
                @if ($project->client_name)
                    <span>{{ __('projects.client') }}: <strong class="text-gray-700 dark:text-gray-200">{{ $project->client_name }}</strong></span>
                @endif
                @if ($project->duration)
                    <span>{{ __('projects.duration') }}: <strong class="text-gray-700 dark:text-gray-200">{{ $project->duration }}</strong></span>
                @endif
                <span>{{ __('projects.views') }}: <strong class="text-gray-700 dark:text-gray-200">{{ $project->views_count }}</strong></span>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="mx-auto grid max-w-5xl gap-10 px-4 sm:px-6 lg:grid-cols-3 lg:px-8">
            <div class="lg:col-span-2">
                @if ($project->image)
                    <img src="{{ $project->image_url }}" alt="{{ $project->translate('title') }}" class="mb-8 w-full rounded-2xl object-cover">
                @endif

                <div class="prose max-w-none dark:prose-invert">
                    <p>{{ $project->translate('description') }}</p>
                    @if ($project->case_study)
                        <h2>{{ __('projects.case_study') }}</h2>
                        <p>{{ $project->case_study }}</p>
                    @endif
                </div>

                @if ($project->youtube_video_id)
                    <div class="mt-8 aspect-video overflow-hidden rounded-2xl">
                        <iframe class="h-full w-full" src="https://www.youtube.com/embed/{{ $project->youtube_video_id }}" title="YouTube" allowfullscreen></iframe>
                    </div>
                @endif

                @if ($project->media->isNotEmpty())
                    <div class="mt-8 grid grid-cols-2 gap-4 sm:grid-cols-3">
                        @foreach ($project->media as $img)
                            <a href="{{ $img->url }}" target="_blank" rel="noopener">
                                <img src="{{ $img->url }}" alt="{{ $img->name }}" class="h-32 w-full rounded-xl object-cover transition-transform hover:scale-105">
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <aside class="space-y-6">
                @if ($project->progress)
                    <div class="card">
                        <h3 class="mb-2 font-semibold">{{ __('projects.progress') }}</h3>
                        <div class="h-2.5 w-full overflow-hidden rounded-full bg-gray-200 dark:bg-dark-200">
                            <div class="h-full rounded-full bg-primary-600" style="width: {{ $project->progress }}%"></div>
                        </div>
                        <span class="mt-1 block text-sm text-gray-500">{{ $project->progress }}%</span>
                    </div>
                @endif

                @if ($project->technologies->isNotEmpty())
                    <div class="card">
                        <h3 class="mb-3 font-semibold">{{ __('projects.technologies') }}</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($project->technologies as $tech)
                                <span class="badge bg-primary-50 text-primary-700 dark:bg-primary-900/40 dark:text-primary-300">{{ $tech->name }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($project->demo_url || $project->github_url || $project->documentation_url)
                    <div class="card space-y-2">
                        @if ($project->demo_url)
                            <a href="{{ $project->demo_url }}" target="_blank" rel="noopener" class="btn-primary w-full text-sm">{{ __('projects.view_live') }}</a>
                        @endif
                        @if ($project->github_url)
                            <a href="{{ $project->github_url }}" target="_blank" rel="noopener" class="btn-secondary w-full text-sm">{{ __('projects.view_code') }}</a>
                        @endif
                        @if ($project->documentation_url)
                            <a href="{{ $project->documentation_url }}" target="_blank" rel="noopener" class="btn-secondary w-full text-sm">{{ __('projects.documentation') }}</a>
                        @endif
                    </div>
                @endif
            </aside>
        </div>

        @if ($relatedProjects->isNotEmpty())
            <div class="mx-auto mt-16 max-w-5xl px-4 sm:px-6 lg:px-8">
                <h2 class="section-title mb-8 text-2xl">{{ __('projects.related_projects') }}</h2>
                <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($relatedProjects as $related)
                        @include('partials.project-card', ['project' => $related])
                    @endforeach
                </div>
            </div>
        @endif
    </section>
@endsection
