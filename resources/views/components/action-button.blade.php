@props([
    'href' => '#',
    'color' => 'blue',
    'type' => 'link'
])

@php
    $colorClasses = [
        'blue' => 'bg-blue-600 hover:bg-blue-700',
        'amber' => 'bg-amber-600 hover:bg-amber-700',
        'emerald' => 'bg-emerald-600 hover:bg-emerald-700',
        'purple' => 'bg-purple-600 hover:bg-purple-700',
        'slate' => 'bg-slate-700 hover:bg-slate-800',
        'red' => 'bg-red-600 hover:bg-red-700',
        'green' => 'bg-green-600 hover:bg-green-700',
        'yellow' => 'bg-yellow-600 hover:bg-yellow-700',
        'indigo' => 'bg-indigo-600 hover:bg-indigo-700',
        'gray' => 'bg-gray-600 hover:bg-gray-700'
    ];

    $classes = $colorClasses[$color] ?? $colorClasses['blue'];
@endphp

@if($type === 'button')
    <button {{ $attributes->merge(['class' => 'inline-flex items-center justify-center px-3 py-2 rounded-md text-white text-sm ' . $classes]) }}>
        {{ $slot }}
    </button>
@else
    <a href="{{ $href }}" class="inline-flex items-center justify-center px-3 py-2 rounded-md text-white text-sm {{ $classes }} {{ $attributes->get('class') }}">
        {{ $slot }}
    </a>
@endif