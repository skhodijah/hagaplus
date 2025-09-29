@props([
    'id' => 'chart',
    'type' => 'line',
    'title' => '',
    'height' => '300px',
    'data' => [],
    'options' => []
])

@php
    $defaultOptions = [
        'responsive' => true,
        'maintainAspectRatio' => false,
        'plugins' => [
            'legend' => [
                'display' => true,
                'position' => 'top',
            ],
        ],
        'scales' => [
            'y' => [
                'beginAtZero' => true,
            ],
        ],
    ];

    $chartOptions = array_merge($defaultOptions, $options);
    $chartData = json_encode($data);
    $chartOptionsJson = json_encode($chartOptions);
@endphp

<div class="bg-white dark:bg-gray-900 rounded-lg shadow p-6">
    @if($title)
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ $title }}</h3>
    @endif

    <div style="height: {{ $height }};">
        <canvas id="{{ $id }}" width="400" height="200"></canvas>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('{{ $id }}');
    if (ctx) {
        try {
            const chart = new Chart(ctx, {
                type: '{{ $type }}',
                data: {!! $chartData !!},
                options: {!! $chartOptionsJson !!}
            });
            
            // Debug log
            console.log('Chart {{ $id }} created successfully:', chart);
        } catch (error) {
            console.error('Error creating chart {{ $id }}:', error);
            console.log('Chart data:', {!! $chartData !!});
            console.log('Chart options:', {!! $chartOptionsJson !!});
        }
    } else {
        console.error('Canvas element {{ $id }} not found');
    }
});
</script>