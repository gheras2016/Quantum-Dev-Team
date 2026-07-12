<div x-data="{ open: false }" class="relative">
    <button type="button" @click="open = !open"
            class="flex items-center gap-1 rounded-lg px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/5">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
        {{ strtoupper(app()->getLocale()) }}
    </button>
    <div x-show="open" x-cloak @click.outside="open = false" x-transition
         class="absolute {{ app()->getLocale() === 'ar' ? 'start-0' : 'end-0' }} mt-2 w-32 overflow-hidden rounded-lg bg-white py-1 shadow-lg ring-1 ring-black/5 dark:bg-dark-100">
        @foreach (['ar' => 'العربية', 'en' => 'English'] as $code => $label)
            <a href="{{ route('lang.switch', $code) }}"
               class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-white/5 {{ app()->getLocale() === $code ? 'text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>
</div>
