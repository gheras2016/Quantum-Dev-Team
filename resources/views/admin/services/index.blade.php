@extends('admin.layouts.app')

@section('title', __('messages.services'))

@section('content')
    <x-admin.page-header :title="__('messages.services')">
        <a href="{{ route('admin.services.create') }}" class="btn-primary text-sm">{{ __('messages.create') }}</a>
    </x-admin.page-header>

    <div class="mb-4"><x-admin.search-bar /></div>

    <x-admin.table :headers="[__('messages.title'), __('messages.slug'), __('messages.status'), __('messages.order'), __('messages.actions')]">
        @forelse ($services as $service)
            <tr class="hover:bg-gray-50 dark:hover:bg-white/5">
                <td class="px-4 py-3 font-medium">{{ $service->translate('title') }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $service->slug }}</td>
                <td class="px-4 py-3"><x-admin.status-badge :status="$service->status" /></td>
                <td class="px-4 py-3 text-gray-500">{{ $service->order }}</td>
                <td class="px-4 py-3">
                    <x-admin.row-actions :show="route('admin.services.show', $service)" :edit="route('admin.services.edit', $service)" :delete="route('admin.services.destroy', $service)" />
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">{{ __('messages.no_results') }}</td></tr>
        @endforelse
    </x-admin.table>

    <div class="mt-4">{{ $services->links() }}</div>
@endsection
