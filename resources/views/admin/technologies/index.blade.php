@extends('admin.layouts.app')

@section('title', __('messages.technologies'))

@section('content')
    <x-admin.page-header :title="__('messages.technologies')">
        <a href="{{ route('admin.technologies.create') }}" class="btn-primary text-sm">{{ __('messages.create') }}</a>
    </x-admin.page-header>

    <div class="mb-4"><x-admin.search-bar /></div>

    <x-admin.table :headers="[__('messages.name'), __('messages.slug'), __('messages.actions')]">
        @forelse ($technologies as $technology)
            <tr class="hover:bg-gray-50 dark:hover:bg-white/5">
                <td class="px-4 py-3 font-medium">{{ $technology->name }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $technology->slug }}</td>
                <td class="px-4 py-3">
                    <x-admin.row-actions :edit="route('admin.technologies.edit', $technology)" :delete="route('admin.technologies.destroy', $technology)" />
                </td>
            </tr>
        @empty
            <tr><td colspan="3" class="px-4 py-8 text-center text-gray-500">{{ __('messages.no_results') }}</td></tr>
        @endforelse
    </x-admin.table>

    <div class="mt-4">{{ $technologies->links() }}</div>
@endsection
