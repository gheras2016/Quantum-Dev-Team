<form method="GET" class="flex flex-1 items-center gap-2 sm:max-w-xs">
    <div class="relative flex-1">
        <input type="search" name="search" value="{{ request('search') }}" placeholder="{{ __('messages.search') }}"
               class="form-input ps-9">
        <svg class="pointer-events-none absolute top-2.5 h-5 w-5 text-gray-400 rtl:right-2.5 ltr:left-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
    </div>
    {{ $slot ?? '' }}
</form>
