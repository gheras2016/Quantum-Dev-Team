<a href="{{ route('projects.show', $project) }}" class="card group block overflow-hidden !p-0">
    <div class="relative h-48 overflow-hidden">
        @if ($project->image)
            <img src="{{ $project->image_url }}" alt="{{ $project->translate('title') }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
        @else
            <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-primary-600 to-primary-400 text-white">
                <svg class="h-14 w-14 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
        @endif
        @if ($project->featured)
            <span class="badge absolute end-3 top-3 bg-yellow-400/95 text-yellow-900">{{ __('messages.featured') }}</span>
        @endif
    </div>
    <div class="p-6">
        <h3 class="text-lg font-bold group-hover:text-primary-600 dark:group-hover:text-primary-400">{{ $project->translate('title') }}</h3>
        <p class="mt-2 line-clamp-2 text-sm text-gray-600 dark:text-gray-300">{{ $project->translate('description') }}</p>
        @if ($project->relationLoaded('technologies') && $project->technologies->isNotEmpty())
            <div class="mt-4 flex flex-wrap gap-1.5">
                @foreach ($project->technologies->take(3) as $tech)
                    <span class="badge bg-primary-50 text-primary-700 dark:bg-primary-900/40 dark:text-primary-300">{{ $tech->name }}</span>
                @endforeach
            </div>
        @endif
    </div>
</a>
