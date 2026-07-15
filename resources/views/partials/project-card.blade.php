<a href="{{ route('projects.show', $project) }}" class="card group block overflow-hidden !p-0">
    <div class="relative h-48 overflow-hidden">
        @if ($project->image)
            <img src="{{ $project->image_url }}" alt="{{ $project->translate('title') }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
        @else
            <x-cover :title="$project->translate('title')" :seed="$project->slug"
                     class="transition-transform duration-500 group-hover:scale-110" />
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
