<!-- Charts Section -->
<div class="mb-6 md:mb-8">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Analisis & Tren</h2>
        <div class="flex items-center space-x-2">
            <span class="text-sm text-gray-500 dark:text-gray-400">Periode:</span>
            <select id="chart-period" class="text-sm border border-gray-300 dark:border-gray-600 rounded-md px-3 py-1 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                <option value="7d" {{ request('period') === '7d' ? 'selected' : '' }}>7 Hari</option>
                <option value="30d" {{ request('period') === '30d' || !request('period') ? 'selected' : '' }}>30 Hari</option>
                <option value="90d" {{ request('period') === '90d' ? 'selected' : '' }}>90 Hari</option>
                <option value="1y" {{ request('period') === '1y' ? 'selected' : '' }}>1 Tahun</option>
            </select>
        </div>
    </div>

    <!-- Charts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Instansi Growth Chart -->
        <x-chart
            id="instansiGrowthChart"
            type="line"
            title="Pertumbuhan Instansi"
            height="300px"
            :data="[
                'labels' => \Carbon\CarbonPeriod::create(now()->subMonths(5), '1 month', now())
                    ->map(fn($date) => $date->format('M Y')),
                'datasets' => [
                    [
                        'label' => 'Instansi Baru',
                        'data' => collect(range(0, 5))->map(function($month) {
                            $date = now()->subMonths(5 - $month);
                            return \App\Models\SuperAdmin\Instansi::whereYear('created_at', $date->year)
                                ->whereMonth('created_at', $date->month)
                                ->count();
                        }),
                        'borderColor' => 'rgb(59, 130, 246)',
                        'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                        'tension' => 0.4,
                        'fill' => true,
                    ]
                ]
            ]"
            :options="[
                'plugins' => [
                    'legend' => ['display' => false],
                    'tooltip' => [
                        'callbacks' => [
                            'label' => 'function(context) { return context.parsed.y + " instansi baru"; }'
                        ]
                    ]
                ],
                'scales' => [
                    'y' => [
                        'beginAtZero' => true,
                        'ticks' => ['precision' => 0],
                        'title' => ['display' => true, 'text' => 'Jumlah Instansi']
                    ]
                ]
            ]"
        />

        <!-- Package Distribution Chart -->
        <x-chart
            id="packageDistributionChart"
            type="doughnut"
            title="Distribusi Paket"
            height="300px"
            :data="[
                'labels' => $packageDistribution->map(function($item) {
                    return $item->package ? $item->package->nama_paket : 'Paket Lainnya';
                }),
                'datasets' => [
                    [
                        'data' => $packageDistribution->pluck('total'),
                        'backgroundColor' => [
                            'rgb(59, 130, 246)',   // Blue
                            'rgb(16, 185, 129)',   // Green
                            'rgb(245, 158, 11)',   // Yellow
                            'rgb(239, 68, 68)',    // Red
                            'rgb(139, 92, 246)',   // Purple
                            'rgb(236, 72, 153)'    // Pink
                        ],
                        'borderWidth' => 2,
                        'borderColor' => 'rgb(255, 255, 255)',
                    ]
                ]
            ]"
            :options="[
                'plugins' => [
                    'legend' => [
                        'position' => 'bottom',
                        'labels' => ['padding' => 20]
                    ],
                    'tooltip' => [
                        'callbacks' => [
                            'label' => 'function(context) { return context.label + ": " + context.parsed + " instansi"; }'
                        ]
                    ]
                ]
            ]"
        />
    </div>

    <!-- Revenue Chart -->
    <div class="grid grid-cols-1 gap-6">
        <x-chart
            id="revenueChart"
            type="bar"
            title="Pendapatan (12 Bulan Terakhir)"
            height="350px"
            :data="[
                'labels' => collect($monthlyRevenue)->pluck('month'),
                'datasets' => [
                    [
                        'label' => 'Pendapatan Bulanan',
                        'data' => collect($monthlyRevenue)->pluck('revenue'),
                        'backgroundColor' => 'rgba(16, 185, 129, 0.8)',
                        'borderColor' => 'rgb(16, 185, 129)',
                        'borderWidth' => 1,
                    ]
                ]
            ]"
            :options="[
                'plugins' => [
                    'legend' => ['display' => false],
                    'tooltip' => [
                        'callbacks' => [
                            'label' => 'function(context) { return "Rp " + context.parsed.y.toLocaleString("id-ID"); }'
                        ]
                    ]
                ],
                'scales' => [
                    'y' => [
                        'beginAtZero' => true,
                        'ticks' => [
                            'callback' => 'function(value) { return "Rp " + (value / 1000000).toFixed(1) + " jt"; }'
                        ],
                        'title' => ['display' => true, 'text' => 'Jumlah Pendapatan']
                    ]
                ]
            ]"
        />
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart period selector
    const periodSelect = document.getElementById('chart-period');
    if (periodSelect) {
        periodSelect.addEventListener('change', function() {
            const url = new URL(window.location);
            url.searchParams.set('period', this.value);
            window.location.href = url.toString();
        });
    }
});
</script>