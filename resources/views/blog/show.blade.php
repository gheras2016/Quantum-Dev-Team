@extends('layouts.app')

@section('title', $post->translate('title'))
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($post->translate('excerpt') ?: $post->translate('body')), 150))
@section('og_type', 'article')
@if ($post->image)
    @section('og_image', $post->image_url)
@endif

@section('content')
    <article class="section">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <a href="{{ route('blog') }}" class="mb-6 inline-flex items-center gap-1 text-sm text-primary-600 hover:underline dark:text-primary-400">
                <span>{{ app()->getLocale() === 'ar' ? '→' : '←' }}</span> {{ __('blog.title') }}
            </a>

            <h1 class="text-3xl font-bold md:text-4xl">{{ $post->translate('title') }}</h1>
            <div class="mt-3 flex items-center gap-3 text-sm text-gray-400">
                <span>{{ optional($post->published_at)->translatedFormat('d M Y') }}</span>
                <span>•</span>
                <span>{{ $post->reading_time }} {{ __('blog.reading_time') }}</span>
            </div>

            @if ($post->image)
                <img src="{{ $post->image_url }}" alt="{{ $post->translate('title') }}" class="mt-8 w-full rounded-2xl object-cover">
            @else
                <x-cover :title="$post->translate('title')" :seed="$post->slug" class="mt-8 h-56 rounded-2xl" />
            @endif

            <div class="prose mt-8 max-w-none dark:prose-invert">
                {!! nl2br(e($post->translate('body'))) !!}
            </div>

            @if ($post->tags->isNotEmpty())
                <div class="mt-8 flex flex-wrap gap-2">
                    @foreach ($post->tags as $tag)
                        <span class="badge bg-primary-50 text-primary-700 dark:bg-primary-900/40 dark:text-primary-300">#{{ $tag->name }}</span>
                    @endforeach
                </div>
            @endif
        </div>

        @if ($related->isNotEmpty())
            <div class="mx-auto mt-16 max-w-7xl px-4 sm:px-6 lg:px-8">
                <h2 class="section-title mb-8 text-2xl">{{ __('blog.related') }}</h2>
                <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($related as $item)
                        @include('partials.post-card', ['post' => $item])
                    @endforeach
                </div>
            </div>
        @endif
    </article>
@endsection
