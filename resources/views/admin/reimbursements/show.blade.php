<x-admin-layout>
<div class="container-fluid px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <a href="{{ route('admin.reimbursements.index') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm mb-2 inline-block">
                <i class="fa-solid fa-arrow-left mr-2"></i>Back to List
            </a>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Reimbursement #{{ $reimbursement->reference_number }}</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Submitted on {{ $reimbursement->created_at->format('d M Y, H:i') }}</p>
        </div>
        <div>
            @php
                $statusClasses = [
                    'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                    'approved_supervisor' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                    'approved_manager' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200',
                    'verified_finance' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
                    'paid' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                    'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                ];
                $statusLabels = [
                    'pending' => 'Pending Supervisor',
                    'approved_supervisor' => 'Approved by Supervisor',
                    'approved_manager' => 'Approved by Manager',
                    'verified_finance' => 'Verified by Finance',
                    'paid' => 'Paid',
                    'rejected' => 'Rejected',
                ];
            @endphp
            <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full {{ $statusClasses[$reimbursement->status] ?? 'bg-gray-100 text-gray-800' }}">
                {{ $statusLabels[$reimbursement->status] ?? ucfirst(str_replace('_', ' ', $reimbursement->status)) }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Employee Information -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Employee Information</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 h-16 w-16">
                            <div class="h-16 w-16 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                <span class="text-blue-600 dark:text-blue-400 font-bold text-xl">
                                    {{ substr($reimbursement->employee->user->name, 0, 2) }}
                                </span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $reimbursement->employee->user->name }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $reimbursement->employee->employee_id }} • {{ $reimbursement->employee->position->name ?? '-' }}
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Division</label>
                            <div class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $reimbursement->employee->division->name ?? '-' }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Department</label>
                            <div class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $reimbursement->employee->department->name ?? '-' }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Supervisor</label>
                            <div class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $reimbursement->employee->supervisor->user->name ?? 'Not assigned' }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Manager</label>
                            <div class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $reimbursement->employee->manager->user->name ?? 'Not assigned' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Expense Details -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Expense Details</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Category</label>
                            <div class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">{{ $reimbursement->category }}</div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Date of Expense</label>
                            <div class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">{{ $reimbursement->date_of_expense->format('d M Y') }}</div>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Description</label>
                            <div class="mt-1 text-sm text-gray-900 dark:text-white">{{ $reimbursement->description }}</div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Amount Requested</label>
                            <div class="mt-1 text-lg font-bold text-gray-900 dark:text-white">
                                {{ $reimbursement->currency }} {{ number_format($reimbursement->amount, 0, ',', '.') }}
                            </div>
                        </div>
                        @if($reimbursement->approved_amount)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Amount Approved</label>
                            <div class="mt-1 text-lg font-bold text-green-600 dark:text-green-400">
                                {{ $reimbursement->currency }} {{ number_format($reimbursement->approved_amount, 0, ',', '.') }}
                            </div>
                        </div>
                        @endif
                        @if($reimbursement->project_code)
                        <div class="col-span-2">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Project Code</label>
                            <div class="mt-1 text-sm text-gray-900 dark:text-white">{{ $reimbursement->project_code }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Payment Information</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Payment Method</label>
                            <div class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">{{ $reimbursement->payment_method }}</div>
                        </div>
                        @if($reimbursement->payment_method === 'Transfer')
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Bank Name</label>
                            <div class="mt-1 text-sm text-gray-900 dark:text-white">{{ $reimbursement->bank_name }}</div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Account Number</label>
                            <div class="mt-1 text-sm text-gray-900 dark:text-white">{{ $reimbursement->bank_account_number }}</div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Account Holder</label>
                            <div class="mt-1 text-sm text-gray-900 dark:text-white">{{ $reimbursement->bank_account_holder }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Proof of Transaction -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Proof of Transaction</h3>
                </div>
                <div class="p-6">
                    @if($reimbursement->proof_file)
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex items-center">
                                <i class="fa-solid fa-file-invoice text-gray-400 text-2xl mr-3"></i>
                                <span class="text-sm text-gray-600 dark:text-gray-400 truncate max-w-xs">
                                    {{ basename($reimbursement->proof_file) }}
                                </span>
                            </div>
                            <a href="{{ asset('storage/' . $reimbursement->proof_file) }}" target="_blank" 
                               class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-md transition-colors">
                                <i class="fa-solid fa-download mr-2"></i>View File
                            </a>
                        </div>
                    @else
                        <p class="text-sm text-gray-500">No file uploaded.</p>
                    @endif
                </div>
            </div>

            <!-- Rejection Reason (if rejected) -->
            @if($reimbursement->status === 'rejected' && $reimbursement->rejection_reason)
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6">
                <h4 class="text-sm font-medium text-red-800 dark:text-red-300 mb-2">
                    <i class="fa-solid fa-exclamation-circle mr-2"></i>Rejection Reason
                </h4>
                <p class="text-sm text-red-700 dark:text-red-400">{{ $reimbursement->rejection_reason }}</p>
            </div>
            @endif
        </div>

        <!-- Right Column: Approval Timeline & Actions -->
        <div class="space-y-6">
            <!-- Approval Workflow -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Approval Workflow</h3>
                </div>
                <div class="p-6">
                    <div class="flow-root">
                        <ul role="list" class="-mb-8">
                            <!-- Step 1: Submitted -->
                            <li>
                                <div class="relative pb-8">
                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-700" aria-hidden="true"></span>
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                <i class="fa-solid fa-check text-white text-xs"></i>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">Request Submitted</p>
                                                <p class="text-xs text-gray-400 dark:text-gray-500">{{ $reimbursement->employee->user->name }}</p>
                                            </div>
                                            <div class="text-right text-sm whitespace-nowrap text-gray-500 dark:text-gray-400">
                                                <time>{{ $reimbursement->created_at->format('d M') }}</time>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <!-- Step 2: Supervisor -->
                            <li>
                                <div class="relative pb-8">
                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-700" aria-hidden="true"></span>
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full {{ $reimbursement->supervisor_approved_at ? 'bg-green-500' : ($reimbursement->status == 'rejected' ? 'bg-red-500' : 'bg-gray-400') }} flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                @if($reimbursement->supervisor_approved_at)
                                                    <i class="fa-solid fa-check text-white text-xs"></i>
                                                @elseif($reimbursement->status == 'rejected' && !$reimbursement->supervisor_approved_at)
                                                    <i class="fa-solid fa-times text-white text-xs"></i>
                                                @else
                                                    <span class="text-white text-xs">2</span>
                                                @endif
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">Supervisor Approval</p>
                                                <p class="text-xs text-gray-500">{{ $reimbursement->supervisor->user->name ?? 'Pending Assignment' }}</p>
                                            </div>
                                            @if($reimbursement->supervisor_approved_at)
                                            <div class="text-right text-sm whitespace-nowrap text-gray-500 dark:text-gray-400">
                                                <time>{{ $reimbursement->supervisor_approved_at->format('d M') }}</time>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <!-- Step 3: Manager -->
                            <li>
                                <div class="relative pb-8">
                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-700" aria-hidden="true"></span>
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full {{ $reimbursement->manager_approved_at ? 'bg-green-500' : ($reimbursement->status == 'rejected' && $reimbursement->supervisor_approved_at ? 'bg-red-500' : 'bg-gray-400') }} flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                @if($reimbursement->manager_approved_at)
                                                    <i class="fa-solid fa-check text-white text-xs"></i>
                                                @elseif($reimbursement->status == 'rejected' && $reimbursement->supervisor_approved_at && !$reimbursement->manager_approved_at)
                                                    <i class="fa-solid fa-times text-white text-xs"></i>
                                                @else
                                                    <span class="text-white text-xs">3</span>
                                                @endif
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">Manager Approval</p>
                                                <p class="text-xs text-gray-500">{{ $reimbursement->manager->user->name ?? 'Pending Assignment' }}</p>
                                            </div>
                                            @if($reimbursement->manager_approved_at)
                                            <div class="text-right text-sm whitespace-nowrap text-gray-500 dark:text-gray-400">
                                                <time>{{ $reimbursement->manager_approved_at->format('d M') }}</time>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <!-- Step 4: Finance -->
                            <li>
                                <div class="relative pb-8">
                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-700" aria-hidden="true"></span>
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full {{ $reimbursement->finance_verified_at ? 'bg-green-500' : 'bg-gray-400' }} flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                @if($reimbursement->finance_verified_at)
                                                    <i class="fa-solid fa-check text-white text-xs"></i>
                                                @else
                                                    <span class="text-white text-xs">4</span>
                                                @endif
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">Finance Verification</p>
                                                @if($reimbursement->financeApprover)
                                                    <p class="text-xs text-gray-500">{{ $reimbursement->financeApprover->name }}</p>
                                                @endif
                                            </div>
                                            @if($reimbursement->finance_verified_at)
                                            <div class="text-right text-sm whitespace-nowrap text-gray-500 dark:text-gray-400">
                                                <time>{{ $reimbursement->finance_verified_at->format('d M') }}</time>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <!-- Step 5: Paid -->
                            <li>
                                <div class="relative">
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full {{ $reimbursement->paid_at ? 'bg-green-500' : 'bg-gray-400' }} flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                @if($reimbursement->paid_at)
                                                    <i class="fa-solid fa-check text-white text-xs"></i>
                                                @else
                                                    <span class="text-white text-xs">5</span>
                                                @endif
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">Payment Processing</p>
                                            </div>
                                            @if($reimbursement->paid_at)
                                            <div class="text-right text-sm whitespace-nowrap text-gray-500 dark:text-gray-400">
                                                <time>{{ $reimbursement->paid_at->format('d M') }}</time>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            @if($reimbursement->status !== 'rejected' && $reimbursement->status !== 'paid')
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Take Action</h3>
                </div>
                <div class="p-6 space-y-3">
                    <form method="POST" action="{{ route('admin.reimbursements.approve', $reimbursement) }}">
                        @csrf
                        <input type="hidden" name="level" value="{{ $reimbursement->status === 'pending' ? 'supervisor' : ($reimbursement->status === 'approved_supervisor' ? 'manager' : 'finance') }}">
                        <button type="submit" class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md transition-colors flex items-center justify-center">
                            <i class="fa-solid fa-check mr-2"></i>
                            Approve
                        </button>
                    </form>

                    <button type="button" onclick="document.getElementById('rejectModal').classList.remove('hidden')" 
                            class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors flex items-center justify-center">
                        <i class="fa-solid fa-times mr-2"></i>
                        Reject
                    </button>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Reject Reimbursement</h3>
        <form method="POST" action="{{ route('admin.reimbursements.reject', $reimbursement) }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rejection Reason</label>
                <textarea name="reason" rows="4" required
                          class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500 focus:border-red-500"
                          placeholder="Please provide a reason for rejection..."></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-300 hover:bg-gray-400 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-700 dark:text-white rounded-md transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors">
                    Reject Request
                </button>
            </div>
        </form>
    </div>
</div>
</x-admin-layout>
