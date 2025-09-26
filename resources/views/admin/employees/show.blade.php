<x-admin-layout>
    <div class="py-2">
        <x-page-header
            title="Employee Details"
            subtitle="View detailed information about {{ $employee->user->name }}"
        />

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Employee Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Basic Information</h3>
                        <a href="{{ route('admin.employees.edit', $employee) }}"
                           class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition-colors duration-200">
                            <i class="fa-solid fa-edit mr-2"></i>Edit
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Full Name</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $employee->user->name }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Email</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $employee->user->email }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Employee ID</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $employee->employee_id }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Hire Date</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $employee->hire_date ? $employee->hire_date->format('d/m/Y') : 'N/A' }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Position</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $employee->position }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Department</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $employee->department }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Branch</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $employee->branch ? $employee->branch->name : 'N/A' }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Salary</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                Rp {{ number_format($employee->salary, 0, ',', '.') }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                @if($employee->status === 'active') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                @elseif($employee->status === 'inactive') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                {{ ucfirst($employee->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Recent Attendance -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Recent Attendance</h3>

                    @if($employee->user->attendances->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Check In</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Check Out</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($employee->user->attendances->take(10) as $attendance)
                                        <tr>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {{ $attendance->attendance_date->format('d/m/Y') }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {{ $attendance->check_in_time ? $attendance->check_in_time->format('H:i') : '-' }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {{ $attendance->check_out_time ? $attendance->check_out_time->format('H:i') : '-' }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                    @if($attendance->status === 'present') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                    @elseif($attendance->status === 'late') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                    @elseif($attendance->status === 'absent') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                    @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 @endif">
                                                    {{ ucfirst($attendance->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400 text-center py-4">No attendance records found.</p>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Quick Actions</h3>

                    <div class="space-y-3">
                        <a href="{{ route('admin.employees.edit', $employee) }}"
                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition-colors duration-200">
                            <i class="fa-solid fa-edit mr-2"></i>Edit Employee
                        </a>

                        <form method="POST" action="{{ route('admin.employees.destroy', $employee) }}"
                              onsubmit="return confirm('Are you sure you want to delete this employee? This action cannot be undone.')"
                              class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white text-sm rounded-md hover:bg-red-700 transition-colors duration-200">
                                <i class="fa-solid fa-trash mr-2"></i>Delete Employee
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Statistics</h3>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Total Attendance</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $employee->user->attendances->count() }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Present Days</span>
                            <span class="text-sm font-medium text-green-600">{{ $employee->user->attendances->where('status', 'present')->count() }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Late Days</span>
                            <span class="text-sm font-medium text-yellow-600">{{ $employee->user->attendances->where('status', 'late')->count() }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Absent Days</span>
                            <span class="text-sm font-medium text-red-600">{{ $employee->user->attendances->where('status', 'absent')->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-6">
            <a href="{{ route('admin.employees.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200">
                <i class="fa-solid fa-arrow-left mr-2"></i>Back to Employees
            </a>
        </div>
    </div>
</x-admin-layout>