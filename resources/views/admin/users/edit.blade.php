@extends('admin.layouts.app')

@section('title', __('messages.users'))

@section('content')
    <x-admin.page-header :title="__('messages.edit').' — '.$user->name" />

    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="max-w-2xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 dark:bg-dark-100 dark:ring-white/5">
        @csrf
        @method('PUT')
        @include('admin.users._form')
        <div class="mt-6 flex gap-2">
            <button type="submit" class="btn-primary text-sm">{{ __('messages.update') }}</button>
            <a href="{{ route('admin.users.index') }}" class="btn-secondary text-sm">{{ __('messages.cancel') }}</a>
        </div>
    </form>

    @if ($user->hasTwoFactorEnabled())
        <div class="mt-6 max-w-2xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 dark:bg-dark-100 dark:ring-white/5">
            <h3 class="font-semibold">{{ __('two-factor.title') }}</h3>
            <p class="mt-1 text-sm text-gray-500">{{ __('two-factor.admin_reset_hint') }}</p>
            <form method="POST" action="{{ route('admin.users.reset-two-factor', $user) }}" class="mt-4"
                  onsubmit="return confirm('{{ __('two-factor.admin_reset_confirm') }}')">
                @csrf
                @method('DELETE')
                <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-red-700">
                    {{ __('two-factor.admin_reset_button') }}
                </button>
            </form>
        </div>
    @endif
@endsection
