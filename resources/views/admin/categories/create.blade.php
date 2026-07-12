@extends('admin.layouts.app')

@section('title', __('messages.categories'))

@section('content')
    <x-admin.page-header :title="__('messages.create').' — '.__('messages.categories')" />

    <form method="POST" action="{{ route('admin.categories.store') }}" class="max-w-2xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 dark:bg-dark-100 dark:ring-white/5">
        @csrf
        @include('admin.categories._form')
        <div class="mt-6 flex gap-2">
            <button type="submit" class="btn-primary text-sm">{{ __('messages.save') }}</button>
            <a href="{{ route('admin.categories.index') }}" class="btn-secondary text-sm">{{ __('messages.cancel') }}</a>
        </div>
    </form>
@endsection
