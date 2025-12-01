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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Details -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Employee Information -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Employee Information</h3>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-12 w-12">
                                @if($reimbursement->employee->foto_karyawan)
                                    <img class="h-12 w-12 rounded-full object-cover" src="{{ asset('storage/' . $reimbursement->employee->foto_karyawan) }}" alt="{{ $reimbursement->employee->user->name }}">
                                @else
                                    <div class="h-12 w-12 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                        <span class="text-gray-500 dark:text-gray-400 font-medium text-lg">{{ substr($reimbursement->employee->user->name, 0, 1) }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $reimbursement->employee->user->name }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $reimbursement->employee->employee_id }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $reimbursement->employee->division->name ?? '-' }} - {{ $reimbursement->employee->department->name ?? '-' }}
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
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Category</label>
                                <div class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">{{ $reimbursement->category }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Date of Expense</label>
                                <div class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">{{ $reimbursement->date_of_expense->format('d M Y') }}</div>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Description</label>
                                <div class="mt-1 text-sm text-gray-900 dark:text-white">{{ $reimbursement->description }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Amount Requested</label>
                                <div class="mt-1 text-lg font-bold text-gray-900 dark:text-white">
                                    {{ $reimbursement->currency }} {{ number_format($reimbursement->amount, 0, ',', '.') }}
                                </div>
                            </div>
                            @if($reimbursement->project_code)
                            <div class="md:col-span-2">
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
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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

                <!-- Payment Proof (if paid via Transfer) -->
                @if($reimbursement->payment_proof_file && $reimbursement->payment_method === 'Transfer')
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Payment Proof</h3>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-between p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800">
                            <div class="flex items-center">
                                <i class="fa-solid fa-receipt text-green-600 dark:text-green-400 text-2xl mr-3"></i>
                                <div>
                                    <span class="text-sm text-gray-900 dark:text-white font-medium block">
                                        {{ basename($reimbursement->payment_proof_file) }}
                                    </span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        Uploaded on {{ $reimbursement->paid_at ? $reimbursement->paid_at->format('d M Y, H:i') : '-' }}
                                    </span>
                                </div>
                            </div>
                            <a href="{{ asset('storage/' . $reimbursement->payment_proof_file) }}" target="_blank" 
                               class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm rounded-md transition-colors">
                                <i class="fa-solid fa-download mr-2"></i>View Proof
                            </a>
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

            <!-- Right Column: Actions & Timeline -->
            <div class="space-y-6">
                
                <!-- Action Buttons -->
                @php
                    $user = Auth::user();
                    $userRole = $user->employee && $user->employee->instansiRole ? $user->employee->instansiRole->name : null;
                    
                    // Can approve as supervisor if:
                    // - User has 'User' role OR is the assigned supervisor
                    // - Status is still 'pending' (not yet approved by supervisor)
                    $canApproveSupervisor = (
                        ($userRole === 'User' || ($user->employee && $reimbursement->supervisor_id === $user->employee->id))
                        && $reimbursement->status === 'pending'
                    );
                    
                    // Can approve as HRD if:
                    // - User has 'HRD' role
                    // - Status is 'approved_supervisor' OR (status is 'pending' AND no supervisor assigned)
                    $canApproveHRD = (
                        $userRole === 'HRD'
                        && ($reimbursement->status === 'approved_supervisor' || ($reimbursement->status === 'pending' && !$reimbursement->supervisor_id))
                    );
                    
                    // Can mark as paid if:
                    // - User has 'HRD' role OR is Superadmin
                    // - Status is 'verified_finance'
                    $canMarkAsPaid = (
                        ($userRole === 'HRD' || $user->system_role_id === 1)
                        && $reimbursement->status === 'verified_finance'
                    );
                    
                    // Superadmin override for supervisor approval (only if not yet approved)
                    $adminOverrideSupervisor = $user->system_role_id === 1 && $reimbursement->status === 'pending';
                @endphp

                @if(($canApproveSupervisor || $canApproveHRD || $canMarkAsPaid || $adminOverrideSupervisor) && $reimbursement->status !== 'rejected' && $reimbursement->status !== 'paid')
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Actions</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        @if($canApproveSupervisor)
                            <form action="{{ route('admin.reimbursements.approve', $reimbursement) }}" method="POST">
                                @csrf
                                <input type="hidden" name="level" value="supervisor">
                                <button type="submit" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors flex justify-center items-center">
                                    <i class="fa-solid fa-check mr-2"></i> Approve as User (Kepala Divisi)
                                </button>
                            </form>
                        @elseif($adminOverrideSupervisor)
                            <button type="button" onclick="document.getElementById('approveOverrideModal').classList.remove('hidden')" 
                                    class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors flex justify-center items-center">
                                <i class="fa-solid fa-shield-halved mr-2"></i> Admin Override: Approve as User
                            </button>
                        @endif

                        @if($canApproveHRD)
                            <form action="{{ route('admin.reimbursements.approve', $reimbursement) }}" method="POST">
                                @csrf
                                <input type="hidden" name="level" value="hrd">
                                <button type="submit" class="w-full px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-md transition-colors flex justify-center items-center">
                                    <i class="fa-solid fa-check-double mr-2"></i> Approve as HRD
                                </button>
                            </form>
                        @endif

                        @if($canMarkAsPaid)
                            <button type="button" onclick="document.getElementById('markAsPaidModal').classList.remove('hidden')" 
                                    class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md transition-colors flex justify-center items-center">
                                <i class="fa-solid fa-money-bill-wave mr-2"></i> Mark as Paid
                            </button>
                        @endif

                        <button type="button" onclick="document.getElementById('rejectModal').classList.remove('hidden')" 
                                class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors flex justify-center items-center">
                            <i class="fa-solid fa-times mr-2"></i> Reject Request
                        </button>
                    </div>
                </div>
                @endif


                <!-- Approval Workflow -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden h-full flex flex-col">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Approval Workflow</h3>
                    </div>
                    <div class="p-6 pb-16 flex-1">
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

                                <!-- Step 3: HRD -->
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

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Reject Reimbursement</h3>
                <form action="{{ route('admin.reimbursements.reject', $reimbursement) }}" method="POST" class="mt-2 text-left">
                    @csrf
                    <div class="mt-2">
                        <label for="reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Reason for Rejection</label>
                        <textarea id="reason" name="reason" rows="3" required
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm"
                                  placeholder="Please provide a reason for rejection..."></textarea>
                    </div>
                    <div class="mt-4 flex justify-end space-x-3">
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
    </div>

    <!-- Mark as Paid Modal -->
    <div id="markAsPaidModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900">
                    <i class="fa-solid fa-money-bill-wave text-green-600 dark:text-green-400 text-lg"></i>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mt-4 text-center">Confirm Payment</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center">
                        Are you sure you want to mark this reimbursement as <strong>PAID</strong>?
                    </p>
                    @if($reimbursement->payment_method === 'Transfer')
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 text-center">
                            Please upload payment proof.
                        </p>
                    @endif
                </div>
                <form action="{{ route('admin.reimbursements.mark-as-paid', $reimbursement) }}" method="POST" enctype="multipart/form-data" class="mt-2">
                    @csrf
                    
                    @if($reimbursement->payment_method === 'Transfer')
                        <div class="px-7 py-3">
                            <label for="payment_proof_file" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Payment Proof <span class="text-red-500">*</span>
                            </label>
                            <input type="file" 
                                   id="payment_proof_file" 
                                   name="payment_proof_file" 
                                   accept=".jpg,.jpeg,.png,.pdf"
                                   required
                                   class="block w-full text-sm text-gray-900 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 focus:outline-none">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">JPG, PNG or PDF (MAX. 2MB)</p>
                        </div>
                    @endif
                    
                    <div class="mt-4 flex justify-end space-x-3 px-7">
                        <button type="button" onclick="document.getElementById('markAsPaidModal').classList.add('hidden')"
                                class="px-4 py-2 bg-gray-300 hover:bg-gray-400 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-700 dark:text-white rounded-md transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md transition-colors">
                            Confirm Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Approve Override Modal -->
    <div id="approveOverrideModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900">
                    <i class="fa-solid fa-shield-halved text-blue-600 dark:text-blue-400 text-lg"></i>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mt-4">Confirm Admin Override</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Are you sure you want to override approval as <strong>User (Kepala Divisi)</strong>?
                    </p>
                </div>
                <form action="{{ route('admin.reimbursements.approve', $reimbursement) }}" method="POST" class="mt-2">
                    @csrf
                    <input type="hidden" name="level" value="supervisor">
                    <div class="mt-4 flex justify-end space-x-3">
                        <button type="button" onclick="document.getElementById('approveOverrideModal').classList.add('hidden')"
                                class="px-4 py-2 bg-gray-300 hover:bg-gray-400 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-700 dark:text-white rounded-md transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors">
                            Confirm Override
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
