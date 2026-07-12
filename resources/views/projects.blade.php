@extends('layouts.app')

@section('title', __('projects.title'))

@section('content')
    @include('partials.page-hero', ['title' => __('projects.title'), 'subtitle' => __('projects.subtitle')])

    <section class="section">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            {{-- Category filter --}}
            @if ($categories->isNotEmpty())
                <div class="mb-10 flex flex-wrap justify-center gap-2">
                    <a href="{{ route('projects') }}"
                       class="badge px-4 py-2 text-sm {{ ! request('category') ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-dark-100 dark:text-gray-300' }}">
                        {{ __('projects.filter_all') }}
                    </a>
                    @foreach ($categories as $category)
                        <a href="{{ route('projects', ['category' => $category->slug]) }}"
                           class="badge px-4 py-2 text-sm {{ request('category') === $category->slug ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-dark-100 dark:text-gray-300' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            @endif

            @if ($projects->isEmpty())
                <p class="text-center text-gray-500">{{ __('projects.no_projects') }}</p>
            @else
                <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($projects as $project)
                        @include('partials.project-card', ['project' => $project])
                    @endforeach
                </div>

                <div class="mt-12">{{ $projects->links() }}</div>
            @endif
        </div>
    </section>
@endsection
