<x-superadmin-layout>
    <div class="py-6 md:py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 md:mb-8">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100">Subscription Analytics</h1>
                <p class="mt-1 text-gray-600 dark:text-gray-400">Comprehensive analytics and insights for subscription management</p>
            </div>

            <!-- Key Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6 md:mb-8">
                <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                            <i class="fa-solid fa-receipt text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Subscriptions</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($totalSubscriptions) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                            <i class="fa-solid fa-check-circle text-green-600 dark:text-green-400"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Active Subscriptions</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($activeSubscriptions) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-red-100 dark:bg-red-900 rounded-lg">
                            <i class="fa-solid fa-clock text-red-600 dark:text-red-400"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Expired Subscriptions</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($expiredSubscriptions) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg">
                            <i class="fa-solid fa-flask text-purple-600 dark:text-purple-400"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Trial Subscriptions</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($trialSubscriptions) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revenue Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 mb-6 md:mb-8">
                <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Revenue</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                        </div>
                        <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                            <i class="fa-solid fa-dollar-sign text-green-600 dark:text-green-400 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Monthly Revenue</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</p>
                        </div>
                        <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                            <i class="fa-solid fa-calendar text-blue-600 dark:text-blue-400 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts and Analytics -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-6 md:mb-8">
                <!-- Package Distribution -->
                <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Package Distribution</h3>
                    <div class="space-y-3">
                        @foreach($packageDistribution as $package)
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $package['package'] }}</span>
                                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ $package['count'] }} ({{ $package['percentage'] }}%)</span>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $package['percentage'] }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Status Distribution -->
                <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Subscription Status</h3>
                    <div class="space-y-3">
                        @foreach($statusDistribution as $status)
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $status['status'] }}</span>
                                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ $status['count'] }} ({{ $status['percentage'] }}%)</span>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                        <div class="bg-green-600 h-2 rounded-full" style="width: {{ $status['percentage'] }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Monthly Growth Chart -->
            <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-6 mb-6 md:mb-8">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Monthly Subscription Growth</h3>
                <div class="h-64 flex items-end justify-between space-x-2">
                    @foreach($monthlyGrowth as $month)
                        <div class="flex-1 flex flex-col items-center">
                            <div class="w-full bg-blue-600 rounded-t" style="height: {{ $month['count'] * 10 }}px; min-height: 4px;"></div>
                            <span class="text-xs text-gray-600 dark:text-gray-400 mt-2 transform -rotate-45 origin-top-left">{{ $month['month'] }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 text-center text-sm text-gray-600 dark:text-gray-400">
                    Number of new subscriptions per month (last 12 months)
                </div>
            </div>

            <!-- Top Packages by Revenue -->
            <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Top Packages by Revenue</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Package</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Revenue</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Subscriptions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($topPackages as $package)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $package['package'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">Rp {{ number_format($package['revenue'], 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $package['subscriptions'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-superadmin-layout>