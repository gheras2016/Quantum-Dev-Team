@extends('admin.layouts.app')

@section('title', __('messages.team'))

@section('content')
    <x-admin.page-header :title="__('messages.team')">
        <a href="{{ route('admin.team.create') }}" class="btn-primary text-sm">{{ __('messages.create') }}</a>
    </x-admin.page-header>

    <div class="mb-4"><x-admin.search-bar /></div>

    <x-admin.table :headers="[__('messages.name'), __('messages.role'), __('messages.status'), __('messages.actions')]">
        @forelse ($members as $member)
            <tr class="hover:bg-gray-50 dark:hover:bg-white/5">
                <td class="px-4 py-3">
                    <div class="flex items-center gap-3">
                        @if ($member->image)
                            <img src="{{ $member->image_url }}" class="h-9 w-9 rounded-full object-cover" alt="">
                        @else
                            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-primary-100 font-semibold text-primary-600 dark:bg-primary-500/15">{{ mb_substr($member->name, 0, 1) }}</span>
                        @endif
                        <span class="font-medium">{{ $member->name }}</span>
                    </div>
                </td>
                <td class="px-4 py-3 text-gray-500">{{ $member->role }}</td>
                <td class="px-4 py-3"><x-admin.status-badge :status="$member->status" /></td>
                <td class="px-4 py-3">
                    <x-admin.row-actions :show="route('admin.team.show', $member)" :edit="route('admin.team.edit', $member)" :delete="route('admin.team.destroy', $member)" />
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">{{ __('messages.no_results') }}</td></tr>
        @endforelse
    </x-admin.table>

    <div class="mt-4">{{ $members->links() }}</div>
@endsection
