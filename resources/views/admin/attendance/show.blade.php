<x-admin-layout>
    <div class="py-2">
        <x-page-header
            title="Attendance Details"
            subtitle="View detailed information about attendance record"
        />

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Attendance Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Attendance Information</h3>
                        <a href="{{ route('admin.attendance.edit', $attendance) }}"
                           class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition-colors duration-200">
                            <i class="fa-solid fa-edit mr-2"></i>Edit
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Employee</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $attendance->user->name }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Attendance Date</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $attendance->attendance_date->format('d/m/Y') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Check In Time</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $attendance->check_in_time ? $attendance->check_in_time->format('H:i') : 'N/A' }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Check Out Time</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $attendance->check_out_time ? $attendance->check_out_time->format('H:i') : 'N/A' }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Check In Method</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $attendance->check_in_method ?: 'N/A' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Check Out Method</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $attendance->check_out_method ?: 'N/A' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Work Duration</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $attendance->work_duration ? $attendance->work_duration . ' minutes' : 'N/A' }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Break Duration</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $attendance->break_duration ? $attendance->break_duration . ' minutes' : 'N/A' }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Overtime Duration</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $attendance->overtime_duration ? $attendance->overtime_duration . ' minutes' : 'N/A' }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Late Minutes</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $attendance->late_minutes ? $attendance->late_minutes . ' minutes' : 'N/A' }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Early Checkout Minutes</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $attendance->early_checkout_minutes ? $attendance->early_checkout_minutes . ' minutes' : 'N/A' }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                @if($attendance->status === 'present') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                @elseif($attendance->status === 'late') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                @elseif($attendance->status === 'absent') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 @endif">
                                {{ ucfirst($attendance->status) }}
                            </span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Approved By</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $attendance->approver ? $attendance->approver->name : 'N/A' }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Approved At</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $attendance->approved_at ? $attendance->approved_at->format('d/m/Y H:i') : 'N/A' }}
                            </p>
                        </div>
                    </div>

                    @if($attendance->notes)
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Notes</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $attendance->notes }}</p>
                        </div>
                    @endif

                    <!-- Selfie Photos Section -->
                    @if($attendance->check_in_photo || $attendance->check_out_photo)
                        <div class="mt-6">
                            <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Attendance Photos</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @if($attendance->check_in_photo)
                                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                        <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Check-in Photo</h5>
                                        <div class="aspect-w-4 aspect-h-3">
                                            <a href="{{ asset('storage/' . $attendance->check_in_photo) }}" 
                                               data-fancybox="attendance-photos" 
                                               data-caption="Check-in Photo @if($attendance->check_in_time) - Taken at {{ $attendance->check_in_time->format('H:i') }}@endif">
                                                <img src="{{ asset('storage/' . $attendance->check_in_photo) }}"
                                                     alt="Check-in selfie"
                                                     class="w-full h-48 object-cover rounded-lg cursor-pointer hover:opacity-90 transition-opacity">
                                            </a>
                                        </div>
                                        @if($attendance->check_in_time)
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                                Taken at {{ $attendance->check_in_time->format('H:i') }}
                                            </p>
                                        @endif
                                    </div>
                                @endif

                                @if($attendance->check_out_photo)
                                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                        <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Check-out Photo</h5>
                                        <div class="aspect-w-4 aspect-h-3">
                                            <a href="{{ asset('storage/' . $attendance->check_out_photo) }}" 
                                               data-fancybox="attendance-photos" 
                                               data-caption="Check-out Photo @if($attendance->check_out_time) - Taken at {{ $attendance->check_out_time->format('H:i') }}@endif">
                                                <img src="{{ asset('storage/' . $attendance->check_out_photo) }}"
                                                     alt="Check-out selfie"
                                                     class="w-full h-48 object-cover rounded-lg cursor-pointer hover:opacity-90 transition-opacity">
                                            </a>
                                        </div>
                                        @if($attendance->check_out_time)
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                                Taken at {{ $attendance->check_out_time->format('H:i') }}
                                            </p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Quick Actions</h3>

                    <div class="space-y-3">
                        <a href="{{ route('admin.attendance.edit', $attendance) }}"
                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition-colors duration-200">
                            <i class="fa-solid fa-edit mr-2"></i>Edit Attendance
                        </a>

                        <form method="POST" action="{{ route('admin.attendance.destroy', $attendance) }}"
                              onsubmit="return confirm('Are you sure you want to delete this attendance record? This action cannot be undone.')"
                              class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white text-sm rounded-md hover:bg-red-700 transition-colors duration-200">
                                <i class="fa-solid fa-trash mr-2"></i>Delete Attendance
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-6">
            <a href="{{ route('admin.attendance.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200">
                <i class="fa-solid fa-arrow-left mr-2"></i>Back to Attendance
            </a>
        </div>
    </div>
</x-admin-layout>