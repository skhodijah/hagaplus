@props(['icon' => 'fa-solid fa-folder', 'label' => '', 'open' => false, 'target' => Str::uuid()])

@php
    $isOpen = (bool) $open;
@endphp

<div class="accordion-item">
    <button class="accordion-toggle flex items-center justify-between w-full px-4 py-3 text-left rounded-lg group transition-all duration-200 {{ $isOpen ? 'bg-gray-100 dark:bg-gray-700 text-haga-2' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}" data-target="{{ $target }}">
        <div class="flex items-center">
            <i class="{{ $icon }} w-5 mr-3 text-haga-2"></i>
            <span class="font-medium">{{ $label }}</span>
        </div>
        <i class="fa-solid fa-chevron-down text-xs accordion-chevron {{ $isOpen ? 'rotate-180' : '' }}"></i>
    </button>
    <div class="accordion-content ml-8 mt-2 space-y-1 {{ $isOpen ? '' : 'hidden' }}" id="{{ $target }}">
        {{ $slot }}
    </div>
</div> 