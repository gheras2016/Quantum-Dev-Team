@extends('layouts.app')

@section('title', __('blog.title'))
@section('meta_description', __('blog.subtitle'))

@section('content')
    @include('partials.page-hero', ['title' => __('blog.title'), 'subtitle' => __('blog.subtitle')])

    <section class="section">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @if ($posts->isEmpty())
                <p class="text-center text-gray-500">{{ __('blog.no_posts') }}</p>
            @else
                <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($posts as $post)
                        @include('partials.post-card', ['post' => $post])
                    @endforeach
                </div>
                <div class="mt-12">{{ $posts->links() }}</div>
            @endif
        </div>
    </section>
@endsection
