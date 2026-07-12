@extends('admin.layouts.app')

@section('title', $projectRequest->name)

@section('content')
    <x-admin.page-header :title="__('messages.project_requests')">
        <a href="{{ route('admin.project-requests.pdf', $projectRequest) }}" class="btn-secondary text-sm">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            {{ __('messages.export_pdf') }}
        </a>
        <a href="{{ route('admin.project-requests.index') }}" class="btn-secondary text-sm">{{ __('messages.back') }}</a>
    </x-admin.page-header>

    <div class="grid max-w-3xl gap-4 lg:grid-cols-3">
        <div class="space-y-4 lg:col-span-2">
            <div class="space-y-4 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 dark:bg-dark-100 dark:ring-white/5">
                <dl class="grid gap-4 sm:grid-cols-2">
                    <div><dt class="text-sm text-gray-500">{{ __('messages.name') }}</dt><dd class="font-medium">{{ $projectRequest->name }}</dd></div>
                    <div><dt class="text-sm text-gray-500">{{ __('messages.email') }}</dt><dd>{{ $projectRequest->email }}</dd></div>
                    <div><dt class="text-sm text-gray-500">WhatsApp</dt><dd dir="ltr">{{ $projectRequest->whatsapp ?? '—' }}</dd></div>
                    <div><dt class="text-sm text-gray-500">{{ __('request-project.project_type') }}</dt><dd>{{ __('request-project.project_types.'.$projectRequest->project_type) }}</dd></div>
                    <div><dt class="text-sm text-gray-500">{{ __('request-project.budget_range') }}</dt><dd>{{ __('request-project.budget_ranges.'.$projectRequest->budget_range) }}</dd></div>
                </dl>
                <div>
                    <dt class="mb-1 text-sm text-gray-500">{{ __('request-project.description') }}</dt>
                    <p class="whitespace-pre-line rounded-xl bg-gray-50 p-4 text-gray-700 dark:bg-white/5 dark:text-gray-300">{{ $projectRequest->description }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 dark:bg-dark-100 dark:ring-white/5">
            <h3 class="mb-4 font-semibold">{{ __('messages.status') }}</h3>
            <form method="POST" action="{{ route('admin.project-requests.status', $projectRequest) }}" class="space-y-3">
                @csrf
                @method('PATCH')
                <select name="status" class="form-select">
                    @foreach (\App\Models\ProjectRequest::STATUSES as $status)
                        <option value="{{ $status }}" @selected($projectRequest->status === $status)>{{ __('messages.'.$status) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn-primary w-full text-sm">{{ __('messages.update') }}</button>
            </form>
        </div>
    </div>
@endsection
