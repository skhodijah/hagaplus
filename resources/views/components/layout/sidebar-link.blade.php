@props(['href' => '#', 'icon' => 'fa-solid fa-circle', 'label' => '', 'active' => false])

<a href="{{ $href }}" class="flex items-center gap-3 px-4 py-3 rounded-xl group transition-all duration-200 {{ $active ? 'bg-gradient-to-r from-[#049460]/10 to-[#10C874]/10 dark:from-[#049460]/20 dark:to-[#10C874]/20 text-[#049460] dark:text-[#10C874] font-semibold shadow-sm border border-[#10C874]/20 dark:border-[#049460]/30' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
    <div class="w-9 h-9 flex items-center justify-center rounded-lg {{ $active ? 'bg-gradient-to-br from-[#049460] to-[#10C874] text-white shadow-md' : 'text-gray-500 dark:text-gray-400 group-hover:text-[#049460] dark:group-hover:text-[#10C874] group-hover:bg-[#049460]/10 dark:group-hover:bg-[#10C874]/10' }} transition-all duration-200">
        <i class="{{ $icon }} text-sm"></i>
    </div>
    <span class="text-sm font-medium">{{ $label }}</span>
</a> 