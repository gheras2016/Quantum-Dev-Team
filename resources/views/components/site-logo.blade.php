@props([
    'height' => 'h-12',        // logo image height (width is automatic to keep the original ratio)
    'box' => 'h-12 w-12',      // square size of the "Q" fallback
    'rounded' => 'rounded-xl',
    'text' => 'text-xl',       // "Q" letter size in the fallback
])

@php $logo = setting('site_logo'); @endphp

@if ($logo)
    {{-- Uploaded logo: constrain height only so the original aspect ratio and quality are preserved. --}}
    <img src="{{ media_url($logo) }}" alt="{{ __('messages.site_name') }}"
         class="{{ $height }} w-auto max-w-[220px] {{ $rounded }} object-contain">
@else
    <span class="{{ $box }} {{ $rounded }} {{ $text }} flex items-center justify-center bg-gradient-to-br from-primary-600 to-primary-400 font-bold text-white">Q</span>
@endif
