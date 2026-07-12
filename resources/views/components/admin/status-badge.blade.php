@props(['status'])

@php
    $map = [
        'published' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/15 dark:text-emerald-300',
        'draft' => 'bg-gray-100 text-gray-700 dark:bg-white/10 dark:text-gray-300',
        'archived' => 'bg-gray-100 text-gray-500 dark:bg-white/5 dark:text-gray-400',
        'new' => 'bg-blue-100 text-blue-800 dark:bg-blue-500/15 dark:text-blue-300',
        'pending' => 'bg-amber-100 text-amber-800 dark:bg-amber-500/15 dark:text-amber-300',
        'in_review' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-500/15 dark:text-indigo-300',
        'approved' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/15 dark:text-emerald-300',
        'in_progress' => 'bg-cyan-100 text-cyan-800 dark:bg-cyan-500/15 dark:text-cyan-300',
        'completed' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/15 dark:text-emerald-300',
        'rejected' => 'bg-red-100 text-red-800 dark:bg-red-500/15 dark:text-red-300',
        'read' => 'bg-gray-100 text-gray-600 dark:bg-white/10 dark:text-gray-300',
    ];
@endphp

<span class="badge {{ $map[$status] ?? 'bg-gray-100 text-gray-700' }}">{{ __('messages.'.$status) }}</span>
