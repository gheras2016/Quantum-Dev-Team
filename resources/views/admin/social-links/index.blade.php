@extends('admin.layouts.app')

@section('title', __('messages.social_links'))

@section('content')
    <x-admin.page-header :title="__('messages.social_links')">
        <a href="{{ route('admin.social-links.create') }}" class="btn-primary text-sm">{{ __('messages.create') }}</a>
    </x-admin.page-header>

    <x-admin.table :headers="[__('messages.platform'), __('messages.url'), __('messages.status'), __('messages.actions')]">
        @forelse ($socialLinks as $link)
            <tr class="hover:bg-gray-50 dark:hover:bg-white/5">
                <td class="px-4 py-3 font-medium capitalize">{{ $link->platform }}</td>
                <td class="px-4 py-3 text-gray-500"><a href="{{ $link->url }}" target="_blank" class="hover:text-primary-600">{{ \Illuminate\Support\Str::limit($link->url, 40) }}</a></td>
                <td class="px-4 py-3">
                    <span class="badge {{ $link->is_active ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/15 dark:text-emerald-300' : 'bg-gray-100 text-gray-600 dark:bg-white/10' }}">
                        {{ $link->is_active ? __('messages.active') : __('messages.inactive') }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <x-admin.row-actions :edit="route('admin.social-links.edit', $link)" :delete="route('admin.social-links.destroy', $link)" />
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">{{ __('messages.no_results') }}</td></tr>
        @endforelse
    </x-admin.table>

    <div class="mt-4">{{ $socialLinks->links() }}</div>
@endsection
