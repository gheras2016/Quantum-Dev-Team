@extends('admin.layouts.app')

@section('title', __('backup.title'))

@section('content')
    <x-admin.page-header :title="__('backup.title')" />

    <div class="max-w-3xl space-y-6">
        {{-- Export --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 dark:bg-dark-100 dark:ring-white/5">
            <h3 class="mb-2 font-semibold">{{ __('backup.export_title') }}</h3>
            <p class="text-sm text-gray-600 dark:text-gray-300">{{ __('backup.export_desc') }}</p>

            <p class="mt-3 text-sm text-gray-500">{{ __('backup.includes', ['count' => count($tables)]) }}</p>

            <div class="mt-3 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800 dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-300">
                {{ __('backup.export_note') }}
            </div>
            <p class="mt-3 text-xs text-gray-400">{{ __('backup.railway_tip') }}</p>

            <a href="{{ route('admin.backup.export') }}" class="btn-primary mt-5 inline-flex items-center gap-2 text-sm">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                {{ __('backup.export_button') }}
            </a>
        </div>

        {{-- Restore --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 dark:bg-dark-100 dark:ring-white/5">
            <h3 class="mb-2 font-semibold">{{ __('backup.restore_title') }}</h3>
            <p class="text-sm text-gray-600 dark:text-gray-300">{{ __('backup.restore_desc') }}</p>

            <div class="mt-3 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-500/30 dark:bg-red-500/10 dark:text-red-300">
                {{ __('backup.restore_warning') }}
            </div>

            <form method="POST" action="{{ route('admin.backup.import') }}" enctype="multipart/form-data"
                  x-data="{ ready: false }" @submit="ready || $event.preventDefault()" class="mt-5 space-y-4">
                @csrf

                <div>
                    <label for="backup_file" class="form-label">{{ __('backup.file_label') }}</label>
                    <input id="backup_file" name="backup_file" type="file" accept=".json,application/json" required class="form-input">
                    @error('backup_file')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="confirm" value="1" x-model="ready" required
                           class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                    {{ __('backup.confirm_label') }}
                </label>

                <button type="submit" x-bind:disabled="!ready"
                        class="btn-primary text-sm disabled:cursor-not-allowed disabled:opacity-50">
                    {{ __('backup.restore_button') }}
                </button>
            </form>
        </div>
    </div>
@endsection
