@extends('admin.layouts.app')

@section('title', __('messages.posts'))

@section('content')
    <x-admin.page-header :title="__('messages.posts')">
        <a href="{{ route('admin.posts.create') }}" class="btn-primary text-sm">{{ __('messages.create') }}</a>
    </x-admin.page-header>

    <div class="mb-4"><x-admin.search-bar /></div>

    <x-admin.table :headers="[__('messages.title'), __('messages.status'), __('messages.featured'), __('projects.views'), __('messages.actions')]">
        @forelse ($posts as $post)
            <tr class="hover:bg-gray-50 dark:hover:bg-white/5">
                <td class="px-4 py-3 font-medium">{{ $post->translate('title') }}</td>
                <td class="px-4 py-3"><x-admin.status-badge :status="$post->status" /></td>
                <td class="px-4 py-3">@if ($post->featured)<span class="badge bg-amber-100 text-amber-800 dark:bg-amber-500/15 dark:text-amber-300">{{ __('messages.featured') }}</span>@else<span class="text-gray-400">—</span>@endif</td>
                <td class="px-4 py-3 text-gray-500">{{ $post->views_count }}</td>
                <td class="px-4 py-3">
                    <x-admin.row-actions :edit="route('admin.posts.edit', $post)" :delete="route('admin.posts.destroy', $post)" />
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">{{ __('messages.no_results') }}</td></tr>
        @endforelse
    </x-admin.table>

    <div class="mt-4">{{ $posts->links() }}</div>
@endsection
