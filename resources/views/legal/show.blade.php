@extends('layouts.app')

@section('title', $page['title'])
@section('meta_description', \Illuminate\Support\Str::limit(str_replace(':site', $siteName, $page['intro']), 150))

@section('content')
    <section class="section">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold md:text-4xl">{{ $page['title'] }}</h1>
            <p class="mt-2 text-sm text-gray-400">{{ __('legal.updated') }}: {{ $updatedAt }}</p>

            <p class="mt-6 leading-relaxed text-gray-600 dark:text-gray-300">{{ str_replace(':site', $siteName, $page['intro']) }}</p>

            <div class="mt-8 space-y-8">
                @foreach ($page['sections'] as $section)
                    <div>
                        <h2 class="text-xl font-bold">{{ $section['heading'] }}</h2>
                        <p class="mt-2 leading-relaxed text-gray-600 dark:text-gray-300">{{ $section['body'] }}</p>
                    </div>
                @endforeach

                <div>
                    <h2 class="text-xl font-bold">{{ __('legal.contact_heading') }}</h2>
                    <p class="mt-2 leading-relaxed text-gray-600 dark:text-gray-300">{{ __('legal.contact_body', ['email' => $email]) }}</p>
                </div>
            </div>

            <p class="mt-10 rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 text-xs text-gray-500 dark:border-white/10 dark:bg-dark-100">
                {{ __('legal.disclaimer') }}
            </p>
        </div>
    </section>
@endsection
