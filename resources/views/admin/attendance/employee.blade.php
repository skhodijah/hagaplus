<x-admin-layout>
    <div class="py-2">
        <x-page-header
            title="Attendance Details - {{ $user->name }}"
            subtitle="Detailed attendance records for {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}"
        />

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $user->name }}</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $user->email }}</p>
                </div>
                <div class="flex items-center space-x-4">
                    <button id="prev-month" class="p-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>
                    <span id="current-month" class="text-lg font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}</span>
                    <button id="next-month" class="p-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>
                </div>
            </div>

            @if($attendances->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Check In</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Check Out</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Method</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Work Duration</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Late (min)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($attendances as $attendance)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $attendance->attendance_date->format('d/m/Y') }}
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $attendance->attendance_date->format('l') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        @if($attendance->check_in_time)
                                            {{ $attendance->check_in_time->format('H:i') }}
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst($attendance->check_in_method ?? 'manual') }}</div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        @if($attendance->check_out_time)
                                            {{ $attendance->check_out_time->format('H:i') }}
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst($attendance->check_out_method ?? 'manual') }}</div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ ucfirst($attendance->check_in_method ?? 'manual') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        @if($attendance->work_duration)
                                            {{ floor($attendance->work_duration / 60) }}h {{ $attendance->work_duration % 60 }}m
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $attendance->late_minutes ?? 0 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                            @if($attendance->status === 'present') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                            @elseif($attendance->status === 'late') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                            @elseif($attendance->status === 'absent') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                            @elseif($attendance->status === 'partial') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                            @elseif($attendance->status === 'leave') bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                                            @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 @endif">
                                            {{ ucfirst($attendance->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.attendance.show', $attendance) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                            View Details
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Summary Statistics -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $attendances->where('status', 'present')->count() }}</div>
                        <div class="text-sm text-green-600 dark:text-green-400">Present Days</div>
                    </div>
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $attendances->where('status', 'late')->count() }}</div>
                        <div class="text-sm text-yellow-600 dark:text-yellow-400">Late Days</div>
                    </div>
                    <div class="bg-red-50 dark:bg-red-900/20 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $attendances->where('status', 'absent')->count() }}</div>
                        <div class="text-sm text-red-600 dark:text-red-400">Absent Days</div>
                    </div>
                    <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $attendances->sum('work_duration') ? floor($attendances->sum('work_duration') / 60) . 'h ' . ($attendances->sum('work_duration') % 60) . 'm' : '0h 0m' }}</div>
                        <div class="text-sm text-blue-600 dark:text-blue-400">Total Work Time</div>
                    </div>
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fa-solid fa-calendar-xmark text-4xl text-gray-400 dark:text-gray-600 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Attendance Records</h3>
                    <p class="text-gray-500 dark:text-gray-400">No attendance records found for {{ $user->name }} in {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}</p>
                </div>
            @endif
        </div>

        <!-- Back Button -->
        <div class="mt-6">
            <a href="{{ route('admin.attendance.index', ['month' => $month]) }}"
               class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200">
                <i class="fa-solid fa-arrow-left mr-2"></i>Back to Attendance Overview
            </a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Month navigation
            const prevMonthBtn = document.getElementById('prev-month');
            const nextMonthBtn = document.getElementById('next-month');
            const currentMonthSpan = document.getElementById('current-month');

            if (prevMonthBtn && nextMonthBtn && currentMonthSpan) {
                prevMonthBtn.addEventListener('click', function() {
                    changeMonth(-1);
                });

                nextMonthBtn.addEventListener('click', function() {
                    changeMonth(1);
                });
            }

            function changeMonth(direction) {
                const currentMonth = currentMonthSpan.textContent;
                const [monthName, year] = currentMonth.split(' ');
                const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                                   'July', 'August', 'September', 'October', 'November', 'December'];
                const monthIndex = monthNames.indexOf(monthName);
                let newMonthIndex = monthIndex + direction;
                let newYear = parseInt(year);

                if (newMonthIndex < 0) {
                    newMonthIndex = 11;
                    newYear--;
                } else if (newMonthIndex > 11) {
                    newMonthIndex = 0;
                    newYear++;
                }

                const newMonth = `${newYear}-${String(newMonthIndex + 1).padStart(2, '0')}`;
                window.location.href = `{{ route('admin.attendance.employee', $user->id) }}?month=${newMonth}`;
            }
        });
    </script>
</x-admin-layout>