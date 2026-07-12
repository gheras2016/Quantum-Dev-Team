@props(['action'])

<form method="POST" action="{{ $action }}" onsubmit="return confirm('{{ __('messages.confirm_delete') }}')" class="inline">
    @csrf
    @method('DELETE')
    <button type="submit" class="rounded-lg p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-500/10" title="{{ __('messages.delete') }}">
        <svg class="h-4.5 w-4.5 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
    </button>
</form>
