@extends('admin.layouts.app')

@section('title', __('messages.trash'))

@section('content')
    <x-admin.page-header :title="__('messages.trash')" />

    {{-- Resource tabs --}}
    <div class="mb-5 flex flex-wrap gap-2">
        @foreach ($resources as $key)
            <a href="{{ route('admin.trash.index', $key) }}"
               class="badge px-4 py-2 text-sm {{ $resource === $key ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-dark-100 dark:text-gray-300' }}">
                {{ __('messages.'.$key) }}
            </a>
        @endforeach
    </div>

    <x-admin.table :headers="[__('messages.title'), __('messages.deleted_at'), __('messages.actions')]">
        @forelse ($items as $item)
            <tr class="hover:bg-gray-50 dark:hover:bg-white/5">
                <td class="px-4 py-3 font-medium">{{ \Illuminate\Support\Str::limit($item->activityLabel(), 60) }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $item->deleted_at?->diffForHumans() }}</td>
                <td class="px-4 py-3">
                    <div class="flex items-center justify-end gap-1">
                        <form method="POST" action="{{ route('admin.trash.restore', [$resource, $item->id]) }}" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="rounded-lg px-2 py-1.5 text-sm text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-500/10">{{ __('messages.restore') }}</button>
                        </form>
                        <form method="POST" action="{{ route('admin.trash.destroy', [$resource, $item->id]) }}"
                              onsubmit="return confirm('{{ __('messages.confirm_permanent_delete') }}')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-lg px-2 py-1.5 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-500/10">{{ __('messages.permanently_delete') }}</button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="3" class="px-4 py-8 text-center text-gray-500">{{ __('messages.trash_empty') }}</td></tr>
        @endforelse
    </x-admin.table>

    <div class="mt-4">{{ $items->links() }}</div>
@endsection
