@props(['title'])

<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $title }}</h1>
    <div class="flex items-center gap-2">{{ $slot }}</div>
</div>
