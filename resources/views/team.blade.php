@extends('layouts.app')

@section('title', __('team.title'))

@section('content')
    @include('partials.page-hero', ['title' => __('team.title'), 'subtitle' => __('team.subtitle')])

    <section class="section">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @if ($teamMembers->isEmpty())
                <p class="text-center text-gray-500">{{ __('team.no_members') }}</p>
            @else
                <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($teamMembers as $member)
                        @include('partials.team-card', ['member' => $member])
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
