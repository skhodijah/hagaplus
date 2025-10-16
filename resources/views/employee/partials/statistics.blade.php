<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Working Hours This Month -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm font-medium">Jam Kerja Bulan Ini</p>
                <p class="text-2xl font-bold">{{ number_format($monthlyHours, 1) }}h</p>
            </div>
            <div class="bg-white/20 rounded-full p-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Days Present -->
    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm font-medium">Hari Hadir</p>
                <p class="text-2xl font-bold">{{ $attendanceStats['present_days'] }}</p>
                <p class="text-xs text-green-200">dari {{ $attendanceStats['total_days'] }} hari</p>
            </div>
            <div class="bg-white/20 rounded-full p-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Late Days -->
    <div class="bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-yellow-100 text-sm font-medium">Terlambat</p>
                <p class="text-2xl font-bold">{{ $attendanceStats['late_days'] }}</p>
                <p class="text-xs text-yellow-200">kali bulan ini</p>
            </div>
            <div class="bg-white/20 rounded-full p-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Absent Days -->
    <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-red-100 text-sm font-medium">Tidak Hadir</p>
                <p class="text-2xl font-bold">{{ $attendanceStats['absent_days'] }}</p>
                <p class="text-xs text-red-200">hari bulan ini</p>
            </div>
            <div class="bg-white/20 rounded-full p-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Stats -->
<div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Statistik Detail Bulan Ini</h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Attendance Breakdown -->
        <div>
            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Ringkasan Kehadiran</h4>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Total Hari Kerja</span>
                    <span class="font-medium">{{ $attendanceStats['working_days'] ?? $attendanceStats['total_days'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Hadir</span>
                    <span class="font-medium text-green-600">{{ $attendanceStats['present_days'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Terlambat</span>
                    <span class="font-medium text-yellow-600">{{ $attendanceStats['late_days'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Tidak Hadir</span>
                    <span class="font-medium text-red-600">{{ $attendanceStats['absent_days'] }}</span>
                </div>
            </div>
        </div>

        <!-- Working Hours Summary -->
        <div>
            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Ringkasan Jam Kerja</h4>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Total Jam Kerja</span>
                    <span class="font-medium">{{ number_format($attendanceStats['total_hours'], 1) }} jam</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Rata-rata per hari</span>
                    <span class="font-medium">
                        {{ $attendanceStats['present_days'] > 0 ? number_format($attendanceStats['total_hours'] / $attendanceStats['present_days'], 1) : 0 }} jam
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Persentase Kehadiran</span>
                    <span class="font-medium">
                        {{ $attendanceStats['total_days'] > 0 ? round(($attendanceStats['present_days'] / $attendanceStats['total_days']) * 100, 1) : 0 }}%
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>