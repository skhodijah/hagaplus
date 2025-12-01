<x-employee-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('employee.reimbursements.index') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 flex items-center">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Back to List
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">New Reimbursement Request</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Fill in the details below to submit a new expense claim.</p>
                </div>

                <form action="{{ route('employee.reimbursements.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-8">
                    @csrf

                    <!-- 1. Employee Information (Auto-filled) -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 border-b pb-2">1. Employee Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Name</label>
                                <div class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">{{ $employee->user->name }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Employee ID / NIK</label>
                                <div class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">{{ $employee->employee_id }} / {{ $employee->nik ?? '-' }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Department</label>
                                <div class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">{{ $employee->department->name ?? '-' }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Position</label>
                                <div class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">{{ $employee->position->name ?? '-' }}</div>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">User (Kepala Divisi) (Approver)</label>
                                <div class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $employee->supervisor ? $employee->supervisor->user->name : 'Not Assigned' }}
                                </div>
                                @if(!$employee->supervisor)
                                    <p class="text-xs text-red-500 mt-1">Warning: You do not have a User (Kepala Divisi) assigned. Approval may be routed directly to HR.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- 2. Reimbursement Details -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 border-b pb-2">2. Expense Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category *</label>
                                <select id="category" name="category" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    <option value="">Select Category</option>
                                    <option value="Transport" {{ old('category') == 'Transport' ? 'selected' : '' }}>Transport</option>
                                    <option value="Meal" {{ old('category') == 'Meal' ? 'selected' : '' }}>Meal (Makan)</option>
                                    <option value="Health" {{ old('category') == 'Health' ? 'selected' : '' }}>Health (Kesehatan)</option>
                                    <option value="Travel" {{ old('category') == 'Travel' ? 'selected' : '' }}>Travel (Perjalanan Dinas)</option>
                                    <option value="Office Supplies" {{ old('category') == 'Office Supplies' ? 'selected' : '' }}>Office Supplies (ATK)</option>
                                    <option value="Operational" {{ old('category') == 'Operational' ? 'selected' : '' }}>Operational</option>
                                    <option value="Other" {{ old('category') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('category') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="date_of_expense" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date of Expense *</label>
                                <input type="date" id="date_of_expense" name="date_of_expense" value="{{ old('date_of_expense') }}" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                @error('date_of_expense') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description *</label>
                                <textarea id="description" name="description" rows="3" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Explain the purpose of this expense..." required>{{ old('description') }}</textarea>
                                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Amount *</label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <span class="text-gray-500 sm:text-sm">Rp</span>
                                    </div>
                                    <input type="number" name="amount" id="amount" value="{{ old('amount') }}" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white pl-10 focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="0.00" min="0" step="0.01" required>
                                </div>
                                @error('amount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="project_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Project / Cost Center (Optional)</label>
                                <input type="text" id="project_code" name="project_code" value="{{ old('project_code') }}" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="e.g. PRJ-2024-001">
                            </div>

                            <div class="md:col-span-2">
                                <label for="proof_file" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Proof of Transaction (Receipt/Invoice) *</label>
                                <input type="file" id="proof_file" name="proof_file" accept=".jpg,.jpeg,.png,.pdf" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-700 dark:file:text-gray-300" required>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Allowed formats: JPG, PNG, PDF. Max size: 2MB.</p>
                                @error('proof_file') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- 3. Payment Information -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 border-b pb-2">3. Payment Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="payment_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Reimbursement Method *</label>
                                <select id="payment_method" name="payment_method" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" required onchange="toggleBankDetails()">
                                    <option value="Transfer" {{ old('payment_method') == 'Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="Payroll" {{ old('payment_method') == 'Payroll' ? 'selected' : '' }}>Include in Payroll</option>
                                </select>
                            </div>
                        </div>

                        <div id="bank-details" class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-6 bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg transition-all duration-300">
                            <div class="md:col-span-3 mb-2">
                                <p class="text-sm font-medium text-blue-600 dark:text-blue-400">
                                    <i class="fa-solid fa-info-circle mr-1"></i> Bank details are auto-filled from your profile.
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bank Name</label>
                                <input type="text" name="bank_name" value="{{ $employee->bank_name }}" class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 dark:border-gray-600 dark:bg-gray-600 dark:text-gray-300 shadow-sm sm:text-sm cursor-not-allowed" readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Account Number</label>
                                <input type="text" name="bank_account_number" value="{{ $employee->bank_account_number }}" class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 dark:border-gray-600 dark:bg-gray-600 dark:text-gray-300 shadow-sm sm:text-sm cursor-not-allowed" readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Account Holder</label>
                                <input type="text" name="bank_account_holder" value="{{ $employee->bank_account_holder }}" class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 dark:border-gray-600 dark:bg-gray-600 dark:text-gray-300 shadow-sm sm:text-sm cursor-not-allowed" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-6 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" onclick="window.location='{{ route('employee.reimbursements.index') }}'" class="mr-3 px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Submit Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleBankDetails() {
            const method = document.getElementById('payment_method').value;
            const bankDetails = document.getElementById('bank-details');
            
            if (method === 'Transfer') {
                bankDetails.classList.remove('hidden');
            } else {
                bankDetails.classList.add('hidden');
            }
        }

        // Initialize on load
        document.addEventListener('DOMContentLoaded', function() {
            toggleBankDetails();
        });
    </script>
</x-employee-layout>
