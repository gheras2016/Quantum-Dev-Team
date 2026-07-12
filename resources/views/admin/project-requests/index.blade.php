@extends('admin.layouts.app')

@section('title', __('messages.project_requests'))

@section('content')
    <x-admin.page-header :title="__('messages.project_requests')">
        <a href="{{ route('admin.project-requests.export') }}" class="btn-secondary text-sm">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            {{ __('messages.export_excel') }}
        </a>
    </x-admin.page-header>

    <div class="mb-4"><x-admin.search-bar /></div>

    <x-admin.table :headers="[__('messages.name'), __('messages.email'), __('request-project.project_type'), __('messages.status'), __('messages.actions')]">
        @forelse ($projectRequests as $request)
            <tr class="hover:bg-gray-50 dark:hover:bg-white/5 {{ $request->is_read ? '' : 'font-medium' }}">
                <td class="px-4 py-3">{{ $request->name }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $request->email }}</td>
                <td class="px-4 py-3 text-gray-500">{{ __('request-project.project_types.'.$request->project_type) }}</td>
                <td class="px-4 py-3"><x-admin.status-badge :status="$request->status" /></td>
                <td class="px-4 py-3">
                    <x-admin.row-actions :show="route('admin.project-requests.show', $request)" :delete="route('admin.project-requests.destroy', $request)" />
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">{{ __('messages.no_results') }}</td></tr>
        @endforelse
    </x-admin.table>

    <div class="mt-4">{{ $projectRequests->links() }}</div>
@endsection
