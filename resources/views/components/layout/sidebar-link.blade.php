@props(['href' => '#', 'icon' => 'fa-solid fa-circle', 'label' => '', 'active' => false])

<a href="{{ $href }}" 
   class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 group
          {{ $active 
             ? 'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400' 
             : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-200' }}">
    <div class="flex items-center justify-center w-8 h-8 rounded-lg mr-2 transition-colors
              {{ $active 
                 ? 'bg-blue-100 text-blue-600 dark:bg-blue-800 dark:text-blue-300' 
                 : 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400 group-hover:bg-white dark:group-hover:bg-gray-700 group-hover:shadow-sm' }}">
        <i class="{{ $icon }} text-xs"></i>
    </div>
    <span>{{ $label }}</span>
</a> 