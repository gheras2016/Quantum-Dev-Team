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
@endsection
