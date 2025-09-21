@props([
    'color' => 'default',
    'text' => 'Action', 
    'route' => '#'
])

@php
    $buttonClass = match($color) {
        'green' => 'border-haga-2 bg-haga-1 text-haga-3 hover:bg-haga-1/70',
        'lime' => 'border-haga-2 bg-haga-3/70 text-neutral-900 hover:bg-haga-3',
        'amber' => 'border-amber-200 bg-amber-400/70 text-neutral-900 hover:bg-amber-400',
        'amber-2' => 'border-amber-400 bg-amber-600/70 text-neutral-800 hover:bg-amber-600',
        default => 'bg-gray-200 hover:bg-gray-300 text-gray-800'
    };
@endphp


<a href="{{ $route }}" type="button" class="rounded-lg border flex-col font-medium {{ $buttonClass}} flex items-center justify-center">
    {{ $slot }}
    <p class="text-sm">{{ $text }}</p>
</a>