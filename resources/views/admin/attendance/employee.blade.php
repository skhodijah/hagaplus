<x-admin-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold text-lg">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">{{ $user->name }}</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }} • {{ $user->employee->position->name ?? 'No Position' }}</p>
                </div>
            </div>
            
            <div class="flex items-center gap-3 bg-gray-50 dark:bg-gray-700/50 p-1.5 rounded-xl border border-gray-200 dark:border-gray-600">
                <button id="prev-month" class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-white dark:hover:bg-gray-600 rounded-lg transition-all shadow-sm hover:shadow-md">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <div class="px-4 py-1 font-semibold text-gray-700 dark:text-gray-200 min-w-[140px] text-center">
                    <span id="current-month">{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}</span>
                </div>
                <button id="next-month" class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-white dark:hover:bg-gray-600 rounded-lg transition-all shadow-sm hover:shadow-md">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>
        </div>

        @if($attendances->count() > 0)
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Present Days</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $attendances->where('status', 'present')->count() }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-green-50 dark:bg-green-900/30 flex items-center justify-center text-green-600 dark:text-green-400">
                        <i class="fa-solid fa-check"></i>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Late Days</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $attendances->where('status', 'late')->count() }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-yellow-50 dark:bg-yellow-900/30 flex items-center justify-center text-yellow-600 dark:text-yellow-400">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Absent Days</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $attendances->where('status', 'absent')->count() }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-red-50 dark:bg-red-900/30 flex items-center justify-center text-red-600 dark:text-red-400">
                        <i class="fa-solid fa-xmark"></i>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Work Time</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                            {{ $attendances->sum('work_duration') ? floor($attendances->sum('work_duration') / 60) . 'h ' . ($attendances->sum('work_duration') % 60) . 'm' : '0h 0m' }}
                        </p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
                        <i class="fa-solid fa-stopwatch"></i>
                    </div>
                </div>
            </div>

            <!-- Attendance Table -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-gray-500 uppercase bg-gray-50 dark:bg-gray-700/50 dark:text-gray-400">
                            <tr>
                                <th class="px-6 py-3 font-medium">Date</th>
                                <th class="px-6 py-3 font-medium">Check In</th>
                                <th class="px-6 py-3 font-medium">Check Out</th>
                                <th class="px-6 py-3 font-medium">Duration</th>
                                <th class="px-6 py-3 font-medium">Late</th>
                                <th class="px-6 py-3 font-medium">Status</th>
                                <th class="px-6 py-3 font-medium text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($attendances as $attendance)
                                <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $attendance->attendance_date->format('d M Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $attendance->attendance_date->format('l') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($attendance->check_in_time)
                                            <div class="font-medium text-gray-900 dark:text-white">{{ $attendance->check_in_time->format('H:i') }}</div>
                                            <div class="text-xs text-gray-500">{{ ucfirst($attendance->check_in_method ?? 'manual') }}</div>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($attendance->check_out_time)
                                            <div class="font-medium text-gray-900 dark:text-white">{{ $attendance->check_out_time->format('H:i') }}</div>
                                            <div class="text-xs text-gray-500">{{ ucfirst($attendance->check_out_method ?? 'manual') }}</div>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-600 dark:text-gray-300">
                                        @if($attendance->work_duration)
                                            {{ floor($attendance->work_duration / 60) }}h {{ $attendance->work_duration % 60 }}m
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($attendance->late_minutes > 0)
                                            <span class="text-red-600 font-medium">{{ $attendance->late_minutes }}m</span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($attendance->status === 'present') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                            @elseif($attendance->status === 'late') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                            @elseif($attendance->status === 'absent') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                            @elseif($attendance->status === 'partial') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                            @elseif($attendance->status === 'leave') bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400
                                            @else bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400 @endif">
                                            {{ ucfirst($attendance->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        @if($attendance->id)
                                            <a href="{{ route('admin.attendance.show', $attendance) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-300 transition-colors">
                                                <i class="fa-solid fa-chevron-right text-xs"></i>
                                            </a>
                                        @else
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-50 dark:bg-gray-800 text-gray-300 dark:text-gray-600 cursor-not-allowed">
                                                <i class="fa-solid fa-chevron-right text-xs"></i>
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-12 text-center">
                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-calendar-xmark text-2xl text-gray-400 dark:text-gray-500"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">No Attendance Records</h3>
                <p class="text-gray-500 dark:text-gray-400">No attendance records found for {{ $user->name }} in {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}</p>
            </div>
        @endif

        <div class="flex justify-start">
            <a href="{{ route('admin.attendance.index', ['month' => $month]) }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                <i class="fa-solid fa-arrow-left mr-2"></i> Back to Overview
            </a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const prevMonthBtn = document.getElementById('prev-month');
            const nextMonthBtn = document.getElementById('next-month');
            const currentMonthSpan = document.getElementById('current-month');

            if (prevMonthBtn && nextMonthBtn && currentMonthSpan) {
                prevMonthBtn.addEventListener('click', () => changeMonth(-1));
                nextMonthBtn.addEventListener('click', () => changeMonth(1));
            }

            function changeMonth(direction) {
                const currentMonth = currentMonthSpan.textContent.trim();
                const [monthName, year] = currentMonth.split(' ');
                const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                                   'July', 'August', 'September', 'October', 'November', 'December'];
                let monthIndex = monthNames.indexOf(monthName);
                let newYear = parseInt(year);
                
                let newMonthIndex = monthIndex + direction;

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