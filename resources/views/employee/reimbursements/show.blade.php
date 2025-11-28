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
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $statusClasses[$reimbursement->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $statusLabels[$reimbursement->status] ?? ucfirst(str_replace('_', ' ', $reimbursement->status)) }}
                        </span>
                    </div>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Left Column: Details -->
                    <div class="md:col-span-2 space-y-6">
                        <!-- Expense Info -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-3">Expense Details</h3>
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 grid grid-cols-2 gap-4">
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
                                    <div class="mt-1 text-lg font-bold text-gray-900 dark:text-white">{{ $reimbursement->currency }} {{ number_format($reimbursement->amount, 2) }}</div>
                                </div>
                                @if($reimbursement->approved_amount)
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Amount Approved</label>
                                    <div class="mt-1 text-lg font-bold text-green-600 dark:text-green-400">{{ $reimbursement->currency }} {{ number_format($reimbursement->approved_amount, 2) }}</div>
                                </div>
                                @endif
                                @if($reimbursement->project_code)
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Project Code</label>
                                    <div class="mt-1 text-sm text-gray-900 dark:text-white">{{ $reimbursement->project_code }}</div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Payment Info -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-3">Payment Information</h3>
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Method</label>
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

                        <!-- Proof -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-3">Proof of Transaction</h3>
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                @if($reimbursement->proof_file)
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <i class="fa-solid fa-file-invoice text-gray-400 text-2xl mr-3"></i>
                                            <span class="text-sm text-gray-600 dark:text-gray-400 truncate max-w-xs">
                                                {{ basename($reimbursement->proof_file) }}
                                            </span>
                                        </div>
                                        <a href="{{ asset('storage/' . $reimbursement->proof_file) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            View File
                                        </a>
                                    </div>
                                @else
                                    <p class="text-sm text-gray-500">No file uploaded.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Approval Timeline -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-3">Approval Workflow</h3>
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
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500 dark:text-gray-400">
                                                    <time datetime="{{ $reimbursement->created_at }}">{{ $reimbursement->created_at->format('d M') }}</time>
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
                                                    <p class="text-xs text-gray-500">{{ $reimbursement->manager ? $reimbursement->manager->user->name : 'Pending Assignment' }}</p>
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
                        
                        @if($reimbursement->status === 'rejected')
                            <div class="mt-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-red-800 dark:text-red-300 mb-2">Rejection Reason</h4>
                                <p class="text-sm text-red-700 dark:text-red-400">{{ $reimbursement->rejection_reason }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-employee-layout>
