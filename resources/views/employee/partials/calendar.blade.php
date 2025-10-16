<div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Kalender Kehadiran</h3>
        <div class="text-sm text-gray-500 dark:text-gray-400">
            {{ \Carbon\Carbon::create($currentYear, $currentMonth)->format('F Y') }}
        </div>
    </div>

    <!-- Calendar Header -->
    <div class="grid grid-cols-7 gap-1 mb-2">
        @foreach(['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'] as $day)
        <div class="p-2 text-center text-xs font-medium text-gray-500 dark:text-gray-400">
            {{ $day }}
        </div>
        @endforeach
    </div>

    <!-- Calendar Grid -->
    <div class="grid grid-cols-7 gap-1">
        @php
            $firstDayOfMonth = \Carbon\Carbon::create($currentYear, $currentMonth, 1);
            $daysInMonth = $firstDayOfMonth->daysInMonth;
            $startDayOfWeek = $firstDayOfMonth->dayOfWeek; // 0 = Sunday, 1 = Monday, etc.

            // Adjust for Monday start (0 = Monday, 6 = Sunday)
            $startDayOfWeek = ($startDayOfWeek + 6) % 7;
        @endphp

        <!-- Empty cells for days before the first day of the month -->
        @for($i = 0; $i < $startDayOfWeek; $i++)
        <div class="aspect-square p-1"></div>
        @endfor

        <!-- Days of the month -->
        @foreach($calendarData as $dayData)
        <div class="aspect-square p-1">
            <div class="h-full w-full rounded-lg flex items-center justify-center text-sm font-medium relative
                {{ $dayData['status'] === 'present' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : '' }}
                {{ $dayData['status'] === 'late' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' : '' }}
                {{ $dayData['status'] === 'absent' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' : '' }}
                {{ $dayData['status'] === 'checked_in' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' : '' }}
                {{ $dayData['status'] === 'weekend' ? 'bg-gray-50 text-gray-400 dark:bg-gray-800 dark:text-gray-600' : '' }}
                {{ !$dayData['is_weekend'] && $dayData['status'] === 'absent' ? 'bg-gray-50 text-gray-400 dark:bg-gray-800 dark:text-gray-600' : '' }}
                {{ $dayData['is_today'] ? 'ring-2 ring-blue-500 ring-offset-1' : '' }}">

                {{ $dayData['day'] }}

                <!-- Status indicator -->
                @if($dayData['status'] !== 'absent' && $dayData['status'] !== 'weekend')
                <div class="absolute -top-1 -right-1 w-2 h-2 rounded-full
                    {{ $dayData['status'] === 'present' ? 'bg-green-500' : '' }}
                    {{ $dayData['status'] === 'late' ? 'bg-yellow-500' : '' }}
                    {{ $dayData['status'] === 'checked_in' ? 'bg-blue-500' : '' }}">
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <!-- Legend -->
    <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
        <div class="flex flex-wrap gap-4 text-xs">
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                <span class="text-gray-600 dark:text-gray-400">Hadir</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                <span class="text-gray-600 dark:text-gray-400">Terlambat</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                <span class="text-gray-600 dark:text-gray-400">Sedang Bekerja</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                <span class="text-gray-600 dark:text-gray-400">Tidak Hadir</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
                <span class="text-gray-600 dark:text-gray-400">Libur/Akhir Pekan</span>
            </div>
        </div>
    </div>

    <!-- Recent Attendance -->
    @if($recentAttendance->count() > 0)
    <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Kehadiran Terbaru</h4>
        <div class="space-y-2">
            @foreach($recentAttendance->take(5) as $attendance)
            <div class="flex items-center justify-between text-sm">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full
                        {{ $attendance->check_in && $attendance->check_out ? 'bg-green-500' : '' }}
                        {{ $attendance->check_in && !$attendance->check_out ? 'bg-blue-500' : '' }}
                        {{ !$attendance->check_in ? 'bg-red-500' : '' }}">
                    </div>
                    <span class="text-gray-600 dark:text-gray-400">
                        {{ $attendance->attendance_date->format('d M Y') }}
                    </span>
                </div>
                <div class="text-gray-900 dark:text-white">
                    @if($attendance->check_in_time)
                        Masuk: {{ $attendance->check_in_time->format('H:i') }}
                        @if($attendance->check_out_time)
                            • Keluar: {{ $attendance->check_out_time->format('H:i') }}
                        @endif
                    @else
                        Tidak hadir
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>