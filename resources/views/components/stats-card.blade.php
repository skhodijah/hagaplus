@props([
    'title' => '',
    'value' => 0,
    'subtitle' => null,
    'icon' => 'fa-solid fa-circle',
    'color' => 'blue',
    'trend' => null,
    'trendLabel' => null
])

@php
    $colorClasses = [
        'blue' => ['bg-blue-100 dark:bg-blue-900', 'text-blue-600 dark:text-blue-300'],
        'green' => ['bg-green-100 dark:bg-green-900', 'text-green-600 dark:text-green-300'],
        'purple' => ['bg-purple-100 dark:bg-purple-900', 'text-purple-600 dark:text-purple-300'],
        'orange' => ['bg-orange-100 dark:bg-orange-900', 'text-orange-600 dark:text-orange-300'],
        'cyan' => ['bg-cyan-100 dark:bg-cyan-900', 'text-cyan-600 dark:text-cyan-300'],
        'pink' => ['bg-pink-100 dark:bg-pink-900', 'text-pink-600 dark:text-pink-300'],
        'emerald' => ['bg-emerald-100 dark:bg-emerald-900', 'text-emerald-600 dark:text-emerald-300'],
        'indigo' => ['bg-indigo-100 dark:bg-indigo-900', 'text-indigo-600 dark:text-indigo-300'],
        'yellow' => ['bg-yellow-100 dark:bg-yellow-900', 'text-yellow-600 dark:text-yellow-300'],
        'red' => ['bg-red-100 dark:bg-red-900', 'text-red-600 dark:text-red-300'],
        'teal' => ['bg-teal-100 dark:bg-teal-900', 'text-teal-600 dark:text-teal-300'],
        'rose' => ['bg-rose-100 dark:bg-rose-900', 'text-rose-600 dark:text-rose-300'],
    ];

    $bgClass = $colorClasses[$color][0] ?? $colorClasses['blue'][0];
    $textClass = $colorClasses[$color][1] ?? $colorClasses['blue'][1];
@endphp

<div class="bg-white dark:bg-gray-900 rounded-lg shadow p-4 flex items-center">
    <div class="p-3 rounded-full {{ $bgClass }} {{ $textClass }} mr-4">
        <i class="{{ $icon }}"></i>
    </div>
    <div class="flex-1">
        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $title }}</div>
        <div class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $value }}</div>
        @if($subtitle)
            <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $subtitle }}</div>
        @endif
        @if($trend)
            <div class="flex items-center mt-1">
                <span class="text-xs {{ str_starts_with($trend, '-') ? 'text-red-500' : 'text-green-500' }}">
                    <i class="fas {{ str_starts_with($trend, '-') ? 'fa-arrow-down' : 'fa-arrow-up' }} mr-1"></i>
                    {{ $trend }}
                </span>
                @if($trendLabel)
                    <span class="text-xs text-gray-400 dark:text-gray-500 ml-1">{{ $trendLabel }}</span>
                @endif
            </div>
        @endif
    </div>
</div>