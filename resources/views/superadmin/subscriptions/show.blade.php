<x-superadmin-layout>
    <div class="container-fluid px-4 py-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Subscription Details</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $subscription->instansi->nama_instansi ?? 'N/A' }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('superadmin.subscriptions.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-all duration-200">
                    <i class="fa-solid fa-arrow-left mr-2"></i>Back
                </a>
                <a href="{{ route('superadmin.subscriptions.edit', $subscription) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all duration-200 shadow-sm hover:shadow">
                    <i class="fa-solid fa-edit mr-2"></i>Edit
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Subscription Info Card -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Subscription Information</h2>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Instansi</dt>
                                <dd class="text-sm font-semibold text-gray-900 dark:text-white">{{ $subscription->instansi->nama_instansi ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Package</dt>
                                <dd class="text-sm font-semibold text-gray-900 dark:text-white">{{ $subscription->package->name ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Status</dt>
                                <dd class="mt-1">
                                    @php
                                        $statusClasses = [
                                            'active' => 'bg-green-100 text-green-700 border-green-200 dark:bg-green-900/30 dark:text-green-300 dark:border-green-800',
                                            'inactive' => 'bg-gray-100 text-gray-700 border-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600',
                                            'expired' => 'bg-red-100 text-red-700 border-red-200 dark:bg-red-900/30 dark:text-red-300 dark:border-red-800',
                                            'suspended' => 'bg-yellow-100 text-yellow-700 border-yellow-200 dark:bg-yellow-900/30 dark:text-yellow-300 dark:border-yellow-800',
                                        ];
                                        $status = $subscription->current_status ?? $subscription->status;
                                    @endphp
                                    <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full border {{ $statusClasses[$status] ?? 'bg-gray-100 text-gray-700 border-gray-200' }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Price</dt>
                                <dd class="text-sm font-bold text-gray-900 dark:text-white">Rp {{ number_format($subscription->price, 0, ',', '.') }}/month</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Start Date</dt>
                                <dd class="text-sm text-gray-900 dark:text-white">{{ optional($subscription->start_date)->format('d M Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">End Date</dt>
                                <dd class="text-sm text-gray-900 dark:text-white">{{ optional($subscription->end_date)->format('d M Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Created At</dt>
                                <dd class="text-sm text-gray-900 dark:text-white">{{ $subscription->created_at->format('d M Y H:i') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Last Updated</dt>
                                <dd class="text-sm text-gray-900 dark:text-white">{{ $subscription->updated_at->format('d M Y H:i') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Package Features Card -->
                @if($subscription->package)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Package Features</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="flex items-center gap-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-100 dark:border-blue-800">
                                <div class="w-10 h-10 bg-blue-200 dark:bg-blue-700/30 rounded-lg flex items-center justify-center">
                                    <i class="fa-solid fa-users text-blue-600 dark:text-blue-400"></i>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Max Employees</div>
                                    <div class="text-lg font-bold text-gray-900 dark:text-white">{{ $subscription->package->max_employees ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 p-3 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-100 dark:border-green-800">
                                <div class="w-10 h-10 bg-green-200 dark:bg-green-700/30 rounded-lg flex items-center justify-center">
                                    <i class="fa-solid fa-building text-green-600 dark:text-green-400"></i>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Max Branches</div>
                                    <div class="text-lg font-bold text-gray-900 dark:text-white">{{ $subscription->package->max_branches ?? '-' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Actions Card -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Actions</h2>
                    </div>
                    <div class="p-6 space-y-3">
                        @if($subscription->canBeExtended() || ($subscription->current_status ?? $subscription->status) === 'expired')
                            <form method="POST" action="{{ route('superadmin.subscriptions.extend', $subscription) }}" onsubmit="return confirm('Extend subscription for 1 month?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-all duration-200 shadow-sm hover:shadow">
                                    <i class="fa-solid fa-calendar-plus mr-2"></i>Extend 1 Month
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('superadmin.subscriptions.edit', $subscription) }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all duration-200 shadow-sm hover:shadow">
                            <i class="fa-solid fa-edit mr-2"></i>Edit Subscription
                        </a>

                        <form method="POST" action="{{ route('superadmin.subscriptions.destroy', $subscription) }}" onsubmit="return confirm('Are you sure you want to delete this subscription?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-all duration-200 shadow-sm hover:shadow">
                                <i class="fa-solid fa-trash mr-2"></i>Delete Subscription
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-indigo-900/20 dark:to-indigo-800/20 rounded-xl shadow-sm border border-indigo-200 dark:border-indigo-800 p-6">
                    <h3 class="text-sm font-semibold text-indigo-900 dark:text-indigo-300 mb-4">Subscription Duration</h3>
                    @php
                        $startDate = $subscription->start_date;
                        $endDate = $subscription->end_date;
                        $daysTotal = $startDate && $endDate ? $startDate->diffInDays($endDate) : 0;
                        $daysRemaining = $endDate ? max(0, now()->diffInDays($endDate, false)) : 0;
                    @endphp
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-indigo-700 dark:text-indigo-300">Total Days</span>
                            <span class="font-bold text-indigo-900 dark:text-indigo-200">{{ $daysTotal }} days</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-indigo-700 dark:text-indigo-300">Days Remaining</span>
                            <span class="font-bold text-indigo-900 dark:text-indigo-200">{{ $daysRemaining }} days</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-superadmin-layout>
