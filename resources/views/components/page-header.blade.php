@props([
    'title',
    'subtitle' => null,
    'showPeriodFilter' => false,
    'periodOptions' => ['7d' => '7 hari', '30d' => '30 hari', '90d' => '90 hari', '1y' => '1 tahun'],
    'defaultPeriod' => '30d'
])

<div class="mb-6 md:mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $title }}</h1>
        @if($subtitle)
            <p class="mt-1 text-gray-600 dark:text-gray-400">{{ $subtitle }}</p>
        @endif
    </div>

    @if($showPeriodFilter)
        <form class="flex items-center gap-2">
            <label for="period" class="text-sm text-gray-600 dark:text-gray-400">Periode</label>
            <select id="period" name="period" class="text-sm rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                @foreach($periodOptions as $value => $label)
                    <option value="{{ $value }}" {{ request('period', $defaultPeriod) === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </form>
    @endif

    {{ $slot }}
</div>