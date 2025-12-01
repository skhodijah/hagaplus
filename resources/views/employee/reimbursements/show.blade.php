<x-employee-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex justify-between items-center">
                <a href="{{ route('employee.reimbursements.index') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 flex items-center">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Back to List
                </a>
                
                @if($reimbursement->status === 'pending')
                    <form action="{{ route('employee.reimbursements.destroy', $reimbursement) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this request?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Delete Request
                        </button>
                    </form>
                @endif
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <!-- Header -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Reimbursement #{{ $reimbursement->reference_number }}</h1>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Submitted on {{ $reimbursement->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="text-right">
                        @php
                            $statusClasses = [
                                'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                'approved_supervisor' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                'verified_finance' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
                                'paid' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                            ];
                            $statusLabels = [
                                'pending' => 'Pending User (Kepala Divisi)',
                                'approved_supervisor' => 'Approved by User (Kepala Divisi)',
                                'verified_finance' => 'Approved by HRD',
                                'paid' => 'Paid',
                                'rejected' => 'Rejected',
                            ];
                        @endphp
                        <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full {{ $statusClasses[$reimbursement->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $statusLabels[$reimbursement->status] ?? ucfirst(str_replace('_', ' ', $reimbursement->status)) }}
                        </span>
                    </div>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Left Column: Details -->
                    <div class="md:col-span-2 space-y-6">
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

                        <!-- Payment Proof from HRD (if paid via Transfer) -->
                        @if($reimbursement->payment_proof_file && $reimbursement->payment_method === 'Transfer' && $reimbursement->status === 'paid')
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                                    <i class="fa-solid fa-check-circle text-green-600 mr-2"></i>
                                    Payment Proof
                                </h3>
                            </div>
                            <div class="p-6">
                                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-start">
                                            <i class="fa-solid fa-receipt text-green-600 dark:text-green-400 text-2xl mr-3 mt-1"></i>
                                            <div>
                                                <p class="text-sm font-medium text-green-900 dark:text-green-100 mb-1">
                                                    Bukti Transfer dari HRD
                                                </p>
                                                <p class="text-xs text-green-700 dark:text-green-300 mb-2">
                                                    <i class="fa-solid fa-calendar mr-1"></i>
                                                    Uploaded on {{ $reimbursement->paid_at ? $reimbursement->paid_at->format('d M Y, H:i') : '-' }}
                                                </p>
                                                <p class="text-xs text-green-600 dark:text-green-400">
                                                    {{ basename($reimbursement->payment_proof_file) }}
                                                </p>
                                            </div>
                                        </div>
                                        <a href="{{ asset('storage/' . $reimbursement->payment_proof_file) }}" target="_blank" 
                                           class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm rounded-md transition-colors flex items-center">
                                            <i class="fa-solid fa-download mr-2"></i>View Proof
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Payroll Information (if paid via Payroll) -->
                        @if($reimbursement->payment_method === 'Payroll' && $reimbursement->status === 'paid')
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                                    <i class="fa-solid fa-money-check-alt text-blue-600 mr-2"></i>
                                    Payment Status
                                </h3>
                            </div>
                            <div class="p-6">
                                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                                    <div class="flex items-center">
                                        <i class="fa-solid fa-info-circle text-blue-600 dark:text-blue-400 text-2xl mr-3"></i>
                                        <div>
                                            <p class="text-sm font-medium text-blue-900 dark:text-blue-100 mb-1">
                                                Dibayarkan via Payroll
                                            </p>
                                            <p class="text-xs text-blue-700 dark:text-blue-300">
                                                Reimbursement ini akan dibayarkan bersamaan dengan gaji bulanan Anda.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

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

                    <!-- Right Column: Approval Timeline -->
                    <div class="space-y-6 h-full">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden h-full flex flex-col">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Approval Workflow</h3>
                            </div>
                            <div class="p-6 pb-10 flex-1">
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
                                                            <p class="text-sm font-medium text-gray-900 dark:text-white">User (Kepala Divisi) Approval</p>
                                                            <p class="text-xs text-gray-500">{{ $reimbursement->supervisor ? $reimbursement->supervisor->user->name : 'Pending Assignment' }}</p>
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

                                        <!-- Step 3: HRD (formerly Finance) -->
                                        <li>
                                            <div class="relative pb-8">
                                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-700" aria-hidden="true"></span>
                                                <div class="relative flex space-x-3">
                                                    <div>
                                                        <span class="h-8 w-8 rounded-full {{ $reimbursement->finance_verified_at ? 'bg-green-500' : 'bg-gray-400' }} flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                            @if($reimbursement->finance_verified_at)
                                                                <i class="fa-solid fa-check text-white text-xs"></i>
                                                            @else
                                                                <span class="text-white text-xs">3</span>
                                                            @endif
                                                        </span>
                                                    </div>
                                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                        <div>
                                                            <p class="text-sm font-medium text-gray-900 dark:text-white">HRD Approval</p>
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

                                        <!-- Step 4: Paid -->
                                        <li>
                                            <div class="relative">
                                                <div class="relative flex space-x-3">
                                                    <div>
                                                        <span class="h-8 w-8 rounded-full {{ $reimbursement->paid_at ? 'bg-green-500' : 'bg-gray-400' }} flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                            @if($reimbursement->paid_at)
                                                                <i class="fa-solid fa-check text-white text-xs"></i>
                                                            @else
                                                                <span class="text-white text-xs">4</span>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-employee-layout>
