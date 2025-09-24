@props([
    'title',
    'class' => 'mb-6 md:mb-8'
])

<div class="bg-white dark:bg-gray-900 rounded-lg shadow p-4 {{ $class }}">
    @if($title)
        <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ $title }}</h3>
    @endif
    {{ $slot }}
</div>