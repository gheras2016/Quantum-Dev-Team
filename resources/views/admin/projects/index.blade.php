@extends('admin.layouts.app')

@section('title', __('messages.projects'))

@section('content')
    <x-admin.page-header :title="__('messages.projects')">
        <a href="{{ route('admin.projects.create') }}" class="btn-primary text-sm">{{ __('messages.create') }}</a>
    </x-admin.page-header>

    <div class="mb-4"><x-admin.search-bar /></div>

    <x-admin.table :headers="[__('messages.title'), __('messages.client'), __('messages.status'), __('messages.featured'), __('messages.actions')]">
        @forelse ($projects as $project)
            <tr class="hover:bg-gray-50 dark:hover:bg-white/5">
                <td class="px-4 py-3 font-medium">{{ $project->translate('title') }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $project->client_name ?? '—' }}</td>
                <td class="px-4 py-3"><x-admin.status-badge :status="$project->status" /></td>
                <td class="px-4 py-3">
                    @if ($project->featured)
                        <span class="badge bg-amber-100 text-amber-800 dark:bg-amber-500/15 dark:text-amber-300">{{ __('messages.featured') }}</span>
                    @else
                        <span class="text-gray-400">—</span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <x-admin.row-actions :show="route('admin.projects.show', $project)" :edit="route('admin.projects.edit', $project)" :delete="route('admin.projects.destroy', $project)" />
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">{{ __('messages.no_results') }}</td></tr>
        @endforelse
    </x-admin.table>

    <div class="mt-4">{{ $projects->links() }}</div>
@endsection
