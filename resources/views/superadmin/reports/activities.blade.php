<x-superadmin-layout>
    <div class="py-6 md:py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 md:mb-8">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100">Activity Reports</h1>
                <p class="mt-1 text-gray-600 dark:text-gray-400">Monitor and track all system activities and user interactions</p>
            </div>

            <!-- Period Filter -->
            <div class="mb-6">
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('superadmin.reports.activities', ['period' => '1d']) }}"
                       class="px-4 py-2 text-sm font-medium rounded-md {{ $period === '1d' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'bg-white text-gray-700 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700' }} border border-gray-300 dark:border-gray-600">
                        Last 24 Hours
                    </a>
                    <a href="{{ route('superadmin.reports.activities', ['period' => '7d']) }}"
                       class="px-4 py-2 text-sm font-medium rounded-md {{ $period === '7d' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'bg-white text-gray-700 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700' }} border border-gray-300 dark:border-gray-600">
                        Last 7 Days
                    </a>
                    <a href="{{ route('superadmin.reports.activities', ['period' => '30d']) }}"
                       class="px-4 py-2 text-sm font-medium rounded-md {{ $period === '30d' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'bg-white text-gray-700 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700' }} border border-gray-300 dark:border-gray-600">
                        Last 30 Days
                    </a>
                    <a href="{{ route('superadmin.reports.activities', ['period' => '90d']) }}"
                       class="px-4 py-2 text-sm font-medium rounded-md {{ $period === '90d' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'bg-white text-gray-700 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700' }} border border-gray-300 dark:border-gray-600">
                        Last 90 Days
                    </a>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6 md:mb-8">
                <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                            <i class="fa-solid fa-chart-line text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Activities</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($totalActivities) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                            <i class="fa-solid fa-users text-green-600 dark:text-green-400"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">User Activities</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($activityCounts['users']) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg">
                            <i class="fa-solid fa-credit-card text-purple-600 dark:text-purple-400"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Payment Activities</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($activityCounts['payments']) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-orange-100 dark:bg-orange-900 rounded-lg">
                            <i class="fa-solid fa-headset text-orange-600 dark:text-orange-400"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Support Activities</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($activityCounts['support']) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Breakdown -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6 md:mb-8">
                <!-- Activity Types Breakdown -->
                <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Activity Breakdown</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Users</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ number_format($activityCounts['users']) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Subscriptions</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ number_format($activityCounts['subscriptions']) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Payments</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ number_format($activityCounts['payments']) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Support Requests</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ number_format($activityCounts['support']) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Instansis</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ number_format($activityCounts['instansis']) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Employees</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ number_format($activityCounts['employees']) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Activity Trend Chart -->
                <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-6 lg:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Activity Trend (Last 7 Days)</h3>
                    <div class="h-64">
                        <canvas id="activityTrendChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="bg-white dark:bg-gray-900 rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Recent Activities</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Latest system activities and user interactions</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Activity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date & Time</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($recentActivities as $activity)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                @switch($activity->entity_type)
                                                    @case('user')
                                                        <i class="fa-solid fa-user text-blue-500"></i>
                                                        @break
                                                    @case('subscription')
                                                        <i class="fa-solid fa-crown text-purple-500"></i>
                                                        @break
                                                    @case('payment')
                                                        <i class="fa-solid fa-credit-card text-green-500"></i>
                                                        @break
                                                    @case('support')
                                                        <i class="fa-solid fa-headset text-orange-500"></i>
                                                        @break
                                                    @case('instansi')
                                                        <i class="fa-solid fa-building text-indigo-500"></i>
                                                        @break
                                                    @case('employee')
                                                        <i class="fa-solid fa-user-tie text-teal-500"></i>
                                                        @break
                                                    @default
                                                        <i class="fa-solid fa-circle text-gray-500"></i>
                                                @endswitch
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $activity->activity_type }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                            @switch($activity->entity_type)
                                                @case('user') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                @case('subscription') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                                                @case('payment') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                @case('support') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                                                @case('instansi') bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200
                                                @case('employee') bg-teal-100 text-teal-800 dark:bg-teal-900 dark:text-teal-200
                                                @default bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                                            @endswitch">
                                            {{ ucfirst($activity->entity_type) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        @if(strpos($activity->description, 'Payment of Rp') === 0)
                                            @php
                                                // Format payment amounts properly
                                                $description = $activity->description;
                                                preg_match('/Payment of Rp (\d+) for/', $description, $matches);
                                                if(isset($matches[1])) {
                                                    $amount = number_format($matches[1], 0, ',', '.');
                                                    $description = str_replace('Payment of Rp ' . $matches[1], 'Payment of Rp ' . $amount, $description);
                                                }
                                            @endphp
                                            {{ $description }}
                                        @else
                                            {{ $activity->description }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                            @switch($activity->status)
                                                @case('active') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                @case('inactive') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                @case('pending') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                @case('paid') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                @case('cancelled') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                @case('expired') bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                                                @case('resolved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                @case('open') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                @case('in_progress') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                @case('closed') bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                                                @case('success') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                @case('info') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                @default bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                                            @endswitch">
                                            {{ ucfirst(str_replace('_', ' ', $activity->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ \Carbon\Carbon::parse($activity->activity_date)->format('d M Y H:i') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        No activities found for the selected period.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($recentActivities->count() >= 50)
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        <p class="text-sm text-gray-600 dark:text-gray-400 text-center">
                            Showing the 50 most recent activities. Use filters to see more specific data.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Activity Trend Chart
            const activityTrendData = @json($activityTrends);
            const ctx = document.getElementById('activityTrendChart').getContext('2d');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: activityTrendData.map(item => item.date),
                    datasets: [{
                        label: 'Activities',
                        data: activityTrendData.map(item => item.count),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-superadmin-layout>