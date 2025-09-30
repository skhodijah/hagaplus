@props([
    'title',
    'value',
    'icon' => 'fas fa-chart-bar',
    'color' => 'blue',
    'href' => null,
    'trend' => null,
    'trendLabel' => null
])

@php
    $colorClasses = [
        'blue' => [
            'border' => 'border-blue-500',
            'bg' => 'bg-blue-100 dark:bg-blue-900/30',
            'text' => 'text-blue-600 dark:text-blue-400'
        ],
        'green' => [
            'border' => 'border-green-500',
            'bg' => 'bg-green-100 dark:bg-green-900/30',
            'text' => 'text-green-600 dark:text-green-400'
        ],
        'purple' => [
            'border' => 'border-purple-500',
            'bg' => 'bg-purple-100 dark:bg-purple-900/30',
            'text' => 'text-purple-600 dark:text-purple-400'
        ],
        'orange' => [
            'border' => 'border-orange-500',
            'bg' => 'bg-orange-100 dark:bg-orange-900/30',
            'text' => 'text-orange-600 dark:text-orange-400'
        ],
        'cyan' => [
            'border' => 'border-cyan-500',
            'bg' => 'bg-cyan-100 dark:bg-cyan-900/30',
            'text' => 'text-cyan-600 dark:text-cyan-400'
        ],
        'pink' => [
            'border' => 'border-pink-500',
            'bg' => 'bg-pink-100 dark:bg-pink-900/30',
            'text' => 'text-pink-600 dark:text-pink-400'
        ],
        'red' => [
            'border' => 'border-red-500',
            'bg' => 'bg-red-100 dark:bg-red-900/30',
            'text' => 'text-red-600 dark:text-red-400'
        ],
        'yellow' => [
            'border' => 'border-yellow-500',
            'bg' => 'bg-yellow-100 dark:bg-yellow-900/30',
            'text' => 'text-yellow-600 dark:text-yellow-400'
        ],
        'indigo' => [
            'border' => 'border-indigo-500',
            'bg' => 'bg-indigo-100 dark:bg-indigo-900/30',
            'text' => 'text-indigo-600 dark:text-indigo-400'
        ],
        'gray' => [
            'border' => 'border-gray-500',
            'bg' => 'bg-gray-100 dark:bg-gray-900/30',
            'text' => 'text-gray-600 dark:text-gray-400'
        ]
    ];

    $classes = $colorClasses[$color] ?? $colorClasses['blue'];
@endphp

@if($href)
    <a href="{{ $href }}" class="card-hover p-4 rounded-lg bg-gray-50 dark:bg-gray-900   {{ $classes['border'] }} block hover:shadow-xl transition-shadow duration-200">
@else
    <div class="card-hover p-4 rounded-lg bg-gray-50 dark:bg-gray-900  {{ $classes['border'] }}">
@endif
    <div class="flex items-center justify-between">
        <div>
            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ $title }}</p>
            <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $value }}</p>
            @if($trend)
                <p class="mt-1 text-xs {{ $trend[0] === '+' ? 'text-green-600 dark:text-green-400' : ($trend[0] === '-' ? 'text-red-600 dark:text-red-400' : 'text-gray-600 dark:text-gray-400') }}">
                    <i class="fas {{ $trend[0] === '+' ? 'fa-arrow-up' : ($trend[0] === '-' ? 'fa-arrow-down' : 'fa-minus') }} mr-1"></i>
                    {{ $trend }} {{ $trendLabel ?? '' }}
                </p>
            @endif
        </div>
        <div class="w-12 h-12 {{ $classes['bg'] }} rounded-lg flex items-center justify-center">
            <i class="{{ $icon }} {{ $classes['text'] }} text-xl"></i>
        </div>
    </div>
@if($href)
    </a>
@else
    </div>
@endif