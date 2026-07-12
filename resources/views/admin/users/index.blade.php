@extends('admin.layouts.app')

@section('title', __('messages.users'))

@section('content')
    <x-admin.page-header :title="__('messages.users')">
        <a href="{{ route('admin.users.create') }}" class="btn-primary text-sm">{{ __('messages.create') }}</a>
    </x-admin.page-header>

    <div class="mb-4"><x-admin.search-bar /></div>

    <x-admin.table :headers="[__('messages.name'), __('messages.email'), __('messages.roles'), __('messages.actions')]">
        @forelse ($users as $user)
            <tr class="hover:bg-gray-50 dark:hover:bg-white/5">
                <td class="px-4 py-3">
                    <div class="flex items-center gap-3">
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-primary-100 font-semibold text-primary-600 dark:bg-primary-500/15">{{ mb_substr($user->name, 0, 1) }}</span>
                        <span class="font-medium">{{ $user->name }}</span>
                    </div>
                </td>
                <td class="px-4 py-3 text-gray-500">{{ $user->email }}</td>
                <td class="px-4 py-3">
                    <div class="flex flex-wrap gap-1">
                        @forelse ($user->roles as $role)
                            <span class="badge bg-primary-50 text-primary-700 dark:bg-primary-900/40 dark:text-primary-300">{{ $role->name }}</span>
                        @empty
                            <span class="text-gray-400">—</span>
                        @endforelse
                    </div>
                </td>
                <td class="px-4 py-3">
                    <x-admin.row-actions
                        :edit="route('admin.users.edit', $user)"
                        :delete="$user->is(auth()->user()) ? null : route('admin.users.destroy', $user)" />
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">{{ __('messages.no_results') }}</td></tr>
        @endforelse
    </x-admin.table>

    <div class="mt-4">{{ $users->links() }}</div>
@endsection
