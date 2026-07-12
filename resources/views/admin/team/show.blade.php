@extends('admin.layouts.app')

@section('title', $member->name)

@section('content')
    <x-admin.page-header :title="$member->name">
        <a href="{{ route('admin.team.edit', $member) }}" class="btn-primary text-sm">{{ __('messages.edit') }}</a>
    </x-admin.page-header>

    <div class="max-w-2xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 dark:bg-dark-100 dark:ring-white/5">
        <div class="flex items-center gap-4">
            @if ($member->image)
                <img src="{{ $member->image_url }}" class="h-20 w-20 rounded-full object-cover" alt="">
            @else
                <span class="flex h-20 w-20 items-center justify-center rounded-full bg-primary-100 text-2xl font-bold text-primary-600 dark:bg-primary-500/15">{{ mb_substr($member->name, 0, 1) }}</span>
            @endif
            <div>
                <h2 class="text-xl font-bold">{{ $member->name }}</h2>
                <p class="text-primary-600 dark:text-primary-400">{{ $member->role }}</p>
            </div>
        </div>
        @if ($member->bio)
            <p class="mt-4 text-gray-600 dark:text-gray-300">{{ $member->bio }}</p>
        @endif
        @if (!empty($member->skills))
            <div class="mt-4 flex flex-wrap gap-2">
                @foreach ($member->skills as $skill)
                    <span class="badge bg-primary-50 text-primary-700 dark:bg-primary-900/40 dark:text-primary-300">{{ $skill }}</span>
                @endforeach
            </div>
        @endif
    </div>
@endsection
