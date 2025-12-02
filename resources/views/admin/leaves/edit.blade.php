<x-admin-layout>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Leave Request</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Update leave request details.</p>
        </div>

        <form method="POST" action="{{ route('admin.leaves.update', $leave) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Employee -->
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Employee *</label>
                    <select name="user_id" id="user_id" required
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm">
                        <option value="">Select Employee</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ old('user_id', $leave->user_id) == $employee->id ? 'selected' : '' }}>
                                {{ $employee->name }} ({{ $employee->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Leave Type -->
                <div>
                    <label for="leave_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Leave Type *</label>
                    <select name="leave_type" id="leave_type" required
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm">
                        <option value="">Select Type</option>
                        <option value="annual" {{ old('leave_type', $leave->leave_type) === 'annual' ? 'selected' : '' }}>Annual Leave</option>
                        <option value="sick" {{ old('leave_type', $leave->leave_type) === 'sick' ? 'selected' : '' }}>Sick Leave</option>
                        <option value="maternity" {{ old('leave_type', $leave->leave_type) === 'maternity' ? 'selected' : '' }}>Maternity Leave</option>
                        <option value="emergency" {{ old('leave_type', $leave->leave_type) === 'emergency' ? 'selected' : '' }}>Emergency Leave</option>
                        <option value="other" {{ old('leave_type', $leave->leave_type) === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('leave_type')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Start Date -->
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Start Date *</label>
                    <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $leave->start_date->format('Y-m-d')) }}" required
                           class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm">
                    @error('start_date')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- End Date -->
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">End Date *</label>
                    <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $leave->end_date->format('Y-m-d')) }}" required
                           class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm">
                    @error('end_date')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status *</label>
                    <select name="status" id="status" required
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm">
                        <option value="pending" {{ old('status', $leave->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ old('status', $leave->status) === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ old('status', $leave->status) === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Rejected Reason (only show if status is rejected) -->
                <div id="rejected-reason-container" class="{{ old('status', $leave->status) === 'rejected' ? '' : 'hidden' }}">
                    <label for="rejected_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Reason for Rejection</label>
                    <input type="text" name="rejected_reason" id="rejected_reason" value="{{ old('rejected_reason', $leave->rejected_reason) }}"
                           class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                           placeholder="Reason for rejection...">
                    @error('rejected_reason')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Reason -->
            <div class="mt-6">
                <label for="reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Reason *</label>
                <textarea name="reason" id="reason" rows="4" required
                          class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                          placeholder="Please provide a detailed reason for the leave request...">{{ old('reason', $leave->reason) }}</textarea>
                @error('reason')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Attachment -->
            <div class="mt-6">
                <label for="attachment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Supporting Document (Optional)</label>
                @if($leave->attachment)
                    <div class="mb-2 flex items-center gap-2">
                        <a href="{{ asset('storage/' . $leave->attachment) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm underline">View Current Document</a>
                    </div>
                @endif
                <input type="file" name="attachment" id="attachment"
                       class="block w-full text-sm text-gray-900 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 focus:outline-none">
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Upload a new document to replace the current one. Max 2MB.</p>
                @error('attachment')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Days Count (Auto-calculated) -->
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Total Days</label>
                <div id="days-count" class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white sm:text-sm">
                    {{ $leave->days_count }} days
                </div>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Automatically calculated based on date range</p>
            </div>

            <!-- Actions -->
            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('admin.leaves.index') }}"
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-white font-bold py-2 px-4 rounded">
                    Cancel
                </a>
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Update Leave Request
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        function calculateDays() {
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            const daysCountElement = document.getElementById('days-count');

            if (startDate && endDate) {
                const start = new Date(startDate);
                const end = new Date(endDate);
                const diffTime = Math.abs(end - start);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;

                daysCountElement.textContent = diffDays + ' days';
            } else {
                daysCountElement.textContent = '0 days';
            }
        }

        function toggleRejectedReason() {
            const status = document.getElementById('status').value;
            const container = document.getElementById('rejected-reason-container');
            const input = document.getElementById('rejected_reason');

            if (status === 'rejected') {
                container.classList.remove('hidden');
                input.required = true;
            } else {
                container.classList.add('hidden');
                input.required = false;
                input.value = '';
            }
        }

        document.getElementById('start_date').addEventListener('change', calculateDays);
        document.getElementById('end_date').addEventListener('change', calculateDays);
        document.getElementById('status').addEventListener('change', toggleRejectedReason);

        // Initialize
        calculateDays();
        toggleRejectedReason();
    </script>
    @endpush
</x-admin-layout>