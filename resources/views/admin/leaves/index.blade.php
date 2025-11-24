<x-admin-layout>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Leave Management</h1>
            <a href="{{ route('admin.leaves.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Add Leave Request
            </a>
        </div>

        <!-- Filters -->
        <div class="mb-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
            <form method="GET" class="flex flex-wrap gap-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                    <select name="status" id="status" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div>
                    <label for="leave_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Leave Type</label>
                    <select name="leave_type" id="leave_type" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm">
                        <option value="">All Types</option>
                        <option value="annual" {{ request('leave_type') === 'annual' ? 'selected' : '' }}>Annual</option>
                        <option value="sick" {{ request('leave_type') === 'sick' ? 'selected' : '' }}>Sick</option>
                        <option value="maternity" {{ request('leave_type') === 'maternity' ? 'selected' : '' }}>Maternity</option>
                        <option value="emergency" {{ request('leave_type') === 'emergency' ? 'selected' : '' }}>Emergency</option>
                        <option value="other" {{ request('leave_type') === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search Employee</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Employee name..."
                           class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white dark:bg-gray-800">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700">
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Employee</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date Range</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Days</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($leaves as $leave)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="text-gray-900 dark:text-white">{{ $leave->user->name }}</div>
                                <div class="text-gray-500 dark:text-gray-400 text-xs">{{ $leave->user->email }}</div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white capitalize">
                                {{ $leave->leave_type }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $leave->start_date->format('M d, Y') }} - {{ $leave->end_date->format('M d, Y') }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $leave->days_count }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
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
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.leaves.show', $leave) }}"
                                       class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">View</a>
                                    <a href="{{ route('admin.leaves.edit', $leave) }}"
                                        class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">Edit</a>
                                    @if($leave->status === 'pending')
                                        <form method="POST" action="{{ route('admin.leaves.approve', $leave) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300"
                                                    onclick="return confirm('Are you sure you want to approve this leave request?')">Approve</button>
                                        </form>
                                        <button type="button" onclick="rejectLeave({{ $leave->id }})"
                                                class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">Reject</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $leaves->appends(request()->query())->links() }}
        </div>
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
                        <label for="rejected_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Reason for Rejection</label>
                        <textarea name="rejected_reason" id="rejected_reason" rows="3"
                                  class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                  required></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeRejectModal()"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">Cancel</button>
                        <button type="submit"
                                class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Reject</button>
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