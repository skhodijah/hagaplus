<x-employee-layout>
    <div class="space-y-6">
        <!-- Welcome Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-1">Selamat Datang, {{ Auth::user()->name }}!</h1>
                    <p class="text-gray-600 dark:text-gray-300">Berikut adalah ringkasan aktivitas Anda hari ini</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 mt-4 md:mt-0">
                    <div class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-white" style="background-color: var(--color-haga-1, #008159)">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ now()->translatedFormat('l, d F Y') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Today's Attendance -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg" style="background-color: var(--color-haga-1, #008159)">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Status Hari Ini</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            @if($todayAttendance && $todayAttendance->check_in_time)
                                @if($todayAttendance->check_out_time)
                                    Selesai
                                @else
                                    Bekerja
                                @endif
                            @else
                                Belum Check In
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Working Hours -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-blue-500">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Jam Kerja Hari Ini</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            @if($todayAttendance && $todayAttendance->work_duration)
                                {{ floor($todayAttendance->work_duration / 60) }}j {{ $todayAttendance->work_duration % 60 }}m
                            @else
                                0j 0m
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Leave Balance -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-orange-500">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Sisa Cuti</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $leaveBalance ?? 12 }} hari</p>
                    </div>
                </div>
            </div>

            <!-- Pending Requests -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-yellow-500">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pengajuan Pending</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $pendingRequests ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Aksi Cepat</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('employee.attendance.index') }}" class="flex items-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors">
                    <div class="p-2 bg-blue-500 rounded-lg mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">Absensi</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Check in/out</p>
                    </div>
                </a>

                <a href="{{ route('employee.leaves.create') }}" class="flex items-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors">
                    <div class="p-2 bg-green-500 rounded-lg mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">Ajukan Cuti</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Request leave</p>
                    </div>
                </a>

                <a href="{{ route('employee.leaves.index') }}" class="flex items-center p-4 bg-orange-50 dark:bg-orange-900/20 rounded-lg hover:bg-orange-100 dark:hover:bg-orange-900/30 transition-colors">
                    <div class="p-2 bg-orange-500 rounded-lg mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">Riwayat Cuti</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Leave history</p>
                    </div>
                </a>

                <a href="{{ route('employee.payroll.index') }}" class="flex items-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/30 transition-colors">
                    <div class="p-2 bg-purple-500 rounded-lg mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">Payroll</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Salary info</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Aktivitas Terbaru</h3>
            <div class="space-y-4">
                @forelse($recentActivities ?? [] as $activity)
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                            <i class="fa-solid fa-{{ $activity['icon'] ?? 'circle' }} text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $activity['title'] }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $activity['time'] }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-400 dark:text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400">Belum ada aktivitas terbaru</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-employee-layout>