<x-admin-layout>
    <div class="py-2">
        <x-page-header
            title="Edit Payroll Record"
            subtitle="Update payroll record details"
        />

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <form method="POST" action="{{ route('admin.payroll.update', $payroll) }}" id="payroll-form">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Information -->
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Basic Information</h3>
                    </div>

                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Employee *</label>
                        <select id="user_id" name="user_id"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('user_id') border-red-500 @enderror"
                                required>
                            <option value="">Select Employee</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ old('user_id', $payroll->user_id) == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->name }} - {{ $employee->email }}
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="period_year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Year *</label>
                            <select id="period_year" name="period_year"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('period_year') border-red-500 @enderror"
                                    required>
                                @for ($y = date('Y') + 1; $y >= 2020; $y--)
                                    <option value="{{ $y }}" {{ old('period_year', $payroll->period_year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                            @error('period_year')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="period_month" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Month *</label>
                            <select id="period_month" name="period_month"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('period_month') border-red-500 @enderror"
                                    required>
                                @for ($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ old('period_month', $payroll->period_month) == $m ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                    </option>
                                @endfor
                            </select>
                            @error('period_month')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Salary Information -->
                    <div class="md:col-span-2 mt-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Salary Information</h3>
                    </div>

                    <div>
                        <label for="basic_salary" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Basic Salary *</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400">Rp</span>
                            </div>
                            <input type="number" id="basic_salary" name="basic_salary" value="{{ old('basic_salary', $payroll->basic_salary) }}"
                                   class="w-full pl-12 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('basic_salary') border-red-500 @enderror"
                                   min="0" step="0.01" required>
                        </div>
                        @error('basic_salary')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="overtime_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Overtime Amount</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400">Rp</span>
                            </div>
                            <input type="number" id="overtime_amount" name="overtime_amount" value="{{ old('overtime_amount', $payroll->overtime_amount) }}"
                                   class="w-full pl-12 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('overtime_amount') border-red-500 @enderror"
                                   min="0" step="0.01">
                        </div>
                        @error('overtime_amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Allowances -->
                    <div class="md:col-span-2">
                        <h4 class="text-md font-medium text-gray-900 dark:text-white mb-2">Allowances</h4>
                        <div id="allowances-container">
                            @if($payroll->allowances && count($payroll->allowances) > 0)
                                @foreach($payroll->allowances as $name => $amount)
                                    <div class="allowance-item grid grid-cols-2 gap-4 mb-2">
                                        <input type="text" name="allowance_names[]" value="{{ $name }}" placeholder="Allowance name"
                                               class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 dark:text-gray-400">Rp</span>
                                            </div>
                                            <input type="number" name="allowances[]" value="{{ $amount }}" placeholder="Amount" min="0" step="0.01"
                                                   class="w-full pl-12 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white allowance-amount">
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            <div class="allowance-item grid grid-cols-2 gap-4 mb-2">
                                <input type="text" name="allowance_names[]" placeholder="Allowance name"
                                       class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 dark:text-gray-400">Rp</span>
                                    </div>
                                    <input type="number" name="allowances[]" placeholder="Amount" min="0" step="0.01"
                                           class="w-full pl-12 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white allowance-amount">
                                </div>
                            </div>
                        </div>
                        <button type="button" id="add-allowance" class="mt-2 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                            <i class="fa-solid fa-plus mr-1"></i>Add Allowance
                        </button>
                    </div>

                    <!-- Deductions -->
                    <div class="md:col-span-2">
                        <h4 class="text-md font-medium text-gray-900 dark:text-white mb-2">Deductions</h4>
                        <div id="deductions-container">
                            @if($payroll->deductions && count($payroll->deductions) > 0)
                                @foreach($payroll->deductions as $name => $amount)
                                    <div class="deduction-item grid grid-cols-2 gap-4 mb-2">
                                        <input type="text" name="deduction_names[]" value="{{ $name }}" placeholder="Deduction name"
                                               class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 dark:text-gray-400">Rp</span>
                                            </div>
                                            <input type="number" name="deductions[]" value="{{ $amount }}" placeholder="Amount" min="0" step="0.01"
                                                   class="w-full pl-12 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white deduction-amount">
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            <div class="deduction-item grid grid-cols-2 gap-4 mb-2">
                                <input type="text" name="deduction_names[]" placeholder="Deduction name"
                                       class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 dark:text-gray-400">Rp</span>
                                    </div>
                                    <input type="number" name="deductions[]" placeholder="Amount" min="0" step="0.01"
                                           class="w-full pl-12 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white deduction-amount">
                                </div>
                            </div>
                        </div>
                        <button type="button" id="add-deduction" class="mt-2 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                            <i class="fa-solid fa-plus mr-1"></i>Add Deduction
                        </button>
                    </div>

                    <!-- Payment Information -->
                    <div class="md:col-span-2 mt-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Payment Information</h3>
                    </div>

                    <div>
                        <label for="payment_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Payment Status *</label>
                        <select id="payment_status" name="payment_status"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('payment_status') border-red-500 @enderror"
                                required>
                            <option value="draft" {{ old('payment_status', $payroll->payment_status) == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="processed" {{ old('payment_status', $payroll->payment_status) == 'processed' ? 'selected' : '' }}>Processed</option>
                            <option value="paid" {{ old('payment_status', $payroll->payment_status) == 'paid' ? 'selected' : '' }}>Paid</option>
                        </select>
                        @error('payment_status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="payment_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Payment Date</label>
                        <input type="date" id="payment_date" name="payment_date" value="{{ old('payment_date', $payroll->payment_date ? $payroll->payment_date->format('Y-m-d') : '') }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('payment_date') border-red-500 @enderror">
                        @error('payment_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes</label>
                        <textarea id="notes" name="notes" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('notes') border-red-500 @enderror">{{ old('notes', $payroll->notes) }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Salary Summary -->
                <div class="mt-8 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Salary Summary</h4>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Total Gross</label>
                            <p id="total-gross" class="text-lg font-semibold text-gray-900 dark:text-white">Rp {{ number_format($payroll->total_gross, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Total Deductions</label>
                            <p id="total-deductions" class="text-lg font-semibold text-red-600 dark:text-red-400">Rp {{ number_format($payroll->total_deductions, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Net Salary</label>
                            <p id="net-salary" class="text-lg font-semibold text-green-600 dark:text-green-400">Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
                            <p id="summary-status" class="text-lg font-semibold text-gray-900 dark:text-white">{{ ucfirst($payroll->payment_status) }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex items-center justify-end space-x-4">
                    <a href="{{ route('admin.payroll.show', $payroll) }}"
                       class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                        <i class="fa-solid fa-save mr-2"></i>Update Payroll Record
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/admin/payroll.js') }}"></script>
</x-admin-layout>