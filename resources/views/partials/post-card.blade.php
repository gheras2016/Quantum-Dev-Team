<a href="{{ route('blog.show', $post) }}" class="card group block overflow-hidden !p-0">
    <div class="relative h-44 overflow-hidden">
        @if ($post->image)
            <img src="{{ $post->image_url }}" alt="{{ $post->translate('title') }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
        @else
            <x-cover :title="$post->translate('title')" :seed="$post->slug"
                     class="transition-transform duration-500 group-hover:scale-110" />
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
