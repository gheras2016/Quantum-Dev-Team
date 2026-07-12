@extends('admin.layouts.app')

@section('title', __('messages.contacts'))

@section('content')
    <x-admin.page-header :title="__('messages.contacts')">
        <a href="{{ route('admin.contacts.export') }}" class="btn-secondary text-sm">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            {{ __('messages.export_excel') }}
        </a>
    </x-admin.page-header>

    <div class="mb-4"><x-admin.search-bar /></div>

    <x-admin.table :headers="[__('messages.name'), __('messages.email'), __('messages.subject'), __('messages.status'), __('messages.actions')]">
        @forelse ($contacts as $contact)
            <tr class="hover:bg-gray-50 dark:hover:bg-white/5 {{ $contact->is_read ? '' : 'font-medium' }}">
                <td class="px-4 py-3">{{ $contact->name }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $contact->email }}</td>
                <td class="px-4 py-3 text-gray-500">{{ \Illuminate\Support\Str::limit($contact->subject, 30) ?: '—' }}</td>
                <td class="px-4 py-3">
                    <x-admin.status-badge :status="$contact->is_read ? 'read' : 'new'" />
                </td>
                <td class="px-4 py-3">
                    <x-admin.row-actions :show="route('admin.contacts.show', $contact)" :delete="route('admin.contacts.destroy', $contact)" />
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">{{ __('messages.no_results') }}</td></tr>
        @endforelse
    </x-admin.table>

    <div class="mt-4">{{ $contacts->links() }}</div>
@endsection
