@extends('admin.layouts.app')

@section('title', __('messages.services'))

@section('content')
    <x-admin.page-header :title="__('messages.edit').' — '.$service->translate('title')" />

    <form method="POST" action="{{ route('admin.services.update', $service) }}" enctype="multipart/form-data" class="max-w-3xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 dark:bg-dark-100 dark:ring-white/5">
        @csrf
        @method('PUT')
        @include('admin.services._form')
        <div class="mt-6 flex gap-2">
            <button type="submit" class="btn-primary text-sm">{{ __('messages.update') }}</button>
            <a href="{{ route('admin.services.index') }}" class="btn-secondary text-sm">{{ __('messages.cancel') }}</a>
        </div>
    </form>
@endsection
