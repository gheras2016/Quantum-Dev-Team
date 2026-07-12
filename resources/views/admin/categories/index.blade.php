@extends('admin.layouts.app')

@section('title', __('messages.categories'))

@section('content')
    <x-admin.page-header :title="__('messages.categories')">
        <a href="{{ route('admin.categories.create') }}" class="btn-primary text-sm">{{ __('messages.create') }}</a>
    </x-admin.page-header>

    <div class="mb-4"><x-admin.search-bar /></div>

    <x-admin.table :headers="[__('messages.name'), __('messages.slug'), __('messages.type'), __('messages.actions')]">
        @forelse ($categories as $category)
            <tr class="hover:bg-gray-50 dark:hover:bg-white/5">
                <td class="px-4 py-3 font-medium">{{ $category->name }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $category->slug }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $category->type ?? '—' }}</td>
                <td class="px-4 py-3">
                    <x-admin.row-actions :edit="route('admin.categories.edit', $category)" :delete="route('admin.categories.destroy', $category)" />
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">{{ __('messages.no_results') }}</td></tr>
        @endforelse
    </x-admin.table>

    <div class="mt-4">{{ $categories->links() }}</div>
@endsection
