<a href="{{ route('blog.show', $post) }}" class="card group block overflow-hidden !p-0">
    <div class="relative h-44 overflow-hidden">
        @if ($post->image)
            <img src="{{ $post->image_url }}" alt="{{ $post->translate('title') }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
        @else
            <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-primary-600 to-primary-400 text-white">
                <svg class="h-12 w-12 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            </div>
        @endif
    </div>
    <div class="p-6">
        <div class="mb-2 flex items-center gap-2 text-xs text-gray-400">
            <span>{{ optional($post->published_at)->translatedFormat('d M Y') }}</span>
            <span>•</span>
            <span>{{ $post->reading_time }} {{ __('blog.reading_time') }}</span>
        </div>
        <h3 class="text-lg font-bold group-hover:text-primary-600 dark:group-hover:text-primary-400">{{ $post->translate('title') }}</h3>
        <p class="mt-2 line-clamp-2 text-sm text-gray-600 dark:text-gray-300">{{ $post->translate('excerpt') ?: \Illuminate\Support\Str::limit(strip_tags($post->translate('body')), 120) }}</p>
    </div>
</a>
