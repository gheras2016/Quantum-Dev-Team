@props([
    'title' => '',
    'seed' => '',        // usually the slug — makes the colour deterministic per item
    'badge' => null,      // optional small label (e.g. category)
])

@php
    // A branded "cover" placeholder that carries the item's name — used when no
    // real image has been uploaded. The gradient colour is derived from the seed
    // so each project/article gets a distinct, stable look.
    $palettes = [
        ['#2563eb', '#1e3a8a'], ['#0891b2', '#0e7490'], ['#7c3aed', '#5b21b6'],
        ['#db2777', '#9d174d'], ['#ea580c', '#9a3412'], ['#059669', '#065f46'],
        ['#4f46e5', '#3730a3'], ['#0d9488', '#115e59'], ['#c026d3', '#86198f'],
    ];
    [$c1, $c2] = $palettes[abs(crc32($seed !== '' ? $seed : $title)) % count($palettes)];
@endphp

<div {{ $attributes->merge(['class' => 'relative flex items-center justify-center overflow-hidden']) }}
     style="background-image: linear-gradient(135deg, {{ $c1 }} 0%, {{ $c2 }} 100%);">
    {{-- subtle texture --}}
    <div class="absolute inset-0 bg-grid-pattern opacity-20"></div>
    {{-- browser-window dots to hint at a system UI --}}
    <div class="absolute start-4 top-4 flex gap-1.5">
        <span class="h-2.5 w-2.5 rounded-full bg-white/60"></span>
        <span class="h-2.5 w-2.5 rounded-full bg-white/40"></span>
        <span class="h-2.5 w-2.5 rounded-full bg-white/25"></span>
    </div>
    @if ($badge)
        <span class="absolute end-4 top-4 rounded-full bg-white/20 px-2.5 py-0.5 text-[11px] font-medium text-white">{{ $badge }}</span>
    @endif
    <span class="relative px-6 text-center text-xl font-bold leading-snug text-white drop-shadow">{{ $title }}</span>
</div>
