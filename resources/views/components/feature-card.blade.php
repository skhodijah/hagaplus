@props([
    'icon' => 'fas fa-star',
    'title' => 'Feature Title',
    'description' => 'Feature description goes here.',
    'color' => 'blue',
    'iconColor' => 'blue-600',
    'darkIconColor' => 'blue-400',
    'bgColor' => 'blue-100',
    'darkBgColor' => 'blue-900/40'
])

<div class="feature-card bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700">
    <div class="w-14 h-14 bg-{{ $bgColor }} dark:bg-{{ $darkBgColor }} rounded-xl flex items-center justify-center mb-6">
        <i class="{{ $icon }} text-2xl text-{{ $iconColor }} dark:text-{{ $darkIconColor }}"></i>
    </div>
    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">{{ $title }}</h3>
    <p class="text-gray-600 dark:text-gray-300 mb-4">
        {{ $description }}
    </p>
    <a href="#" class="text-{{ $iconColor }} dark:text-{{ $darkIconColor }} font-medium inline-flex items-center">
        Learn more
        <i class="fas fa-arrow-right ml-2"></i>
    </a>
</div>
