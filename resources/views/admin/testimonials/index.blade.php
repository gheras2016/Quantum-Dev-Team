@extends('admin.layouts.app')

@section('title', __('messages.testimonials'))

@section('content')
    <x-admin.page-header :title="__('messages.testimonials')">
        <a href="{{ route('admin.testimonials.create') }}" class="btn-primary text-sm">{{ __('messages.create') }}</a>
    </x-admin.page-header>

    <div class="mb-4"><x-admin.search-bar /></div>

    <x-admin.table :headers="[__('messages.author'), __('messages.author_company'), __('messages.rating'), __('messages.status'), __('messages.actions')]">
        @forelse ($testimonials as $t)
            <tr class="hover:bg-gray-50 dark:hover:bg-white/5">
                <td class="px-4 py-3 font-medium">{{ $t->author_name }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $t->author_company ?? '—' }}</td>
                <td class="px-4 py-3 text-amber-500">{{ str_repeat('★', $t->rating) }}</td>
                <td class="px-4 py-3">
                    <span class="badge {{ $t->is_active ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/15 dark:text-emerald-300' : 'bg-gray-100 text-gray-600 dark:bg-white/10' }}">{{ $t->is_active ? __('messages.active') : __('messages.inactive') }}</span>
                </td>
                <td class="px-4 py-3">
                    <x-admin.row-actions :edit="route('admin.testimonials.edit', $t)" :delete="route('admin.testimonials.destroy', $t)" />
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">{{ __('messages.no_results') }}</td></tr>
        @endforelse
    </x-admin.table>

    <div class="mt-4">{{ $testimonials->links() }}</div>
@endsection
