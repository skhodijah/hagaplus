@props(['href' => '#', 'icon' => 'fa-solid fa-circle', 'label' => '', 'active' => false])

<a href="{{ $href }}" class="flex items-center px-4 py-3 rounded-lg group transition-all duration-200 {{ $active ? 'text-haga-2' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
    <i class="{{ $icon }} w-5 mr-3 {{ $active ? 'text-haga-2' : 'text-haga-2' }}"></i>
    <span class="font-medium">{{ $label }}</span>
</a> 