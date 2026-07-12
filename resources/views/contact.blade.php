@extends('layouts.app')

@section('title', __('contact.title'))

@section('content')
    @include('partials.page-hero', ['title' => __('contact.title'), 'subtitle' => __('contact.subtitle')])

    <section class="section">
        <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
            @include('partials.alerts')

            <form method="POST" action="{{ route('contact.store') }}" class="card space-y-5">
                @csrf
                <div class="absolute -left-[9999px]" aria-hidden="true">
                    <label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                </div>
                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label class="form-label" for="name">{{ __('contact.name') }}</label>
                        <input id="name" name="name" value="{{ old('name') }}" required class="form-input" placeholder="{{ __('contact.name_placeholder') }}">
                    </div>
                    <div>
                        <label class="form-label" for="email">{{ __('contact.email') }}</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required class="form-input" placeholder="{{ __('contact.email_placeholder') }}">
                    </div>
                </div>
                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label class="form-label" for="phone">{{ __('contact.phone') }}</label>
                        <input id="phone" name="phone" value="{{ old('phone') }}" class="form-input" placeholder="{{ __('contact.phone_placeholder') }}">
                    </div>
                    <div>
                        <label class="form-label" for="subject">{{ __('contact.subject') }}</label>
                        <input id="subject" name="subject" value="{{ old('subject') }}" class="form-input" placeholder="{{ __('contact.subject_placeholder') }}">
                    </div>
                </div>
                <div>
                    <label class="form-label" for="message">{{ __('contact.message') }}</label>
                    <textarea id="message" name="message" rows="5" required class="form-textarea" placeholder="{{ __('contact.message_placeholder') }}">{{ old('message') }}</textarea>
                </div>
                <button type="submit" class="btn-primary w-full">{{ __('contact.send_message') }}</button>
            </form>
        </div>
    </section>
@endsection
