@props([
    'href' => '#',
    'color' => 'blue',
    'type' => 'link',
    'icon' => null
])

@php
    $colors = [
        'blue' => 'bg-gradient-to-br from-blue-50 to-blue-50/70 hover:from-blue-100 hover:to-blue-100/70 dark:from-blue-900/20 dark:to-blue-900/10 dark:hover:from-blue-900/30 dark:hover:to-blue-900/20 text-blue-700 dark:text-blue-200 border border-blue-100 dark:border-blue-800/50',
        'green' => 'bg-gradient-to-br from-green-50 to-green-50/70 hover:from-green-100 hover:to-green-100/70 dark:from-green-900/20 dark:to-green-900/10 dark:hover:from-green-900/30 dark:hover:to-green-900/20 text-green-700 dark:text-green-200 border border-green-100 dark:border-green-800/50',
        'red' => 'bg-gradient-to-br from-red-50 to-red-50/70 hover:from-red-100 hover:to-red-100/70 dark:from-red-900/20 dark:to-red-900/10 dark:hover:from-red-900/30 dark:hover:to-red-900/20 text-red-700 dark:text-red-200 border border-red-100 dark:border-red-800/50',
        'yellow' => 'bg-gradient-to-br from-yellow-50 to-yellow-50/70 hover:from-yellow-100 hover:to-yellow-100/70 dark:from-yellow-900/20 dark:to-yellow-900/10 dark:hover:from-yellow-900/30 dark:hover:to-yellow-900/20 text-yellow-700 dark:text-yellow-200 border border-yellow-100 dark:border-yellow-800/50',
        'purple' => 'bg-gradient-to-br from-purple-50 to-purple-50/70 hover:from-purple-100 hover:to-purple-100/70 dark:from-purple-900/20 dark:to-purple-900/10 dark:hover:from-purple-900/30 dark:hover:to-purple-900/20 text-purple-700 dark:text-purple-200 border border-purple-100 dark:border-purple-800/50',
        'pink' => 'bg-gradient-to-br from-pink-50 to-pink-50/70 hover:from-pink-100 hover:to-pink-100/70 dark:from-pink-900/20 dark:to-pink-900/10 dark:hover:from-pink-900/30 dark:hover:to-pink-900/20 text-pink-700 dark:text-pink-200 border border-pink-100 dark:border-pink-800/50',
        'indigo' => 'bg-gradient-to-br from-indigo-50 to-indigo-50/70 hover:from-indigo-100 hover:to-indigo-100/70 dark:from-indigo-900/20 dark:to-indigo-900/10 dark:hover:from-indigo-900/30 dark:hover:to-indigo-900/20 text-indigo-700 dark:text-indigo-200 border border-indigo-100 dark:border-indigo-800/50',
        'orange' => 'bg-gradient-to-br from-orange-50 to-orange-50/70 hover:from-orange-100 hover:to-orange-100/70 dark:from-orange-900/20 dark:to-orange-900/10 dark:hover:from-orange-900/30 dark:hover:to-orange-900/20 text-orange-700 dark:text-orange-200 border border-orange-100 dark:border-orange-800/50',
        'gray' => 'bg-gradient-to-br from-gray-50 to-gray-50/70 hover:from-gray-100 hover:to-gray-100/70 dark:from-gray-800/30 dark:to-gray-800/20 dark:hover:from-gray-800/40 dark:hover:to-gray-800/30 text-gray-700 dark:text-gray-200 border border-gray-100 dark:border-gray-700/50',
    ];
    
    $iconColors = [
        'blue' => 'text-blue-500 dark:text-blue-400',
        'green' => 'text-green-500 dark:text-green-400',
        'red' => 'text-red-500 dark:text-red-400',
        'yellow' => 'text-yellow-500 dark:text-yellow-400',
        'purple' => 'text-purple-500 dark:text-purple-400',
        'pink' => 'text-pink-500 dark:text-pink-400',
        'indigo' => 'text-indigo-500 dark:text-indigo-400',
        'orange' => 'text-orange-500 dark:text-orange-400',
        'gray' => 'text-gray-500 dark:text-gray-400',
    ];
    
    $colorClasses = $colors[$color] ?? $colors['blue'];
    $iconColorClasses = $iconColors[$color] ?? $iconColors['blue'];
@endphp

@if($type === 'button')
    <button {{ $attributes->merge(['class' => 'group relative flex flex-col p-5 rounded-xl transition-all duration-300 ease-in-out hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-opacity-50 dark:focus:ring-offset-gray-900 ' . $colorClasses . ' shadow-sm hover:shadow-md overflow-hidden']) }}>
        <div class="flex items-start">
            @if($icon)
                <div class="flex-shrink-0 mr-4 mt-0.5">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-white/50 dark:bg-gray-800/30 group-hover:bg-white/70 dark:group-hover:bg-gray-800/50 transition-colors duration-300 shadow-sm">
                        <i class="{{ $icon }} {{ $iconColorClasses }} text-lg"></i>
                    </div>
                </div>
            @endif
            <div class="flex-1">
                {{ $slot }}
            </div>
        </div>
    </button>
@else
    <a href="{{ $href }}"
       class="group relative flex flex-col p-5 rounded-xl transition-all duration-300 ease-in-out hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-opacity-50 dark:focus:ring-offset-gray-900 {{ $colorClasses }} shadow-sm hover:shadow-md overflow-hidden"
       {{ $attributes }}>
        <div class="flex items-start">
            @if($icon)
                <div class="flex-shrink-0 mr-4 mt-0.5">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-white/50 dark:bg-gray-800/30 group-hover:bg-white/70 dark:group-hover:bg-gray-800/50 transition-colors duration-300 shadow-sm">
                        <i class="{{ $icon }} {{ $iconColorClasses }} text-lg"></i>
                    </div>
                </div>
            @endif
            <div class="flex-1">
                {{ $slot }}
            </div>
        </div>
    </a>
@endif