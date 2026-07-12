@extends('admin.layouts.app')

@section('title', __('messages.faqs'))

@section('content')
    <x-admin.page-header :title="__('messages.faqs')">
        <a href="{{ route('admin.faqs.create') }}" class="btn-primary text-sm">{{ __('messages.create') }}</a>
    </x-admin.page-header>

    <div class="mb-4"><x-admin.search-bar /></div>

    <x-admin.table :headers="[__('messages.question'), __('messages.order'), __('messages.status'), __('messages.actions')]">
        @forelse ($faqs as $faq)
            <tr class="hover:bg-gray-50 dark:hover:bg-white/5">
                <td class="px-4 py-3 font-medium">{{ \Illuminate\Support\Str::limit($faq->translate('question'), 70) }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $faq->order }}</td>
                <td class="px-4 py-3">
                    <span class="badge {{ $faq->is_active ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/15 dark:text-emerald-300' : 'bg-gray-100 text-gray-600 dark:bg-white/10' }}">{{ $faq->is_active ? __('messages.active') : __('messages.inactive') }}</span>
                </td>
                <td class="px-4 py-3">
                    <x-admin.row-actions :edit="route('admin.faqs.edit', $faq)" :delete="route('admin.faqs.destroy', $faq)" />
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">{{ __('messages.no_results') }}</td></tr>
        @endforelse
    </x-admin.table>

    <div class="mt-4">{{ $faqs->links() }}</div>
@endsection
