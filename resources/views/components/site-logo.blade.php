@props(['size' => 'h-10 w-10', 'rounded' => 'rounded-xl'])

@php $logo = setting('site_logo'); @endphp

@if ($logo)
    <img src="{{ asset('storage/'.$logo) }}" alt="{{ __('messages.site_name') }}"
         class="{{ $size }} {{ $rounded }} object-contain">
@else
    <span class="{{ $size }} {{ $rounded }} flex items-center justify-center bg-gradient-to-br from-primary-600 to-primary-400 font-bold text-white">Q</span>
@endif
