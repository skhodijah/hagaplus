@props(['title' => '', 'value' => 0, 'icon' => 'fa-solid fa-circle'])

<div class="bg-white dark:bg-gray-900 rounded-lg shadow p-4 flex items-center">
    <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 mr-4">
        <i class="{{ $icon }}"></i>
    </div>
    <div class="flex-1">
        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $title }}</div>
        <div class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $value }}</div>
    </div>
</div> 