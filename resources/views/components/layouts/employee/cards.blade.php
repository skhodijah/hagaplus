@props(['title' => ''])

<div class="col-span-1">
    <div class="aspect-square h-full w-full rounded-xl border-2 border-dashed border-neutral-200 bg-neutral-50 p-6 dark:border-neutral-700 dark:bg-neutral-700/30">
        <h3 class="mb-2 sm:mb-3 lg:mb-4 text-xs sm:text-sm font-bold text-neutral-400">{{ $title }}</h3>
            {{ $slot }}
    </div>
</div>