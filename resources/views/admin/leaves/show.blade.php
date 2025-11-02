<x-admin-layout>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Leave Request Details</h1>
            <div class="flex space-x-3">
                <a href="{{ route('admin.leaves.edit', $leave) }}"
                   class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    Edit
                </a>
                <a href="{{ route('admin.leaves.index') }}"
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-white font-bold py-2 px-4 rounded">
                    Back to List
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Employee Information -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Employee Information</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</dt>
                        <dd class="text-sm text-gray-900 dark:text-white">{{ $leave->user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                        <dd class="text-sm text-gray-900 dark:text-white">{{ $leave->user->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Role</dt>
                        <dd class="text-sm text-gray-900 dark:text-white capitalize">{{ $leave->user->role }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Leave Information -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Leave Information</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Leave Type</dt>
                        <dd class="text-sm text-gray-900 dark:text-white capitalize">{{ $leave->leave_type }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Start Date</dt>
                        <dd class="text-sm text-gray-900 dark:text-white">{{ $leave->start_date->format('l, F d, Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">End Date</dt>
                        <dd class="text-sm text-gray-900 dark:text-white">{{ $leave->end_date->format('l, F d, Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Days</dt>
                        <dd class="text-sm text-gray-900 dark:text-white">{{ $leave->days_count }} days</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                        <dd>
                            @if($leave->status === 'pending')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                    Pending
                                </span>
                            @elseif($leave->status === 'approved')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    Approved
                                </span>
                            @elseif($leave->status === 'rejected')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                    Rejected
                                </span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Reason -->
        <div class="mt-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Reason</h3>
            <p class="text-sm text-gray-900 dark:text-white whitespace-pre-wrap">{{ $leave->reason }}</p>
        </div>

        <!-- Approval Information -->
        @if($leave->status !== 'pending')
        <div class="mt-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Approval Information</h3>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Approved/Rejected By</dt>
                    <dd class="text-sm text-gray-900 dark:text-white">{{ $leave->approver ? $leave->approver->name : 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Decision Date</dt>
                    <dd class="text-sm text-gray-900 dark:text-white">{{ $leave->approved_at ? $leave->approved_at->format('l, F d, Y \a\t H:i') : 'N/A' }}</dd>
                </div>
                @if($leave->status === 'rejected' && $leave->rejected_reason)
                <div class="md:col-span-2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Reason for Rejection</dt>
                    <dd class="text-sm text-gray-900 dark:text-white mt-1">{{ $leave->rejected_reason }}</dd>
                </div>
                @endif
            </dl>
        </div>
        @endif

        <!-- Timestamps -->
        <div class="mt-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Request Timeline</h3>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Created At</dt>
                    <dd class="text-sm text-gray-900 dark:text-white">{{ $leave->created_at->format('l, F d, Y \a\t H:i') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</dt>
                    <dd class="text-sm text-gray-900 dark:text-white">{{ $leave->updated_at->format('l, F d, Y \a\t H:i') }}</dd>
                </div>
            </dl>
        </div>

        <!-- Actions for Pending Requests -->
        @if($leave->status === 'pending')
        <div class="mt-8 flex justify-center space-x-4">
            <form method="POST" action="{{ route('admin.leaves.approve', $leave) }}" class="inline">
                @csrf
                @method('PATCH')
                <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg"
                        onclick="return confirm('Are you sure you want to approve this leave request?')">
                    <i class="fa-solid fa-check mr-2"></i>Approve Request
                </button>
            </form>

            <button type="button" onclick="rejectLeave({{ $leave->id }})"
                    class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg">
                <i class="fa-solid fa-times mr-2"></i>Reject Request
            </button>
        </div>
        @endif
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Reject Leave Request</h3>
                <form id="rejectForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-4">
                        <label for="rejected_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Reason for Rejection *</label>
                        <textarea name="rejected_reason" id="rejected_reason" rows="3"
                                  class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                  required placeholder="Please provide a reason for rejecting this leave request..."></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeRejectModal()"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-white font-bold py-2 px-4 rounded">Cancel</button>
                        <button type="submit"
                                class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Reject Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function rejectLeave(leaveId) {
            document.getElementById('rejectForm').action = `/admin/leaves/${leaveId}/reject`;
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.getElementById('rejected_reason').value = '';
        }
    </script>
    @endpush
</x-admin-layout>