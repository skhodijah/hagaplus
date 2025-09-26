<x-admin-layout>
    <div class="py-2">
        <x-page-header
            title="Branch Details"
            subtitle="View detailed information about {{ $branch->name }}"
        />

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Branch Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Branch Information</h3>
                        <a href="{{ route('admin.branches.edit', $branch) }}"
                           class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition-colors duration-200">
                            <i class="fa-solid fa-edit mr-2"></i>Edit
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Branch Name</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $branch->name }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($branch->is_active) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                <i class="fa-solid @if($branch->is_active) fa-check-circle @else fa-times-circle @endif mr-1"></i>
                                {{ $branch->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Timezone</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $branch->timezone }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Attendance Radius</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $branch->radius }} meters</p>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Address</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $branch->address }}</p>
                        </div>

                        @if($branch->latitude && $branch->longitude)
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Latitude</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ number_format($branch->latitude, 6) }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Longitude</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ number_format($branch->longitude, 6) }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Location Map -->
                @if($branch->latitude && $branch->longitude)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Location Map</h3>
                        <div id="map-container" class="w-full h-64 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                            <div class="text-center text-gray-500 dark:text-gray-400">
                                <i class="fa-solid fa-map-marked-alt text-3xl mb-2"></i>
                                <p>Map preview for {{ $branch->name }}</p>
                                <p class="text-xs">{{ number_format($branch->latitude, 6) }}, {{ number_format($branch->longitude, 6) }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Recent Attendance -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Recent Attendance Records</h3>

                    @if($branch->attendances->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Employee</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Check In</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Check Out</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($branch->attendances->take(10) as $attendance)
                                        <tr>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {{ $attendance->user->name }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {{ $attendance->attendance_date->format('d/m/Y') }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                    @if($attendance->status === 'present') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                    @elseif($attendance->status === 'late') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                    @elseif($attendance->status === 'absent') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                    @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 @endif">
                                                    {{ ucfirst($attendance->status) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {{ $attendance->check_in_time ? $attendance->check_in_time->format('H:i') : '-' }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {{ $attendance->check_out_time ? $attendance->check_out_time->format('H:i') : '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400 text-center py-4">No attendance records found for this branch.</p>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Quick Actions</h3>

                    <div class="space-y-3">
                        <a href="{{ route('admin.branches.edit', $branch) }}"
                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition-colors duration-200">
                            <i class="fa-solid fa-edit mr-2"></i>Edit Branch
                        </a>

                        <form method="POST" action="{{ route('admin.branches.destroy', $branch) }}"
                              onsubmit="return confirm('Are you sure you want to delete this branch? This action cannot be undone.')"
                              class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white text-sm rounded-md hover:bg-red-700 transition-colors duration-200">
                                <i class="fa-solid fa-trash mr-2"></i>Delete Branch
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Branch Statistics</h3>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Total Employees</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $branch->employees->count() }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Total Attendance Records</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $branch->attendances->count() }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Present Today</span>
                            <span class="text-sm font-medium text-green-600">{{ $branch->attendances->where('attendance_date', today())->where('status', 'present')->count() }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Late Today</span>
                            <span class="text-sm font-medium text-yellow-600">{{ $branch->attendances->where('attendance_date', today())->where('status', 'late')->count() }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Absent Today</span>
                            <span class="text-sm font-medium text-red-600">{{ $branch->attendances->where('attendance_date', today())->where('status', 'absent')->count() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Branch Status -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Branch Status</h3>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Current Status</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($branch->is_active) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                {{ $branch->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Attendance Radius</span>
                            <span class="text-sm text-gray-900 dark:text-white">{{ $branch->radius }}m</span>
                        </div>

                        @if($branch->latitude && $branch->longitude)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">GPS Location</span>
                                <span class="text-sm text-gray-900 dark:text-white">Set</span>
                            </div>
                        @else
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">GPS Location</span>
                                <span class="text-sm text-gray-500 dark:text-gray-400">Not Set</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-6">
            <a href="{{ route('admin.branches.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200">
                <i class="fa-solid fa-arrow-left mr-2"></i>Back to Branches
            </a>
        </div>
    </div>
</x-admin-layout>