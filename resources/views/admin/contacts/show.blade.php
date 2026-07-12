@extends('admin.layouts.app')

@section('title', $contact->name)

@section('content')
    <x-admin.page-header :title="__('messages.contacts')">
        <a href="mailto:{{ $contact->email }}" class="btn-primary text-sm">{{ __('messages.reply') }}</a>
        <a href="{{ route('admin.contacts.index') }}" class="btn-secondary text-sm">{{ __('messages.back') }}</a>
    </x-admin.page-header>

    <div class="max-w-2xl space-y-4 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 dark:bg-dark-100 dark:ring-white/5">
        <dl class="grid gap-4 sm:grid-cols-2">
            <div><dt class="text-sm text-gray-500">{{ __('messages.name') }}</dt><dd class="font-medium">{{ $contact->name }}</dd></div>
            <div><dt class="text-sm text-gray-500">{{ __('messages.email') }}</dt><dd><a href="mailto:{{ $contact->email }}" class="text-primary-600 hover:underline">{{ $contact->email }}</a></dd></div>
            <div><dt class="text-sm text-gray-500">{{ __('messages.phone') }}</dt><dd>{{ $contact->phone ?? '—' }}</dd></div>
            <div><dt class="text-sm text-gray-500">{{ __('messages.subject') }}</dt><dd>{{ $contact->subject ?? '—' }}</dd></div>
            <div><dt class="text-sm text-gray-500">{{ __('messages.created_at') }}</dt><dd>{{ $contact->created_at->format('Y-m-d H:i') }}</dd></div>
        </dl>
        <div>
            <dt class="mb-1 text-sm text-gray-500">{{ __('messages.message') }}</dt>
            <p class="whitespace-pre-line rounded-xl bg-gray-50 p-4 text-gray-700 dark:bg-white/5 dark:text-gray-300">{{ $contact->message }}</p>
        </div>
    </div>
@endsection
