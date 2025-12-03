@props([
    'title' => 'Starter',
    'price' => '9',
    'period' => 'month',
    'description' => 'Perfect for small teams getting started',
    'popular' => false,
    'ctaText' => 'Get Started',
    'ctaLink' => '#',
    'features' => [],
    'limits' => []
])

<div @class([
    'bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden border',
    'border-2 border-[#008159] transform scale-105 z-10' => $popular,
    'border-gray-200 dark:border-gray-700' => !$popular
])>
    <div class="p-4 md:p-6">
        @if($popular)
            <div class="flex justify-between items-start mb-3">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $title }}</h3>
                    <p class="text-xs text-gray-600 dark:text-gray-300 line-clamp-2">{{ $description }}</p>
                </div>
                <span class="bg-[#D2FE8C]/40 text-[#008159] dark:text-[#76E47E] text-[10px] font-semibold px-2 py-0.5 rounded-full">
                    POPULAR
                </span>
            </div>
        @else
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">{{ $title }}</h3>
            <p class="text-xs text-gray-600 dark:text-gray-300 mb-4 line-clamp-2">{{ $description }}</p>
        @endif

        <div class="mb-4">
            <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $price }}</span>
            <span class="text-xs text-gray-500 dark:text-gray-400">/ {{ $period }}</span>
        </div>

        <a href="{{ $ctaLink }}" class="block w-full bg-[#008159] hover:bg-[#0EC774] text-white text-sm font-semibold py-2 px-4 rounded-lg text-center transition-colors mb-4 shadow-md hover:shadow-[#0EC774]/30">
            {{ $ctaText }}
        </a>

        @if(isset($limits) && !empty($limits))
            <div class="mb-4 space-y-2 border-b border-gray-100 dark:border-gray-700 pb-4">
                <div class="flex items-center justify-between text-xs">
                    <span class="text-gray-500 dark:text-gray-400">Employees</span>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $limits['employees'] ?? 'Unlimited' }}</span>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <span class="text-gray-500 dark:text-gray-400">Admins</span>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $limits['admins'] ?? 'Unlimited' }}</span>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <span class="text-gray-500 dark:text-gray-400">Branches</span>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $limits['branches'] ?? 'Unlimited' }}</span>
                </div>
            </div>
        @endif

        <ul class="space-y-2">
            @foreach($features as $feature)
                <li class="flex items-start">
                    <i class="fas fa-check text-[#0EC774] text-xs mt-1 mr-2"></i>
                    <span class="text-xs text-gray-600 dark:text-gray-300">{{ $feature }}</span>
                </li>
            @endforeach
        </ul>
    </div>
</div>
