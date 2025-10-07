@props([
    'title' => 'Starter',
    'price' => '9',
    'period' => 'month',
    'description' => 'Perfect for small teams getting started',
    'popular' => false,
    'ctaText' => 'Get Started',
    'ctaLink' => '#',
    'features' => []
])

<div @class([
    'bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden border',
    'border-2 border-blue-500 transform scale-105 z-10' => $popular,
    'border-gray-200 dark:border-gray-700' => !$popular
])>
    <div class="p-8">
        @if($popular)
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $title }}</h3>
                    <p class="text-gray-600 dark:text-gray-300">{{ $description }}</p>
                </div>
                <span class="bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-200 text-xs font-semibold px-3 py-1 rounded-full">
                    POPULAR
                </span>
            </div>
        @else
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ $title }}</h3>
            <p class="text-gray-600 dark:text-gray-300 mb-6">{{ $description }}</p>
        @endif

        <div class="mb-6">
            <span class="text-4xl font-bold text-gray-900 dark:text-white">${{ $price }}</span>
            <span class="text-gray-500 dark:text-gray-400">/user/{{ $period }}</span>
        </div>

        <a href="{{ $ctaLink }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg text-center transition-colors mb-6">
            {{ $ctaText }}
        </a>

        <ul class="space-y-3">
            @foreach($features as $feature)
                <li class="flex items-start">
                    <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                    <span class="text-gray-600 dark:text-gray-300">{{ $feature }}</span>
                </li>
            @endforeach
        </ul>
    </div>
</div>
