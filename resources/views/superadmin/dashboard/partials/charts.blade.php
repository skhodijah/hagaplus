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
        @php
            $instansiLabels = [];
            $instansiData = [];
            for ($i = 5; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $monthKey = $date->format('Y-m');
                $count = $monthlyInstansiCounts->where('ym', $monthKey)->first()->total ?? 0;
                $instansiLabels[] = $date->format('M Y');
                $instansiData[] = $count;
            }

            $instansiChartData = [
                'labels' => $instansiLabels,
                'datasets' => [
                    [
                        'label' => 'Instansi Baru',
                        'data' => $instansiData,
                        'borderColor' => 'rgb(59, 130, 246)',
                        'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                        'tension' => 0.4,
                        'fill' => true,
                    ]
                ]
            ];

            $instansiChartOptions = [
                'responsive' => true,
                'maintainAspectRatio' => false,
                'interaction' => [
                    'intersect' => false,
                    'mode' => 'index'
                ],
                'plugins' => [
                    'legend' => ['display' => false],
                    'tooltip' => [
                        'enabled' => true,
                        'backgroundColor' => 'rgba(0, 0, 0, 0.8)',
                        'titleColor' => '#fff',
                        'bodyColor' => '#fff',
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
                    ],
                    'x' => [
                        'title' => ['display' => true, 'text' => 'Bulan']
                    ]
                ]
            ];
        @endphp
        
        <x-chart
            id="instansiGrowthChart"
            type="line"
            title="Pertumbuhan Instansi"
            height="300px"
            :data="$instansiChartData"
            :options="$instansiChartOptions"
        />

        <!-- Package Distribution Chart -->
        @php
            $packageLabels = [];
            $packageData = [];
            $colors = [
                'rgb(59, 130, 246)',   // Blue
                'rgb(16, 185, 129)',   // Green
                'rgb(245, 158, 11)',   // Yellow
                'rgb(239, 68, 68)',    // Red
                'rgb(147, 51, 234)',  // Purple
                'rgb(236, 72, 153)',  // Pink
            ];

            foreach ($packageDistribution as $index => $pkg) {
                $packageLabels[] = $pkg->package->name ?? 'Unknown Package';
                $packageData[] = $pkg->total;
            }

            $packageChartData = [
                'labels' => $packageLabels,
                'datasets' => [
                    [
                        'data' => $packageData,
                        'backgroundColor' => array_slice($colors, 0, count($packageData)),
                        'borderWidth' => 2,
                        'borderColor' => 'rgb(255, 255, 255)',
                    ]
                ]
            ];

            $packageChartOptions = [
                'responsive' => true,
                'maintainAspectRatio' => false,
                'plugins' => [
                    'legend' => [
                        'position' => 'bottom',
                        'labels' => ['padding' => 20]
                    ],
                    'tooltip' => [
                        'enabled' => true,
                        'backgroundColor' => 'rgba(0, 0, 0, 0.8)',
                        'titleColor' => '#fff',
                        'bodyColor' => '#fff',
                        'callbacks' => [
                            'label' => 'function(context) { return context.label + ": " + context.parsed + " instansi"; }'
                        ]
                    ]
                ]
            ];
        @endphp
        
        <x-chart
            id="packageDistributionChart"
            type="doughnut"
            title="Distribusi Paket"
            height="300px"
            :data="$packageChartData"
            :options="$packageChartOptions"
        />
    </div>

    <!-- Revenue Chart -->
    <div class="grid grid-cols-1 gap-6">
        @php
            $revenueLabels = [];
            $revenueData = [];
            foreach ($monthlyRevenue as $revenue) {
                $revenueLabels[] = $revenue['month'];
                $revenueData[] = (float) $revenue['revenue'];
            }

            $revenueChartData = [
                'labels' => $revenueLabels,
                'datasets' => [
                    [
                        'label' => 'Pendapatan',
                        'data' => $revenueData,
                        'backgroundColor' => 'rgba(79, 70, 229, 0.7)',
                        'borderColor' => 'rgb(79, 70, 229)',
                        'borderWidth' => 1,
                        'borderRadius' => 4,
                    ]
                ]
            ];

            $revenueChartOptions = [
                'responsive' => true,
                'maintainAspectRatio' => false,
                'interaction' => [
                    'intersect' => false,
                    'mode' => 'index'
                ],
                'plugins' => [
                    'legend' => ['display' => false],
                    'tooltip' => [
                        'enabled' => true,
                        'backgroundColor' => 'rgba(0, 0, 0, 0.8)',
                        'titleColor' => '#fff',
                        'bodyColor' => '#fff',
                        'callbacks' => [
                            'label' => 'function(context) { return "Pendapatan: Rp " + context.parsed.y.toLocaleString("id-ID"); }'
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
                    ],
                    'x' => [
                        'title' => ['display' => true, 'text' => 'Bulan']
                    ]
                ]
            ];
        @endphp
        
        <x-chart
            id="revenueChart"
            type="bar"
            title="Pendapatan (12 Bulan Terakhir)"
            height="350px"
            :data="$revenueChartData"
            :options="$revenueChartOptions"
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