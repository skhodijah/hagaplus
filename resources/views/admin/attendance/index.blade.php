<x-admin-layout>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Attendance Management</h1>
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

        <!-- Legend -->
        <div class="mb-4 flex flex-wrap gap-4 text-sm">
            <div class="flex items-center">
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 mr-2">✓</span>
                <span class="text-gray-700 dark:text-gray-300">Present</span>
            </div>
            <div class="flex items-center">
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 mr-2">L</span>
                <span class="text-gray-700 dark:text-gray-300">Late</span>
            </div>
            <div class="flex items-center">
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 mr-2">✗</span>
                <span class="text-gray-700 dark:text-gray-300">Absent</span>
            </div>
            <div class="flex items-center">
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 mr-2">◐</span>
                <span class="text-gray-700 dark:text-gray-300">Partial</span>
            </div>
            <div class="flex items-center">
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 mr-2">C</span>
                <span class="text-gray-700 dark:text-gray-300">Leave</span>
            </div>
            <div class="flex items-center">
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-50 text-gray-400 dark:bg-gray-800 dark:text-gray-600 mr-2">-</span>
                <span class="text-gray-700 dark:text-gray-300">No Record</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white dark:bg-gray-800">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700">
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Employee</th>
                        @for ($day = 1; $day <= \Carbon\Carbon::createFromFormat('Y-m', $month)->daysInMonth; $day++)
                            <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ $day }}</th>
                        @endfor
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($employees as $employee)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.attendance.employee', [$employee->id, 'month' => $month]) }}"
                                   class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                    {{ $employee->name }}
                                </a>
                            </td>
                            @for ($day = 1; $day <= \Carbon\Carbon::createFromFormat('Y-m', $month)->daysInMonth; $day++)
                                @php
                                    $date = \Carbon\Carbon::createFromFormat('Y-m', $month)->setDay($day)->format('Y-m-d');
                                    $attendance = $attendances->get($employee->id, collect())->get($date);
                                @endphp
                                <td class="px-2 py-4 text-center">
                                    @if($attendance)
                                        @if($attendance->status === 'present')
                                            <a href="{{ route('admin.attendance.show', $attendance) }}"
                                               class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 hover:bg-green-200 dark:hover:bg-green-800 transition-colors" title="Present - Click for details">
                                                ✓
                                            </a>
                                        @elseif($attendance->status === 'late')
                                            <a href="{{ route('admin.attendance.show', $attendance) }}"
                                               class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 hover:bg-yellow-200 dark:hover:bg-yellow-800 transition-colors" title="Late - Click for details">
                                                L
                                            </a>
                                        @elseif($attendance->status === 'absent')
                                            <a href="{{ route('admin.attendance.show', $attendance) }}"
                                               class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 hover:bg-red-200 dark:hover:bg-red-800 transition-colors" title="Absent - Click for details">
                                                ✗
                                            </a>
                                        @elseif($attendance->status === 'partial')
                                            <a href="{{ route('admin.attendance.show', $attendance) }}"
                                               class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 hover:bg-blue-200 dark:hover:bg-blue-800 transition-colors" title="Partial - Click for details">
                                                ◐
                                            </a>
                                        @elseif($attendance->status === 'leave')
                                            <a href="{{ route('admin.attendance.show', $attendance) }}"
                                               class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors" title="Leave - Click for details">
                                                C
                                            </a>
                                        @else
                                            <a href="{{ route('admin.attendance.show', $attendance) }}"
                                               class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors" title="Unknown - Click for details">
                                                ?
                                            </a>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-50 text-gray-400 dark:bg-gray-800 dark:text-gray-600" title="No Record">
                                            -
                                        </span>
                                    @endif
                                </td>
                            @endfor
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex justify-end">
            <a href="{{ route('admin.attendance.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Add Attendance Record
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
                window.location.href = `{{ route('admin.attendance.index') }}?month=${newMonth}`;
            }
        });
    </script>
</x-admin-layout>