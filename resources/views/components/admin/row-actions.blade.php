@props(['edit' => null, 'show' => null, 'delete' => null])

<div class="flex items-center justify-end gap-1">
    @if ($show)
        <a href="{{ $show }}" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 dark:hover:bg-white/5" title="{{ __('messages.view') }}">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
        </a>
    @endif
    @if ($edit)
        <a href="{{ $edit }}" class="rounded-lg p-2 text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-500/10" title="{{ __('messages.edit') }}">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        </a>
    @endif
    @if ($delete)
        <x-admin.delete-form :action="$delete" />
    @endif
</div>
