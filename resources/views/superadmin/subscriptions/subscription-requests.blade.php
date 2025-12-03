<x-superadmin-layout>
    <div class="container-fluid px-4 py-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Subscription Requests</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage and process subscription requests and payments</p>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl shadow-sm border border-blue-200 dark:border-blue-800 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-blue-700 dark:text-blue-300 font-medium">Total Requests</p>
                        <p class="text-3xl font-bold text-blue-600 dark:text-blue-400 mt-1">
                            {{ number_format($totalPayments) }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-blue-200 dark:bg-blue-700/30 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-receipt text-blue-600 dark:text-blue-400 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl shadow-sm border border-green-200 dark:border-green-800 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-green-700 dark:text-green-300 font-medium">Processed</p>
                        <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-1">
                            {{ number_format($paidPayments) }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-green-200 dark:bg-green-700/30 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20 rounded-xl shadow-sm border border-yellow-200 dark:border-yellow-800 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-yellow-700 dark:text-yellow-300 font-medium">Pending</p>
                        <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400 mt-1">
                            {{ number_format($pendingPayments) }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-200 dark:bg-yellow-700/30 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-clock text-yellow-600 dark:text-yellow-400 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700 rounded-xl shadow-sm border border-gray-200 dark:border-gray-600 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-700 dark:text-gray-300 font-medium">Total Revenue</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                            Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-gray-200 dark:bg-gray-600 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-money-bill-wave text-gray-600 dark:text-gray-400 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                    <select name="status" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Status</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Instansi</label>
                    <select name="instansi_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Instansi</option>
                        @foreach($instansis as $instansi)
                            <option value="{{ $instansi->id }}" {{ request('instansi_id') == $instansi->id ? 'selected' : '' }}>{{ $instansi->nama_instansi }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Package</label>
                    <select name="package_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Packages</option>
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}" {{ request('package_id') == $package->id ? 'selected' : '' }}>{{ $package->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Transaction ID or Instansi..." 
                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="md:col-span-4 flex items-center justify-end gap-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all duration-200 shadow-sm hover:shadow">
                        <i class="fa-solid fa-filter mr-2"></i>Filter
                    </button>
                    <a href="{{ route('superadmin.subscriptions.subscription-requests') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-all duration-200">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Transaction ID</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Instansi</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Package Details</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($payments as $payment)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ $payment->transaction_id ?? 'N/A' }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $payment->payment_method_name ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center shadow-sm">
                                            <span class="text-white font-semibold text-sm">
                                                {{ substr($payment->nama_instansi, 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $payment->nama_instansi }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                By: {{ $payment->created_by_name ?? 'System' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($payment->target_package_name)
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $payment->package_name }}</span>
                                            <i class="fa-solid fa-arrow-right text-xs text-gray-400"></i>
                                            <span class="px-2 py-0.5 rounded text-xs font-bold bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800">
                                                {{ $payment->target_package_name }}
                                            </span>
                                        </div>
                                        <div class="text-[10px] text-indigo-600 dark:text-indigo-400 font-medium mt-0.5 uppercase tracking-wide">Upgrade</div>
                                    @elseif($payment->extension_months)
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $payment->package_name }}</span>
                                            <span class="px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                                                +{{ $payment->extension_months }} Months
                                            </span>
                                        </div>
                                        <div class="text-[10px] text-blue-600 dark:text-blue-400 font-medium mt-0.5 uppercase tracking-wide">Extension</div>
                                    @else
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $payment->package_name }}</div>
                                        <div class="text-[10px] text-green-600 dark:text-green-400 font-medium mt-0.5 uppercase tracking-wide">New Subscription</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900 dark:text-white">
                                        Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusClasses = [
                                            'paid' => 'bg-green-100 text-green-700 border-green-200 dark:bg-green-900/30 dark:text-green-300 dark:border-green-800',
                                            'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-200 dark:bg-yellow-900/30 dark:text-yellow-300 dark:border-yellow-800',
                                            'cancelled' => 'bg-red-100 text-red-700 border-red-200 dark:bg-red-900/30 dark:text-red-300 dark:border-red-800',
                                            'failed' => 'bg-red-100 text-red-700 border-red-200 dark:bg-red-900/30 dark:text-red-300 dark:border-red-800',
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full border {{ $statusClasses[$payment->payment_status] ?? 'bg-gray-100 text-gray-700 border-gray-200' }}">
                                        {{ ucfirst($payment->payment_status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                    {{ $payment->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    @if($payment->payment_status == 'pending')
                                        <a href="{{ route('superadmin.subscriptions.process-transaction', $payment->id) }}" 
                                           class="inline-flex items-center gap-1 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-all duration-200 shadow-sm hover:shadow text-xs font-medium">
                                            Process
                                            <i class="fa-solid fa-arrow-right ml-1"></i>
                                        </a>
                                    @else
                                        @if($payment->payment_proof)
                                            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
                                            <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
                                            <script>
                                                document.addEventListener("DOMContentLoaded", function() {
                                                    Fancybox.bind("[data-fancybox]", {});
                                                });
                                            </script>
                                            <a href="{{ asset('storage/' . $payment->payment_proof) }}" data-fancybox 
                                               class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium text-xs">
                                                View Proof
                                            </a>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                            <i class="fa-solid fa-inbox text-gray-400 text-2xl"></i>
                                        </div>
                                        <p class="text-gray-500 dark:text-gray-400 text-lg font-medium">No subscription requests found</p>
                                        <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Try adjusting your filters</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($payments->hasPages())
                <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                    {{ $payments->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</x-superadmin-layout>