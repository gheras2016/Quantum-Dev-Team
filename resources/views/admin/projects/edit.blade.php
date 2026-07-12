@extends('admin.layouts.app')

@section('title', __('messages.projects'))

@section('content')
    <x-admin.page-header :title="__('messages.edit').' — '.$project->translate('title')" />

    <form method="POST" action="{{ route('admin.projects.update', $project) }}" enctype="multipart/form-data" class="max-w-4xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 dark:bg-dark-100 dark:ring-white/5">
        @csrf
        @method('PUT')
        @include('admin.projects._form')
        <div class="mt-6 flex gap-2">
            <button type="submit" class="btn-primary text-sm">{{ __('messages.update') }}</button>
            <a href="{{ route('admin.projects.index') }}" class="btn-secondary text-sm">{{ __('messages.cancel') }}</a>
        </div>
    </form>

    @if ($project->media->isNotEmpty())
        <div class="mt-6 max-w-4xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 dark:bg-dark-100 dark:ring-white/5">
            <h3 class="mb-4 font-semibold">{{ __('messages.gallery') }}</h3>
            <div class="grid grid-cols-3 gap-3 sm:grid-cols-5">
                @foreach ($project->media as $item)
                    <div class="group relative">
                        <img src="{{ $item->url }}" alt="{{ $item->name }}" class="h-24 w-full rounded-lg object-cover">
                        <form method="POST" action="{{ route('admin.media.destroy', $item) }}"
                              onsubmit="return confirm('{{ __('messages.confirm_delete') }}')"
                              class="absolute end-1 top-1 hidden group-hover:block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-full bg-red-600 p-1 text-white" title="{{ __('messages.delete') }}">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@endsection
