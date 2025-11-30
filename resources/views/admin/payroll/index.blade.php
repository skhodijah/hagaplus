<x-admin-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Payroll Management</h1>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.payroll.export', request()->query()) }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                        <i class="fa-solid fa-file-excel mr-2"></i>Export to Excel
                    </a>
                    <a href="{{ route('admin.payroll.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fa-solid fa-plus mr-2"></i>Add Payroll Record
                    </a>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <form method="GET" action="{{ route('admin.payroll.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label for="year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Year</label>
                    <select id="year" name="year" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="">All Years</option>
                        @for ($y = date('Y') + 1; $y >= 2020; $y--)
                            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label for="month" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Month</label>
                    <select id="month" name="month" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="">All Months</option>
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Payment Status</label>
                    <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="">All Status</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="processed" {{ request('status') == 'processed' ? 'selected' : '' }}>Processed</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    </select>
                </div>

                <div>
                    <label for="approval_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Approval Status</label>
                    <select id="approval_status" name="approval_status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="">All</option>
                        <option value="pending" {{ request('approval_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('approval_status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('approval_status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <div class="flex items-end space-x-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fa-solid fa-filter mr-2"></i>Filter
                    </button>
                    <a href="{{ route('admin.payroll.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                        <i class="fa-solid fa-times mr-2"></i>Clear
                    </a>
                </div>
            </form>
        </div>

        <!-- Bulk Actions -->
        <div id="bulk-actions" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 hidden">
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-700 dark:text-gray-300">
                    <span id="selected-count">0</span> item(s) selected
                </span>
                <div class="flex space-x-2">
                    @php
                        $canApprove = Auth::user()->hasRole('manager') || 
                                     (Auth::user()->hasRole('admin') && !Auth::user()->employee);
                    @endphp
                    @if($canApprove)
                    <button onclick="bulkApprove()" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fa-solid fa-check mr-2"></i>Approve Selected
                    </button>
                    <button onclick="bulkReject()" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fa-solid fa-times mr-2"></i>Reject Selected
                    </button>
                    @endif
                    <button onclick="bulkMarkAsPaid()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fa-solid fa-money-bill-wave mr-2"></i>Mark as Paid
                    </button>
                </div>
            </div>
        </div>

        <!-- Payroll Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left">
                                <input type="checkbox" id="select-all" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Employee</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Period</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Bank Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Account No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Account Holder</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Net Salary</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Payment Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Approval</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($payrolls as $payroll)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($payroll->approval_status === 'pending' || ($payroll->approval_status === 'approved' && $payroll->payment_status !== 'paid'))
                                    <input type="checkbox" class="payroll-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-4 h-4" value="{{ $payroll->id }}" data-approval="{{ $payroll->approval_status }}" data-payment="{{ $payroll->payment_status }}">
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    {{ substr($payroll->user->name, 0, 1) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $payroll->user->name }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $payroll->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ date('F Y', mktime(0, 0, 0, $payroll->period_month, 1, $payroll->period_year)) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $payroll->bank_name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $payroll->bank_account_number ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $payroll->bank_account_holder ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    Rp {{ number_format($payroll->gaji_bersih, 0, ',', '.') }}
                                </td>
                                
                                <!-- Payment Status with Action Button -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        @if($payroll->payment_status === 'paid')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                <i class="fa-solid fa-check-circle mr-1"></i>Paid
                                            </span>
                                        @elseif($payroll->payment_status === 'processed')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                <i class="fa-solid fa-cog mr-1"></i>Processed
                                            </span>
                                            @if($payroll->approval_status === 'approved')
                                            <button onclick="singleMarkAsPaid({{ $payroll->id }})" class="text-xs px-2 py-1 bg-green-600 hover:bg-green-700 text-white rounded" title="Mark as Paid">
                                                <i class="fa-solid fa-money-bill-wave"></i>
                                            </button>
                                            @endif
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                                <i class="fa-solid fa-edit mr-1"></i>Draft
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                
                                <!-- Approval Status with Action Buttons -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($payroll->approval_status === 'approved')
                                        <div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                <i class="fa-solid fa-check mr-1"></i>Approved
                                            </span>
                                            @if($payroll->approver)
                                            <div class="text-xs text-gray-500 mt-1">by {{ $payroll->approver->name }}</div>
                                            @endif
                                        </div>
                                    @elseif($payroll->approval_status === 'rejected')
                                        <div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                <i class="fa-solid fa-times mr-1"></i>Rejected
                                            </span>
                                            @if($payroll->rejection_reason)
                                            <div class="text-xs text-gray-500 mt-1">{{ $payroll->rejection_reason }}</div>
                                            @endif
                                        </div>
                                    @else
                                        <div class="flex items-center space-x-1">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                <i class="fa-solid fa-clock mr-1"></i>Pending
                                            </span>
                                            @php
                                                $canApprove = Auth::user()->hasRole('manager') || 
                                                             (Auth::user()->hasRole('admin') && !Auth::user()->employee);
                                            @endphp
                                            @if($canApprove)
                                            <button onclick="singleApprove({{ $payroll->id }})" class="text-xs px-2 py-1 bg-green-600 hover:bg-green-700 text-white rounded" title="Approve">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                            <button onclick="singleReject({{ $payroll->id }})" class="text-xs px-2 py-1 bg-red-600 hover:bg-red-700 text-white rounded" title="Reject">
                                                <i class="fa-solid fa-times"></i>
                                            </button>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.payroll.show', $payroll) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" title="View">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.payroll.print', $payroll) }}" target="_blank" class="text-purple-600 hover:text-purple-900 dark:text-purple-400 dark:hover:text-purple-300" title="Print">
                                            <i class="fa-solid fa-print"></i>
                                        </a>
                                        <a href="{{ route('admin.payroll.edit', $payroll) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300" title="Edit">
                                            <i class="fa-solid fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.payroll.destroy', $payroll) }}"
                                              onsubmit="return confirm('Are you sure you want to delete this payroll record?')"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" title="Delete">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    No payroll records found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($payrolls->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $payrolls->links() }}
                </div>
            @endif
        </div>
    </div>

    <script>
        // Select All Checkbox
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.payroll-checkbox');
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
            updateBulkActions();
        });

        // Individual Checkboxes
        document.querySelectorAll('.payroll-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', updateBulkActions);
        });

        function updateBulkActions() {
            const checkboxes = document.querySelectorAll('.payroll-checkbox:checked');
            const count = checkboxes.length;
            document.getElementById('selected-count').textContent = count;
            document.getElementById('bulk-actions').classList.toggle('hidden', count === 0);
        }

        function bulkApprove() {
            const checkboxes = document.querySelectorAll('.payroll-checkbox:checked');
            const ids = Array.from(checkboxes).map(cb => cb.value);
            
            if (ids.length === 0) {
                alert('Please select at least one payroll record');
                return;
            }

            if (!confirm(`Are you sure you want to approve ${ids.length} payroll record(s)?`)) {
                return;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('admin.payroll.bulk-approve') }}';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            ids.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'payroll_ids[]';
                input.value = id;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
        }

        function bulkReject() {
            const checkboxes = document.querySelectorAll('.payroll-checkbox:checked');
            const ids = Array.from(checkboxes).map(cb => cb.value);
            
            if (ids.length === 0) {
                alert('Please select at least one payroll record');
                return;
            }

            const reason = prompt('Please enter rejection reason:');
            if (!reason) {
                return;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('admin.payroll.bulk-reject') }}';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            const reasonInput = document.createElement('input');
            reasonInput.type = 'hidden';
            reasonInput.name = 'rejection_reason';
            reasonInput.value = reason;
            form.appendChild(reasonInput);

            ids.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'payroll_ids[]';
                input.value = id;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
        }

        function bulkMarkAsPaid() {
            const checkboxes = document.querySelectorAll('.payroll-checkbox:checked');
            const ids = Array.from(checkboxes).map(cb => cb.value);
            
            if (ids.length === 0) {
                alert('Pilih minimal satu slip gaji yang sudah approved');
                return;
            }

            if (!confirm(`Tandai ${ids.length} slip gaji sebagai DIBAYAR?`)) {
                return;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.payroll.bulk-mark-as-paid") }}';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            ids.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'payroll_ids[]';
                input.value = id;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
        }

        // Single Actions
        function singleApprove(id) {
            if (!confirm('Approve slip gaji ini?')) return;
            
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.payroll.bulk-approve") }}';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'payroll_ids[]';
            input.value = id;
            form.appendChild(input);

            document.body.appendChild(form);
            form.submit();
        }

        function singleReject(id) {
            const reason = prompt('Masukkan alasan penolakan:');
            if (!reason) return;
            
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.payroll.bulk-reject") }}';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            const reasonInput = document.createElement('input');
            reasonInput.type = 'hidden';
            reasonInput.name = 'rejection_reason';
            reasonInput.value = reason;
            form.appendChild(reasonInput);

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'payroll_ids[]';
            input.value = id;
            form.appendChild(input);

            document.body.appendChild(form);
            form.submit();
        }

        function singleMarkAsPaid(id) {
            if (!confirm('Tandai slip gaji ini sebagai DIBAYAR?')) return;
            
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.payroll.bulk-mark-as-paid") }}';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'payroll_ids[]';
            input.value = id;
            form.appendChild(input);

            document.body.appendChild(form);
            form.submit();
        }
    </script>
</x-admin-layout>