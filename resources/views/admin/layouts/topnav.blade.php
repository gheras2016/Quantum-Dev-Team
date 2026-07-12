<header class="sticky top-0 z-20 flex h-16 items-center justify-between gap-4 border-b border-gray-200 bg-white px-4 dark:border-white/5 dark:bg-dark-200 sm:px-6">
    <button type="button" @click="sidebar = !sidebar" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 dark:hover:bg-white/5 lg:hidden" aria-label="Menu">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
    </button>

    <div class="flex flex-1 items-center justify-end gap-2">
        @php
            $unreadContacts = \App\Models\Contact::unread()->count();
            $pendingRequests = \App\Models\ProjectRequest::pending()->count();
            $notifCount = $unreadContacts + $pendingRequests;
        @endphp
        <div x-data="{ open: false }" class="relative">
            <button type="button" @click="open = !open" class="relative rounded-lg p-2 text-gray-500 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/5" aria-label="Notifications">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                @if ($notifCount > 0)
                    <span class="absolute -end-0.5 -top-0.5 flex h-4 min-w-4 items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-semibold text-white">{{ $notifCount }}</span>
                @endif
            </button>
            <div x-show="open" x-cloak @click.outside="open = false" x-transition
                 class="absolute {{ app()->getLocale() === 'ar' ? 'start-0' : 'end-0' }} mt-2 w-72 overflow-hidden rounded-lg bg-white py-1 shadow-lg ring-1 ring-black/5 dark:bg-dark-100">
                <p class="px-4 py-2 text-xs font-semibold uppercase tracking-wide text-gray-400">{{ __('messages.recent_activity') }}</p>
                <a href="{{ route('admin.contacts.index') }}" class="flex items-center justify-between px-4 py-2.5 text-sm hover:bg-gray-100 dark:hover:bg-white/5">
                    <span>{{ __('messages.unread_contacts') }}</span>
                    <span class="badge {{ $unreadContacts ? 'bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-300' : 'bg-gray-100 text-gray-500 dark:bg-white/10' }}">{{ $unreadContacts }}</span>
                </a>
                <a href="{{ route('admin.project-requests.index') }}" class="flex items-center justify-between px-4 py-2.5 text-sm hover:bg-gray-100 dark:hover:bg-white/5">
                    <span>{{ __('messages.pending_requests') }}</span>
                    <span class="badge {{ $pendingRequests ? 'bg-amber-100 text-amber-700 dark:bg-amber-500/15 dark:text-amber-300' : 'bg-gray-100 text-gray-500 dark:bg-white/10' }}">{{ $pendingRequests }}</span>
                </a>
                @if ($notifCount === 0)
                    <p class="px-4 py-3 text-center text-sm text-gray-400">{{ __('messages.no_results') }}</p>
                @endif
            </div>
        </div>

        @include('layouts.components.language-switcher')

        <button type="button" @click="$store.theme.toggle()" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/5" aria-label="Theme">
            <svg x-show="!$store.theme.dark" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
            <svg x-show="$store.theme.dark" x-cloak class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
        </button>

        <div x-data="{ open: false }" class="relative">
            <button type="button" @click="open = !open" class="flex items-center gap-2 rounded-lg p-1.5 hover:bg-gray-100 dark:hover:bg-white/5">
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-primary-600 text-sm font-semibold text-white">{{ mb_substr(auth()->user()->name, 0, 1) }}</span>
                <span class="hidden text-sm font-medium sm:block">{{ auth()->user()->name }}</span>
            </button>
            <div x-show="open" x-cloak @click.outside="open = false" x-transition
                 class="absolute {{ app()->getLocale() === 'ar' ? 'start-0' : 'end-0' }} mt-2 w-48 overflow-hidden rounded-lg bg-white py-1 shadow-lg ring-1 ring-black/5 dark:bg-dark-100">
                <a href="{{ route('admin.profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-white/5">{{ __('messages.profile') }}</a>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="block w-full px-4 py-2 text-start text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-white/5">{{ __('messages.logout') }}</button>
                </form>
            </div>
        </div>
    </div>
</header>
