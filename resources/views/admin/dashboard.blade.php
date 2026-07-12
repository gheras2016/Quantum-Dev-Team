@extends('admin.layouts.app')

@section('title', __('messages.dashboard'))

@section('content')
    <x-admin.page-header :title="__('messages.dashboard')">
        <span class="text-sm text-gray-500">{{ __('messages.welcome_back') }}, {{ auth()->user()->name }}</span>
    </x-admin.page-header>

    @php
        $cards = [
            ['total_projects', $stats['projects'], 'text-primary-600 bg-primary-100 dark:bg-primary-500/15'],
            ['total_services', $stats['services'], 'text-emerald-600 bg-emerald-100 dark:bg-emerald-500/15'],
            ['total_team', $stats['team'], 'text-blue-600 bg-blue-100 dark:bg-blue-500/15'],
            ['unread_contacts', $stats['unread_contacts'], 'text-red-600 bg-red-100 dark:bg-red-500/15'],
        ];
    @endphp

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($cards as [$key, $value, $classes])
            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100 dark:bg-dark-100 dark:ring-white/5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">{{ __('messages.'.$key) }}</p>
                        <p class="mt-1 text-3xl font-bold">{{ $value }}</p>
                    </div>
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl {{ $classes }}">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </span>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4 grid gap-4 lg:grid-cols-3">
        @foreach ([['published_projects', $stats['published_projects'], 'text-emerald-600'], ['featured_projects', $stats['featured_projects'], 'text-amber-600'], ['pending_requests', $stats['pending_requests'], 'text-orange-600']] as [$key, $value, $color])
            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100 dark:bg-dark-100 dark:ring-white/5">
                <h3 class="text-sm font-medium text-gray-500">{{ __('messages.'.$key) }}</h3>
                <p class="mt-2 text-4xl font-bold {{ $color }}">{{ $value }}</p>
            </div>
        @endforeach
    </div>

    {{-- New content over time — single-series magnitude bar chart --}}
    <div class="mt-4 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 dark:bg-dark-100 dark:ring-white/5">
        <div class="mb-1 flex items-baseline justify-between">
            <h3 class="font-semibold">{{ __('messages.content_overview') }}</h3>
            <span class="text-xs text-gray-400">{{ __('messages.content_overview_hint') }}</span>
        </div>
        <div class="mt-5 flex h-48 items-end gap-3 sm:gap-5">
            @foreach ($contentChart as $bar)
                <div class="group flex flex-1 flex-col items-center justify-end" style="height:100%">
                    <span class="mb-1.5 text-xs font-semibold text-gray-600 dark:text-gray-300">{{ $bar['count'] }}</span>
                    <div class="w-full max-w-[3rem] rounded-t-md bg-primary-600 transition-colors duration-200 group-hover:bg-primary-500 dark:bg-primary-500 dark:group-hover:bg-primary-400"
                         style="height: {{ $bar['pct'] }}%"
                         title="{{ $bar['label'] }} — {{ $bar['count'] }}"
                         role="img"
                         aria-label="{{ $bar['label'] }}: {{ $bar['count'] }}"></div>
                </div>
            @endforeach
        </div>
        <div class="mt-2 flex gap-3 border-t border-gray-100 pt-2 dark:border-white/5 sm:gap-5">
            @foreach ($contentChart as $bar)
                <span class="flex-1 text-center text-xs text-gray-400">{{ $bar['label'] }}</span>
            @endforeach
        </div>
    </div>

    <div class="mt-4 grid gap-4 lg:grid-cols-2">
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 dark:bg-dark-100 dark:ring-white/5">
            <h3 class="mb-4 font-semibold">{{ __('messages.recent_activity') }}</h3>
            <ul class="space-y-3">
                @forelse ($recentActivity as $activity)
                    <li class="flex items-start gap-3 border-b border-gray-100 pb-3 last:border-0 last:pb-0 dark:border-white/5">
                        <span class="mt-1 flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-primary-100 text-primary-600 dark:bg-primary-500/15">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </span>
                        <div>
                            <p class="text-sm">{{ $activity->description }}</p>
                            <p class="text-xs text-gray-400">{{ $activity->created_at->diffForHumans() }}</p>
                        </div>
                    </li>
                @empty
                    <li class="text-sm text-gray-500">{{ __('messages.no_activity_yet') }}</li>
                @endforelse
            </ul>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 dark:bg-dark-100 dark:ring-white/5">
            <h3 class="mb-4 font-semibold">{{ __('messages.recent_contacts') }}</h3>
            <ul class="space-y-3">
                @forelse ($recentContacts as $contact)
                    <li class="flex items-center justify-between border-b border-gray-100 pb-3 last:border-0 last:pb-0 dark:border-white/5">
                        <a href="{{ route('admin.contacts.show', $contact) }}" class="min-w-0">
                            <p class="truncate text-sm font-medium">{{ $contact->name }}</p>
                            <p class="truncate text-xs text-gray-400">{{ $contact->email }}</p>
                        </a>
                        @unless ($contact->is_read)
                            <span class="badge bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-300">{{ __('messages.new') }}</span>
                        @endunless
                    </li>
                @empty
                    <li class="text-sm text-gray-500">{{ __('messages.no_results') }}</li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection
