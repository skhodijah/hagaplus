<x-admin-layout>
    <div class="py-2">
        <x-page-header
            title="Payroll Details"
            subtitle="View detailed information about payroll record"
        />

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Payroll Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Payroll Information</h3>
                        <a href="{{ route('admin.payroll.edit', $payroll) }}"
                           class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition-colors duration-200">
                            <i class="fa-solid fa-edit mr-2"></i>Edit
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Employee</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $payroll->user->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $payroll->user->email }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Period</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ date('F Y', mktime(0, 0, 0, $payroll->period_month, 1, $payroll->period_year)) }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Payment Status</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($payroll->payment_status === 'paid') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                @elseif($payroll->payment_status === 'processed') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                @elseif($payroll->payment_status === 'draft') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                @endif">
                                <i class="fa-solid
                                    @if($payroll->payment_status === 'paid') fa-check-circle
                                    @elseif($payroll->payment_status === 'processed') fa-cog
                                    @elseif($payroll->payment_status === 'draft') fa-edit
                                    @endif mr-1"></i>
                                {{ ucfirst($payroll->payment_status) }}
                            </span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Payment Date</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $payroll->payment_date ? $payroll->payment_date->format('d/m/Y') : 'Not set' }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Created By</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $payroll->creator ? $payroll->creator->name : 'System' }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Created At</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $payroll->created_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    </div>

                    @if($payroll->notes)
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Notes</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $payroll->notes }}</p>
                        </div>
                    @endif
                </div>

                <!-- Salary Breakdown -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Salary Breakdown</h3>

                    <div class="space-y-4">
                        <!-- Basic Salary -->
                        <div class="flex justify-between items-center py-2 border-b border-gray-200 dark:border-gray-700">
                            <span class="text-sm font-medium text-gray-900 dark:text-white">Basic Salary</span>
                            <span class="text-sm text-gray-900 dark:text-white">Rp {{ number_format($payroll->basic_salary, 0, ',', '.') }}</span>
                        </div>

                        <!-- Allowances -->
                        @if($payroll->allowances && count($payroll->allowances) > 0)
                            <div class="py-2">
                                <h4 class="text-sm font-medium text-green-600 dark:text-green-400 mb-2">Allowances</h4>
                                @foreach($payroll->allowances as $name => $amount)
                                    <div class="flex justify-between items-center ml-4 py-1">
                                        <span class="text-xs text-gray-600 dark:text-gray-400">{{ $name }}</span>
                                        <span class="text-xs text-green-600 dark:text-green-400">+ Rp {{ number_format($amount, 0, ',', '.') }}</span>
                                    </div>
                                @endforeach
                                <div class="flex justify-between items-center ml-4 py-1 border-t border-gray-200 dark:border-gray-700 mt-2 pt-2">
                                    <span class="text-sm font-medium text-green-600 dark:text-green-400">Total Allowances</span>
                                    <span class="text-sm font-medium text-green-600 dark:text-green-400">Rp {{ number_format(array_sum($payroll->allowances), 0, ',', '.') }}</span>
                                </div>
                            </div>
                        @endif

                        <!-- Overtime -->
                        @if($payroll->overtime_amount > 0)
                            <div class="flex justify-between items-center py-2 border-b border-gray-200 dark:border-gray-700">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">Overtime</span>
                                <span class="text-sm text-green-600 dark:text-green-400">+ Rp {{ number_format($payroll->overtime_amount, 0, ',', '.') }}</span>
                            </div>
                        @endif

                        <!-- Total Gross -->
                        <div class="flex justify-between items-center py-2 border-b-2 border-gray-300 dark:border-gray-600">
                            <span class="text-base font-semibold text-gray-900 dark:text-white">Total Gross</span>
                            <span class="text-base font-semibold text-gray-900 dark:text-white">Rp {{ number_format($payroll->total_gross, 0, ',', '.') }}</span>
                        </div>

                        <!-- Deductions -->
                        @if($payroll->deductions && count($payroll->deductions) > 0)
                            <div class="py-2">
                                <h4 class="text-sm font-medium text-red-600 dark:text-red-400 mb-2">Deductions</h4>
                                @foreach($payroll->deductions as $name => $amount)
                                    <div class="flex justify-between items-center ml-4 py-1">
                                        <span class="text-xs text-gray-600 dark:text-gray-400">{{ $name }}</span>
                                        <span class="text-xs text-red-600 dark:text-red-400">- Rp {{ number_format($amount, 0, ',', '.') }}</span>
                                    </div>
                                @endforeach
                                <div class="flex justify-between items-center ml-4 py-1 border-t border-gray-200 dark:border-gray-700 mt-2 pt-2">
                                    <span class="text-sm font-medium text-red-600 dark:text-red-400">Total Deductions</span>
                                    <span class="text-sm font-medium text-red-600 dark:text-red-400">Rp {{ number_format(array_sum($payroll->deductions), 0, ',', '.') }}</span>
                                </div>
                            </div>
                        @endif

                        <!-- Net Salary -->
                        <div class="flex justify-between items-center py-3 border-t-2 border-gray-400 dark:border-gray-500 bg-gray-50 dark:bg-gray-700 -mx-6 px-6 -mb-6 rounded-b-lg">
                            <span class="text-lg font-bold text-gray-900 dark:text-white">Net Salary</span>
                            <span class="text-lg font-bold text-green-600 dark:text-green-400">Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Quick Actions</h3>

                    <div class="space-y-3">
                        <a href="{{ route('admin.payroll.edit', $payroll) }}"
                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition-colors duration-200">
                            <i class="fa-solid fa-edit mr-2"></i>Edit Payroll
                        </a>

                        <form method="POST" action="{{ route('admin.payroll.destroy', $payroll) }}"
                              onsubmit="return confirm('Are you sure you want to delete this payroll record? This action cannot be undone.')"
                              class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white text-sm rounded-md hover:bg-red-700 transition-colors duration-200">
                                <i class="fa-solid fa-trash mr-2"></i>Delete Payroll
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Payment Status -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Payment Status</h3>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Current Status</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($payroll->payment_status === 'paid') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                @elseif($payroll->payment_status === 'processed') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                @elseif($payroll->payment_status === 'draft') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                @endif">
                                {{ ucfirst($payroll->payment_status) }}
                            </span>
                        </div>

                        @if($payroll->payment_date)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Payment Date</span>
                                <span class="text-sm text-gray-900 dark:text-white">{{ $payroll->payment_date->format('d/m/Y') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-6">
            <a href="{{ route('admin.payroll.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200">
                <i class="fa-solid fa-arrow-left mr-2"></i>Back to Payroll
            </a>
        </div>
    </div>
</x-admin-layout>