@extends('admin.layouts.app')

@section('title', __('messages.profile'))

@section('content')
    <x-admin.page-header :title="__('messages.profile')" />

    <div class="grid max-w-4xl gap-6 lg:grid-cols-2">
        <form method="POST" action="{{ route('admin.profile.update') }}" class="space-y-5 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 dark:bg-dark-100 dark:ring-white/5">
            @csrf
            @method('PUT')
            <h3 class="font-semibold">{{ __('messages.profile') }}</h3>
            <x-admin.input name="name" :label="__('messages.name')" :value="$user->name" required />
            <x-admin.input name="email" type="email" :label="__('messages.email')" :value="$user->email" required />
            <button type="submit" class="btn-primary text-sm">{{ __('messages.save') }}</button>
        </form>

        <form method="POST" action="{{ route('admin.profile.password') }}" class="space-y-5 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 dark:bg-dark-100 dark:ring-white/5">
            @csrf
            @method('PUT')
            <h3 class="font-semibold">{{ __('messages.change_password') }}</h3>
            <x-admin.input name="current_password" type="password" :label="__('messages.current_password')" required />
            <x-admin.input name="password" type="password" :label="__('messages.new_password')" required />
            <x-admin.input name="password_confirmation" type="password" :label="__('messages.confirm_password')" required />
            <button type="submit" class="btn-primary text-sm">{{ __('messages.update') }}</button>
        </form>
    </div>
@endsection
