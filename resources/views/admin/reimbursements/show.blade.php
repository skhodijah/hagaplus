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
                    // - OR User is Admin (system_role_id 2)
                    // - Status is still 'pending' (not yet approved by supervisor)
                    $canApproveSupervisor = (
                        ($userRole === 'User' || ($user->employee && $reimbursement->supervisor_id === $user->employee->id) || $user->system_role_id === 2)
                        && $reimbursement->status === 'pending'
                    );
                    
                    // Can approve as HRD if:
                    // - User has 'HRD' role
                    // - OR User is Admin (system_role_id 2)
                    // - Status is 'approved_supervisor' OR (status is 'pending' AND no supervisor assigned)
                    $canApproveHRD = (
                        ($userRole === 'HRD' || $user->system_role_id === 2)
                        && ($reimbursement->status === 'approved_supervisor' || ($reimbursement->status === 'pending' && !$reimbursement->supervisor_id))
                    );
                    
                    // Can mark as paid if:
                    // - User has 'HRD' role OR is Superadmin OR Admin (system_role_id 2)
                    // - Status is 'verified_finance'
                    $canMarkAsPaid = (
                        ($userRole === 'HRD' || $user->system_role_id === 1 || $user->system_role_id === 2)
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
    <div id="rejectModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-blur-sm" aria-hidden="true" onclick="document.getElementById('rejectModal').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-gray-100 dark:border-gray-700">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fa-solid fa-triangle-exclamation text-red-600 dark:text-red-400"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">Reject Reimbursement</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                                    Are you sure you want to reject this reimbursement request? This action cannot be undone.
                                </p>
                                <form action="{{ route('admin.reimbursements.reject', $reimbursement) }}" method="POST" id="rejectForm">
                                    @csrf
                                    <div>
                                        <label for="reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Reason for Rejection <span class="text-red-500">*</span></label>
                                        <textarea id="reason" name="reason" rows="3" required
                                                  class="shadow-sm focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg"
                                                  placeholder="Please provide a reason..."></textarea>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" form="rejectForm" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        Reject Request
                    </button>
                    <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Mark as Paid Modal -->
    <div id="markAsPaidModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-blur-sm" aria-hidden="true" onclick="document.getElementById('markAsPaidModal').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-gray-100 dark:border-gray-700">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-green-100 dark:border-green-800/30">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900/50 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fa-solid fa-money-bill-wave text-green-600 dark:text-green-400"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-bold text-gray-900 dark:text-white" id="modal-title">Confirm Payment</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Are you sure you want to mark this reimbursement as <strong>PAID</strong>?
                                </p>
                                @if($reimbursement->payment_method === 'Transfer')
                                    <div class="mt-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-100 dark:border-yellow-800/30 rounded-lg p-3">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <i class="fa-solid fa-circle-info text-yellow-400"></i>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-xs text-yellow-700 dark:text-yellow-300">
                                                    Since the payment method is <strong>Transfer</strong>, you are required to upload a proof of payment.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <form action="{{ route('admin.reimbursements.mark-as-paid', $reimbursement) }}" method="POST" enctype="multipart/form-data" id="markAsPaidForm">
                    @csrf
                    <div class="px-4 py-5 sm:p-6 bg-white dark:bg-gray-800">
                        @if($reimbursement->payment_method === 'Transfer')
                            <div>
                                <label for="payment_proof_file" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Upload Payment Proof <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors cursor-pointer" onclick="document.getElementById('payment_proof_file').click()">
                                    <div class="space-y-1 text-center">
                                        <i class="fa-solid fa-cloud-arrow-up text-gray-400 text-3xl mb-2"></i>
                                        <div class="flex text-sm text-gray-600 dark:text-gray-400 justify-center">
                                            <span class="relative cursor-pointer bg-white dark:bg-transparent rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-green-500">
                                                <span>Upload a file</span>
                                                <input id="payment_proof_file" name="payment_proof_file" type="file" class="sr-only" accept=".jpg,.jpeg,.png,.pdf" required onchange="document.getElementById('file-name-display').innerText = this.files[0].name">
                                            </span>
                                            <p class="pl-1">or drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            PNG, JPG, PDF up to 2MB
                                        </p>
                                        <p id="file-name-display" class="text-sm text-green-600 font-medium mt-2"></p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fa-solid fa-check-circle text-green-500 text-4xl mb-3"></i>
                                <p class="text-gray-600 dark:text-gray-400">Ready to complete payment via <strong>{{ $reimbursement->payment_method }}</strong>.</p>
                            </div>
                        @endif
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100 dark:border-gray-700">
                        <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-base font-medium text-white hover:from-green-700 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm transition-all transform hover:scale-105">
                            Confirm Payment
                        </button>
                        <button type="button" onclick="document.getElementById('markAsPaidModal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Approve Override Modal -->
    <div id="approveOverrideModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-blur-sm" aria-hidden="true" onclick="document.getElementById('approveOverrideModal').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-gray-100 dark:border-gray-700">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900/30 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fa-solid fa-shield-halved text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">Confirm Admin Override</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Are you sure you want to override approval as <strong>User (Kepala Divisi)</strong>? This action will be logged.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form action="{{ route('admin.reimbursements.approve', $reimbursement) }}" method="POST" class="w-full sm:w-auto">
                        @csrf
                        <input type="hidden" name="level" value="supervisor">
                        <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Confirm Override
                        </button>
                    </form>
                    <button type="button" onclick="document.getElementById('approveOverrideModal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
