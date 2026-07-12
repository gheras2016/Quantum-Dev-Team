@extends('layouts.app')

@section('title', __('request-project.title'))

@section('content')
    @include('partials.page-hero', ['title' => __('request-project.title'), 'subtitle' => __('request-project.subtitle')])

    <section class="section">
        <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
            @include('partials.alerts')

            <form method="POST" action="{{ route('request-project.store') }}" class="card space-y-5">
                @csrf
                <div class="absolute -left-[9999px]" aria-hidden="true">
                    <label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                </div>
                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label class="form-label" for="name">{{ __('request-project.name') }}</label>
                        <input id="name" name="name" value="{{ old('name') }}" required class="form-input" placeholder="{{ __('request-project.name_placeholder') }}">
                    </div>
                    <div>
                        <label class="form-label" for="email">{{ __('request-project.email') }}</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required class="form-input" placeholder="{{ __('request-project.email_placeholder') }}">
                    </div>
                </div>
                <div>
                    <label class="form-label" for="whatsapp">{{ __('request-project.whatsapp') }}</label>
                    <input id="whatsapp" name="whatsapp" value="{{ old('whatsapp') }}" class="form-input" placeholder="{{ __('request-project.whatsapp_placeholder') }}">
                </div>
                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label class="form-label" for="project_type">{{ __('request-project.project_type') }}</label>
                        <select id="project_type" name="project_type" required class="form-select">
                            @foreach (__('request-project.project_types') as $value => $label)
                                <option value="{{ $value }}" @selected(old('project_type') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label" for="budget_range">{{ __('request-project.budget_range') }}</label>
                        <select id="budget_range" name="budget_range" required class="form-select">
                            @foreach (__('request-project.budget_ranges') as $value => $label)
                                <option value="{{ $value }}" @selected(old('budget_range') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="form-label" for="description">{{ __('request-project.description') }}</label>
                    <textarea id="description" name="description" rows="5" required class="form-textarea" placeholder="{{ __('request-project.description_placeholder') }}">{{ old('description') }}</textarea>
                </div>
                <button type="submit" class="btn-primary w-full">{{ __('request-project.submit_request') }}</button>
            </form>
        </div>
    </section>
@endsection
