<x-admin-layout>
    <div class="container-fluid px-4 py-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <a href="{{ route('admin.leaves.index') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm mb-2 inline-block">
                    <i class="fa-solid fa-arrow-left mr-2"></i>Back to List
                </a>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Leave Request #{{ $leave->id }}</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Submitted on {{ $leave->created_at->format('d M Y, H:i') }}</p>
            </div>
            <div class="text-right">
                @php
                    $statusClasses = [
                        'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                        'approved_supervisor' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                        'approved' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                        'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                    ];
                    $statusLabels = [
                        'pending' => 'Pending User (Kepala Divisi)',
                        'approved_supervisor' => 'Approved by User (Kepala Divisi)',
                        'approved' => 'Approved by HRD',
                        'rejected' => 'Rejected',
                    ];
                @endphp
                <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full {{ $statusClasses[$leave->status] ?? 'bg-gray-100 text-gray-800' }}">
                    {{ $statusLabels[$leave->status] ?? ucfirst($leave->status) }}
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
                                @if($leave->user->employee && $leave->user->employee->foto_karyawan)
                                    <img class="h-12 w-12 rounded-full object-cover" src="{{ asset('storage/' . $leave->user->employee->foto_karyawan) }}" alt="{{ $leave->user->name }}">
                                @else
                                    <div class="h-12 w-12 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                        <span class="text-gray-500 dark:text-gray-400 font-medium text-lg">{{ substr($leave->user->name, 0, 1) }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $leave->user->name }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $leave->user->employee->employee_id ?? '-' }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $leave->user->employee->division->name ?? '-' }} - {{ $leave->user->employee->department->name ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Leave Details -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Leave Details</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Leave Type</label>
                                <div class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">{{ ucfirst($leave->leave_type) }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Duration</label>
                                <div class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">{{ $leave->days_count }} Days</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Start Date</label>
                                <div class="mt-1 text-sm text-gray-900 dark:text-white">{{ $leave->start_date->format('d M Y') }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">End Date</label>
                                <div class="mt-1 text-sm text-gray-900 dark:text-white">{{ $leave->end_date->format('d M Y') }}</div>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Reason</label>
                                <div class="mt-1 text-sm text-gray-900 dark:text-white">{{ $leave->reason }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Attachment -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Supporting Document</h3>
                    </div>
                    <div class="p-6">
                        @if($leave->attachment)
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fa-solid fa-file-lines text-gray-400 text-2xl mr-3"></i>
                                    <span class="text-sm text-gray-600 dark:text-gray-400 truncate max-w-xs">
                                        {{ basename($leave->attachment) }}
                                    </span>
                                </div>
                                <a href="{{ asset('storage/' . $leave->attachment) }}" target="_blank" 
                                   class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-md transition-colors">
                                    <i class="fa-solid fa-download mr-2"></i>View File
                                </a>
                            </div>
                        @else
                            <p class="text-sm text-gray-500">No supporting document uploaded.</p>
                        @endif
                    </div>
                </div>

                <!-- Rejection Reason (if rejected) -->
                @if($leave->status === 'rejected' && $leave->rejected_reason)
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6">
                    <h4 class="text-sm font-medium text-red-800 dark:text-red-300 mb-2">
                        <i class="fa-solid fa-exclamation-circle mr-2"></i>Rejection Reason
                    </h4>
                    <p class="text-sm text-red-700 dark:text-red-400">{{ $leave->rejected_reason }}</p>
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
                    // - Status is still 'pending'
                    $canApproveSupervisor = (
                        ($userRole === 'User' || ($user->employee && $leave->user->employee && $leave->user->employee->supervisor_id === $user->employee->id) || $user->system_role_id === 2)
                        && $leave->status === 'pending'
                    );
                    
                    // Can approve as HRD if:
                    // - User has 'HRD' role
                    // - OR User is Admin (system_role_id 2)
                    // - Status is 'approved_supervisor' OR (status is 'pending' AND no supervisor assigned)
                    $canApproveHRD = (
                        ($userRole === 'HRD' || $user->system_role_id === 2)
                        && ($leave->status === 'approved_supervisor' || ($leave->status === 'pending' && (!$leave->user->employee || !$leave->user->employee->supervisor_id)))
                    );
                    
                    // Superadmin override for supervisor approval
                    $adminOverrideSupervisor = $user->system_role_id === 1 && $leave->status === 'pending';
                    
                    // Superadmin override for HRD approval
                    $adminOverrideHRD = $user->system_role_id === 1 && $leave->status === 'approved_supervisor';
                @endphp

                @if(($canApproveSupervisor || $canApproveHRD || $adminOverrideSupervisor || $adminOverrideHRD) && $leave->status !== 'rejected' && $leave->status !== 'approved')
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden transform transition-all hover:shadow-xl">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-white dark:from-gray-800 dark:to-gray-700">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white flex items-center">
                            <i class="fa-solid fa-bolt text-yellow-500 mr-2"></i> Actions
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        @if($canApproveSupervisor)
                            <form action="{{ route('admin.leaves.approve', $leave) }}" method="POST" class="space-y-3">
                                @csrf
                                <input type="hidden" name="level" value="supervisor">
                                <div>
                                    <label for="supervisor_note" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Note (Optional)</label>
                                    <textarea name="note" id="supervisor_note" rows="2" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm" placeholder="Add a note..."></textarea>
                                </div>
                                <button type="submit" class="w-full group relative flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-md hover:shadow-lg transition-all duration-200">
                                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                        <i class="fa-solid fa-check text-blue-100 group-hover:text-white transition-colors"></i>
                                    </span>
                                    Approve as User (Kepala Divisi)
                                </button>
                            </form>
                        @elseif($adminOverrideSupervisor)
                            <button type="button" onclick="document.getElementById('approveOverrideModal').classList.remove('hidden')" 
                                    class="w-full group relative flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-md hover:shadow-lg transition-all duration-200">
                                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                    <i class="fa-solid fa-shield-halved text-blue-100 group-hover:text-white transition-colors"></i>
                                </span>
                                Admin Override: Approve as User
                            </button>
                        @endif

                        @if($canApproveHRD)
                            <form action="{{ route('admin.leaves.approve', $leave) }}" method="POST" class="space-y-3">
                                @csrf
                                <input type="hidden" name="level" value="hrd">
                                <div>
                                    <label for="hrd_note" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Note (Optional)</label>
                                    <textarea name="note" id="hrd_note" rows="2" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm" placeholder="Add a note..."></textarea>
                                </div>
                                <button type="submit" class="w-full group relative flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-purple-600 to-purple-500 hover:from-purple-700 hover:to-purple-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 shadow-md hover:shadow-lg transition-all duration-200">
                                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                        <i class="fa-solid fa-check-double text-purple-100 group-hover:text-white transition-colors"></i>
                                    </span>
                                    Approve as HRD
                                </button>
                            </form>
                        @elseif($adminOverrideHRD)
                             <form action="{{ route('admin.leaves.approve', $leave) }}" method="POST" class="space-y-3">
                                @csrf
                                <input type="hidden" name="level" value="hrd">
                                <div>
                                    <label for="hrd_note_override" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Note (Optional)</label>
                                    <textarea name="note" id="hrd_note_override" rows="2" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm" placeholder="Add a note..."></textarea>
                                </div>
                                <button type="submit" class="w-full group relative flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-purple-600 to-purple-500 hover:from-purple-700 hover:to-purple-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 shadow-md hover:shadow-lg transition-all duration-200">
                                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                        <i class="fa-solid fa-shield-halved text-purple-100 group-hover:text-white transition-colors"></i>
                                    </span>
                                    Admin Override: Approve as HRD
                                </button>
                            </form>
                        @endif

                        <button type="button" onclick="document.getElementById('rejectModal').classList.remove('hidden')" 
                                class="w-full group relative flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-red-600 to-red-500 hover:from-red-700 hover:to-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 shadow-md hover:shadow-lg transition-all duration-200">
                            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                <i class="fa-solid fa-times text-red-100 group-hover:text-white transition-colors"></i>
                            </span>
                            Reject Request
                        </button>
                    </div>
                </div>
                @endif
                
                <!-- Approval Workflow -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden h-fit">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-white dark:from-gray-800 dark:to-gray-700">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white flex items-center">
                            <i class="fa-solid fa-timeline text-blue-500 mr-2"></i> Approval Workflow
                        </h3>
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
                                                    <p class="text-xs text-gray-400 dark:text-gray-500">{{ $leave->user->name }}</p>
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500 dark:text-gray-400">
                                                    <time>{{ $leave->created_at->format('d M') }}</time>
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
                                                <span class="h-8 w-8 rounded-full {{ $leave->supervisor_approved_at ? 'bg-green-500' : ($leave->status == 'rejected' ? 'bg-red-500' : 'bg-gray-400') }} flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                    @if($leave->supervisor_approved_at)
                                                        <i class="fa-solid fa-check text-white text-xs"></i>
                                                    @elseif($leave->status == 'rejected' && !$leave->supervisor_approved_at)
                                                        <i class="fa-solid fa-times text-white text-xs"></i>
                                                    @else
                                                        <span class="text-white text-xs">2</span>
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">User (Kepala Divisi) Approval</p>
                                                    <p class="text-xs text-gray-500">{{ $leave->supervisor ? $leave->supervisor->user->name : 'Pending Assignment' }}</p>
                                                    @if($leave->supervisor_note)
                                                        <div class="mt-2 p-2 bg-gray-50 dark:bg-gray-700 rounded text-xs text-gray-600 dark:text-gray-300">
                                                            <strong>Note:</strong> {{ $leave->supervisor_note }}
                                                        </div>
                                                    @endif
                                                </div>
                                                @if($leave->supervisor_approved_at)
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500 dark:text-gray-400">
                                                    <time>{{ $leave->supervisor_approved_at->format('d M') }}</time>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <!-- Step 3: HRD -->
                                <li>
                                    <div class="relative">
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full {{ $leave->hrd_approved_at ? 'bg-green-500' : 'bg-gray-400' }} flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                    @if($leave->hrd_approved_at)
                                                        <i class="fa-solid fa-check text-white text-xs"></i>
                                                    @else
                                                        <span class="text-white text-xs">3</span>
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">HRD Approval</p>
                                                    @if($leave->hrd)
                                                        <p class="text-xs text-gray-500">{{ $leave->hrd->name }}</p>
                                                    @endif
                                                    @if($leave->hrd_note)
                                                        <div class="mt-2 p-2 bg-gray-50 dark:bg-gray-700 rounded text-xs text-gray-600 dark:text-gray-300">
                                                            <strong>Note:</strong> {{ $leave->hrd_note }}
                                                        </div>
                                                    @endif
                                                </div>
                                                @if($leave->hrd_approved_at)
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500 dark:text-gray-400">
                                                    <time>{{ $leave->hrd_approved_at->format('d M') }}</time>
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
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Reject Leave Request</h3>
                <form action="{{ route('admin.leaves.reject', $leave) }}" method="POST" class="mt-2 text-left">
                    @csrf
                    <div class="mt-2">
                        <label for="rejected_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Reason for Rejection</label>
                        <textarea id="rejected_reason" name="rejected_reason" rows="3" required
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
                <form action="{{ route('admin.leaves.approve', $leave) }}" method="POST" class="mt-2">
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