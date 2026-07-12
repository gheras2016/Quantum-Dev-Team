@extends('admin.layouts.app')

@section('title', $project->translate('title'))

@section('content')
    <x-admin.page-header :title="$project->translate('title')">
        <a href="{{ route('admin.projects.edit', $project) }}" class="btn-primary text-sm">{{ __('messages.edit') }}</a>
        <a href="{{ route('projects.show', $project) }}" target="_blank" class="btn-secondary text-sm">{{ __('messages.view') }}</a>
    </x-admin.page-header>

    <div class="grid gap-4 lg:grid-cols-3">
        <div class="space-y-4 lg:col-span-2">
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 dark:bg-dark-100 dark:ring-white/5">
                @if ($project->image)
                    <img src="{{ $project->image_url }}" class="mb-4 w-full rounded-xl object-cover" alt="">
                @endif
                <h3 class="font-semibold">{{ __('messages.description') }}</h3>
                <p class="mt-2 text-gray-600 dark:text-gray-300">{{ $project->translate('description') }}</p>
                @if ($project->case_study)
                    <h3 class="mt-4 font-semibold">{{ __('projects.case_study') }}</h3>
                    <p class="mt-2 text-gray-600 dark:text-gray-300">{{ $project->case_study }}</p>
                @endif
            </div>
        </div>
        <div class="space-y-4">
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 dark:bg-dark-100 dark:ring-white/5">
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between"><dt class="text-gray-500">{{ __('messages.status') }}</dt><dd><x-admin.status-badge :status="$project->status" /></dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">{{ __('messages.client') }}</dt><dd>{{ $project->client_name ?? '—' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">{{ __('messages.progress') }}</dt><dd>{{ $project->progress }}%</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">{{ __('projects.views') }}</dt><dd>{{ $project->views_count }}</dd></div>
                </dl>
            </div>
            @if ($project->technologies->isNotEmpty())
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 dark:bg-dark-100 dark:ring-white/5">
                    <h3 class="mb-3 font-semibold">{{ __('projects.technologies') }}</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($project->technologies as $tech)
                            <span class="badge bg-primary-50 text-primary-700 dark:bg-primary-900/40 dark:text-primary-300">{{ $tech->name }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
