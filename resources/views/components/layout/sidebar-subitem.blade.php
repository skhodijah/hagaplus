@props(['href' => '#', 'label' => '', 'active' => false])

<a href="{{ $href }}" class="flex items-center px-4 py-2 text-sm rounded-lg transition-all duration-200 {{ $active ? 'bg-haga-2 text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">{{ $label }}</a> 