<x-superadmin-layout>
    <div class="container-fluid px-4 py-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Subscription Management</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Kelola semua subscription instansi</p>
            </div>
            <a href="{{ route('superadmin.subscriptions.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all duration-200 shadow-sm hover:shadow">
                <i class="fa-solid fa-plus mr-2"></i>Buat Subscription Baru
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            @php
                $activeCount = $subscriptions->where('status', 'active')->count();
                $expiredCount = $subscriptions->where('status', 'expired')->count();
                $inactiveCount = $subscriptions->where('status', 'inactive')->count();
                $totalRevenue = $subscriptions->where('status', 'active')->sum('price');
            @endphp
            <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl shadow-sm border border-green-200 dark:border-green-800 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-green-700 dark:text-green-300 font-medium">Active</p>
                        <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-1">{{ $activeCount }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-200 dark:bg-green-700/30 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-xl shadow-sm border border-red-200 dark:border-red-800 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-red-700 dark:text-red-300 font-medium">Expired</p>
                        <p class="text-3xl font-bold text-red-600 dark:text-red-400 mt-1">{{ $expiredCount }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-200 dark:bg-red-700/30 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-times-circle text-red-600 dark:text-red-400 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700 rounded-xl shadow-sm border border-gray-200 dark:border-gray-600 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-700 dark:text-gray-300 font-medium">Inactive</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $inactiveCount }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gray-200 dark:bg-gray-600 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-pause-circle text-gray-600 dark:text-gray-400 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl shadow-sm border border-blue-200 dark:border-blue-800 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-blue-700 dark:text-blue-300 font-medium">Monthly Revenue</p>
                        <p class="text-2xl font-bold text-blue-600 dark:text-blue-400 mt-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-200 dark:bg-blue-700/30 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-money-bill-wave text-blue-600 dark:text-blue-400 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Instansi</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Package</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Period</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($subscriptions as $subscription)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center shadow-sm">
                                            <span class="text-white font-semibold text-sm">
                                                {{ substr($subscription->instansi->nama_instansi ?? 'N', 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $subscription->instansi->nama_instansi ?? '-' }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                ID: {{ $subscription->id }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $subscription->package->name ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusClasses = [
                                            'active' => 'bg-green-100 text-green-700 border-green-200 dark:bg-green-900/30 dark:text-green-300 dark:border-green-800',
                                            'inactive' => 'bg-gray-100 text-gray-700 border-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600',
                                            'expired' => 'bg-red-100 text-red-700 border-red-200 dark:bg-red-900/30 dark:text-red-300 dark:border-red-800',
                                            'cancelled' => 'bg-orange-100 text-orange-700 border-orange-200 dark:bg-orange-900/30 dark:text-orange-300 dark:border-orange-800',
                                            'pending_verification' => 'bg-yellow-100 text-yellow-700 border-yellow-200 dark:bg-yellow-900/30 dark:text-yellow-300 dark:border-yellow-800',
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full border {{ $statusClasses[$subscription->status] ?? 'bg-gray-100 text-gray-700 border-gray-200' }}">
                                        {{ ucfirst($subscription->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                    <div>{{ $subscription->start_date->format('d M Y') }}</div>
                                    <div class="text-xs">to {{ $subscription->end_date->format('d M Y') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white">
                                    Rp {{ number_format($subscription->price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    <a href="{{ route('superadmin.subscriptions.show', $subscription) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                                        View
                                    </a>
                                    <a href="{{ route('superadmin.subscriptions.edit', $subscription) }}" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium">
                                        Edit
                                    </a>
                                    @if($subscription->canBeExtended())
                                        <form method="POST" action="{{ route('superadmin.subscriptions.extend', $subscription) }}" class="inline" onsubmit="return confirm('Extend subscription for 1 month?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 font-medium">
                                                Extend
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                            <i class="fa-solid fa-receipt text-gray-400 text-2xl"></i>
                                        </div>
                                        <p class="text-gray-500 dark:text-gray-400 text-lg font-medium">No subscriptions found</p>
                                        <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Create your first subscription to get started</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-superadmin-layout>
