<x-employee-layout>
    @include('components.camera-modal')

    <div class="py-6 md:py-8 bg-gray-50/50 dark:bg-gray-900/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- Welcome Header -->
            <div class="bg-gradient-to-r from-blue-500/10 to-purple-500/10 dark:from-blue-500/5 dark:to-purple-500/5 rounded-2xl border border-blue-200/50 dark:border-blue-800/30 p-6 md:p-8">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-xl overflow-hidden border-2 border-white dark:border-gray-700 shadow-lg">
                            <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&color=FFFFFF&background=3B82F6' }}"
                                alt="Profile Photo"
                                class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                                Halo, {{ explode(' ', auth()->user()->name)[0] }}! 👋
                            </h1>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                {{ $employee->position ?? 'Karyawan' }}
                                @if($employee->department)
                                    • {{ $employee->department }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium {{ $employee->status === 'active' ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800' : 'bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-800' }}">
                            <span class="w-2 h-2 rounded-full {{ $employee->status === 'active' ? 'bg-emerald-500' : 'bg-red-500' }} mr-2 animate-pulse"></span>
                            {{ ucfirst($employee->status ?? 'inactive') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Today's Status Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 md:p-8">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 flex items-center justify-center rounded-lg border-2 border-blue-500 dark:border-blue-400">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Status Hari Ini</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::now()->format('l, d F Y') }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <p class="text-2xl font-bold {{ 
                        $todayStatus === 'Belum Check In' ? 'text-gray-500 dark:text-gray-400' : 
                        ($todayStatus === 'Sedang Bekerja' ? 'text-blue-600 dark:text-blue-400' : 'text-emerald-600 dark:text-emerald-400') 
                    }}">
                        {{ $todayStatus }}
                    </p>
                    @if($todayStatus !== 'Belum Check In')
                        <div class="w-3 h-3 rounded-full {{ $todayStatus === 'Sedang Bekerja' ? 'bg-blue-500' : 'bg-emerald-500' }} animate-pulse"></div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @if($todayStatus === 'Belum Check In')
                    <a href="#" data-camera-trigger
                        class="group bg-gradient-to-br from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 dark:from-emerald-600 dark:to-emerald-700 dark:hover:from-emerald-500 dark:hover:to-emerald-600 rounded-xl p-6 text-white transition-all duration-200 shadow-lg hover:shadow-xl border border-emerald-400/20">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold mb-1">Check In Sekarang</h3>
                                <p class="text-emerald-100 dark:text-emerald-200 text-sm">Lakukan absensi harian</p>
                            </div>
                            <div class="w-12 h-12 flex items-center justify-center rounded-lg bg-white/20 group-hover:scale-110 transition-transform duration-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13l-3 3m0 0l-3-3m3 3V8m0 13a9 9 0 110-18 9 9 0 010 18z" />
                                </svg>
                            </div>
                        </div>
                    </a>
                @elseif($todayStatus === 'Sedang Bekerja')
                    <button type="button" data-camera-checkout
                        class="group bg-gradient-to-br from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 dark:from-red-600 dark:to-red-700 dark:hover:from-red-500 dark:hover:to-red-600 rounded-xl p-6 text-white transition-all duration-200 shadow-lg hover:shadow-xl border border-red-400/20 w-full text-left">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold mb-1">Check Out Sekarang</h3>
                                <p class="text-red-100 dark:text-red-200 text-sm">Selesaikan absensi harian</p>
                            </div>
                            <div class="w-12 h-12 flex items-center justify-center rounded-lg bg-white/20 group-hover:scale-110 transition-transform duration-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z" />
                                </svg>
                            </div>
                        </div>
                    </button>
                @else
                    <div class="bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 rounded-xl p-6 border border-gray-300 dark:border-gray-600">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-1">Absensi Selesai</h3>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">Anda sudah check out hari ini</p>
                            </div>
                            <div class="w-12 h-12 flex items-center justify-center rounded-lg bg-white/50 dark:bg-gray-900/50">
                                <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                @endif

                <a href="{{ route('employee.attendance.index') }}"
                    class="group bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-750 rounded-xl p-6 border border-gray-200 dark:border-gray-700 transition-all duration-200 hover:shadow-md">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Riwayat Kehadiran</h3>
                            <p class="text-gray-600 dark:text-gray-400 text-sm">Lihat semua absensi</p>
                        </div>
                        <div class="w-12 h-12 flex items-center justify-center rounded-lg bg-blue-50 dark:bg-blue-900/20 group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                    </div>
                </a>

                <a href="{{ route('employee.payroll.index') }}"
                    class="group bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-750 rounded-xl p-6 border border-gray-200 dark:border-gray-700 transition-all duration-200 hover:shadow-md">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Riwayat Gaji</h3>
                            <p class="text-gray-600 dark:text-gray-400 text-sm">Informasi penggajian</p>
                        </div>
                        <div class="w-12 h-12 flex items-center justify-center rounded-lg bg-emerald-50 dark:bg-emerald-900/20 group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                            </svg>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Work Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Work Duration -->
                @if($workDuration)
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-purple-50 dark:bg-purple-900/20">
                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Lama Bekerja</p>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            @if($workDuration['years'] > 0)
                                {{ $workDuration['years'] }} Tahun
                            @elseif($workDuration['months'] > 0)
                                {{ $workDuration['months'] }} Bulan
                            @else
                                {{ $workDuration['days'] }} Hari
                            @endif
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Total {{ number_format($workDuration['total_days']) }} hari
                        </p>
                    </div>
                </div>
                @endif

                <!-- Monthly Hours -->
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-blue-50 dark:bg-blue-900/20">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Jam Kerja</p>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($monthlyHours, 1) }}h</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Bulan ini</p>
                    </div>
                </div>

                <!-- Attendance Stats -->
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-emerald-50 dark:bg-emerald-900/20">
                            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Kehadiran</p>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $attendanceStats['present_days'] }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Dari {{ $attendanceStats['working_days'] }} hari</p>
                    </div>
                </div>

                <!-- Salary -->
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-amber-50 dark:bg-amber-900/20">
                            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Gaji Terakhir</p>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($lastPayroll / 1000000, 1) }}jt</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Estimasi bulan ini</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 md:p-8">
                <!-- Header with Month Navigation -->
                <div class="flex flex-col md:flex-row items-center justify-between mb-8 gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 flex items-center justify-center rounded-lg border-2 border-blue-500 dark:border-blue-400">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Kalender Absensi</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Pantau kehadiran Anda</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button id="prev-month" class="w-9 h-9 flex items-center justify-center text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <span id="current-month" class="text-base font-semibold text-gray-900 dark:text-white min-w-[140px] text-center">{{ \Carbon\Carbon::create($currentYear, $currentMonth, 1)->format('F Y') }}</span>
                        <button id="next-month" class="w-9 h-9 flex items-center justify-center text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Calendar Grid -->
                <div class="calendar-container">
                    <!-- Days of week header -->
                    <div class="grid grid-cols-7 gap-2 mb-3">
                        <div class="py-2 text-center text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">Min</div>
                        <div class="py-2 text-center text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">Sen</div>
                        <div class="py-2 text-center text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">Sel</div>
                        <div class="py-2 text-center text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">Rab</div>
                        <div class="py-2 text-center text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">Kam</div>
                        <div class="py-2 text-center text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">Jum</div>
                        <div class="py-2 text-center text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">Sab</div>
                    </div>

                    <!-- Calendar days -->
                    <div class="grid grid-cols-7 gap-2">
                        @php
                            $calendarMonth = \Carbon\Carbon::create($currentYear, $currentMonth, 1);
                            $startOfMonth = $calendarMonth->copy()->startOfMonth();
                            $endOfMonth = $calendarMonth->copy()->endOfMonth();
                            $startDate = $startOfMonth->copy()->startOfWeek(\Carbon\Carbon::SUNDAY);
                            $endDate = $endOfMonth->copy()->endOfWeek(\Carbon\Carbon::SATURDAY);

                            // Create attendance lookup array
                            $attendanceLookup = [];
                            foreach($recentAttendance as $att) {
                                $attendanceLookup[$att->attendance_date->format('Y-m-d')] = $att;
                            }
                        @endphp

                        @for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay())
                            @php
                                $isCurrentMonth = $date->month === $calendarMonth->month;
                                $isToday = $date->isToday();
                                $isWeekend = $date->isWeekend();
                                $dateKey = $date->format('Y-m-d');
                                $attendance = isset($attendanceLookup[$dateKey]) ? $attendanceLookup[$dateKey] : null;
                                
                                // Determine status
                                $status = 'no-data';
                                $statusClass = '';
                                $statusBorder = '';
                                $statusIcon = '';
                                $statusText = 'Tidak Ada Data';
                                
                                if ($attendance) {
                                    if ($attendance->check_in_time && $attendance->check_out_time) {
                                        if ($attendance->late_minutes > 0) {
                                            $status = 'late';
                                            $statusClass = 'bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-400';
                                            $statusBorder = 'border-amber-200 dark:border-amber-800';
                                            $statusIcon = '●';
                                            $statusText = 'Terlambat ' . $attendance->late_minutes . ' menit';
                                        } else {
                                            $status = 'present';
                                            $statusClass = 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400';
                                            $statusBorder = 'border-emerald-200 dark:border-emerald-800';
                                            $statusIcon = '●';
                                            $statusText = 'Hadir Tepat Waktu';
                                        }
                                    } else if ($attendance->check_in_time) {
                                        $status = 'checked-in';
                                        $statusClass = 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400';
                                        $statusBorder = 'border-blue-200 dark:border-blue-800';
                                        $statusIcon = '●';
                                        $statusText = 'Sedang Bekerja';
                                    }
                                } else if ($isCurrentMonth && $date->lt(\Carbon\Carbon::today()) && !$isWeekend) {
                                    $status = 'absent';
                                    $statusClass = 'bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400';
                                    $statusBorder = 'border-red-200 dark:border-red-800';
                                    $statusIcon = '●';
                                    $statusText = 'Tidak Hadir';
                                }
                            @endphp
                            <div class="calendar-day group relative min-h-[85px] p-2.5 rounded-lg transition-all duration-200 cursor-pointer
                                {{ $status !== 'no-data' ? $statusClass . ' border ' . $statusBorder : 'bg-gray-50/50 dark:bg-gray-700/30 border border-gray-200 dark:border-gray-600' }}
                                {{ !$isCurrentMonth ? 'opacity-40' : '' }} 
                                {{ $isToday ? 'ring-2 ring-blue-500 ring-offset-2 dark:ring-offset-gray-800' : '' }}
                                hover:ring-1 hover:ring-gray-300 dark:hover:ring-gray-600"
                                data-date="{{ $dateKey }}"
                                data-status="{{ $status }}"
                                data-status-text="{{ $statusText }}">
                                
                                <!-- Date number -->
                                <div class="flex items-center justify-between mb-2">
                                    <div class="text-sm font-semibold {{ $isToday ? 'text-blue-600 dark:text-blue-400' : ($status !== 'no-data' ? '' : 'text-gray-700 dark:text-gray-300') }}">
                                        {{ $date->format('d') }}
                                    </div>
                                    @if($status !== 'no-data')
                                        <div class="text-xs">{{ $statusIcon }}</div>
                                    @endif
                                </div>
                                
                                <!-- Status Info -->
                                @if($status !== 'no-data')
                                    <div class="space-y-1">
                                        <div class="text-[11px] font-medium">
                                            {{ $status === 'present' ? 'Hadir' : ($status === 'late' ? 'Terlambat' : ($status === 'absent' ? 'Tidak Hadir' : 'Bekerja')) }}
                                        </div>
                                        @if($attendance && $attendance->check_in_time)
                                            <div class="text-[10px] opacity-75">
                                                {{ \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') }}
                                            </div>
                                        @endif
                                    </div>
                                @endif
                                
                                <!-- Hover Tooltip -->
                                <div class="tooltip absolute left-1/2 -translate-x-1/2 bottom-full mb-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-10">
                                    <div class="bg-gray-900 dark:bg-gray-700 text-white text-xs rounded-lg px-3 py-2 whitespace-nowrap">
                                        <div class="font-semibold mb-1">{{ $date->format('d F Y') }}</div>
                                        <div class="text-gray-300">{{ $statusText }}</div>
                                        @if($attendance && $attendance->check_in_time)
                                            <div class="mt-1 pt-1 border-t border-gray-700 dark:border-gray-600 text-gray-300">
                                                Masuk: {{ \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') }}
                                                @if($attendance->check_out_time)
                                                    <br>Pulang: {{ \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i') }}
                                                @endif
                                            </div>
                                        @endif
                                        <div class="absolute left-1/2 -translate-x-1/2 top-full border-4 border-transparent border-t-gray-900 dark:border-t-gray-700"></div>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>

                <!-- Legend -->
                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex flex-wrap justify-center gap-4 text-sm">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                            <span class="text-gray-700 dark:text-gray-300">Hadir</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                            <span class="text-gray-700 dark:text-gray-300">Terlambat</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-red-500"></div>
                            <span class="text-gray-700 dark:text-gray-300">Tidak Hadir</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                            <span class="text-gray-700 dark:text-gray-300">Sedang Bekerja</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Attendance calendar navigation
            document.addEventListener('DOMContentLoaded', function() {
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
                    // Get current displayed month
                    const urlParams = new URLSearchParams(window.location.search);
                    let currentMonthParam = urlParams.get('month');
                    
                    let currentDate;
                    if (currentMonthParam) {
                        const parts = currentMonthParam.split('-');
                        currentDate = new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, 1);
                    } else {
                        currentDate = new Date({{ $currentYear }}, {{ $currentMonth - 1 }}, 1);
                    }
                    
                    // Add/subtract month
                    currentDate.setMonth(currentDate.getMonth() + direction);
                    
                    const newYear = currentDate.getFullYear();
                    const newMonth = String(currentDate.getMonth() + 1).padStart(2, '0');
                    const newMonthParam = `${newYear}-${newMonth}`;
                    
                    window.location.href = `${window.location.pathname}?month=${newMonthParam}`;
                }
            });

            // Initialize location tracking for quick check-in/check-out
            if (!window.AttendanceLocationManager) {
                // If AttendanceLocationManager doesn't exist, create a minimal version
                window.branchInfo = @json($branchData);
                window.branchName = @json($branchData['name'] ?? null);

                // Load the attendance location manager script
                const script = document.createElement('script');
                script.textContent = `
                    if (!window.calculateDistanceMeters) {
                        window.calculateDistanceMeters = function(lat1, lon1, lat2, lon2) {
                            if ([lat1, lon1, lat2, lon2].some(function(value) { return value === null || value === undefined || isNaN(parseFloat(value)); })) {
                                return null;
                            }
                            const toRad = function(value) { return (value * Math.PI) / 180; };
                            const earthRadius = 6371000; // meters
                            const dLat = toRad(lat2 - lat1);
                            const dLon = toRad(lon2 - lon1);
                            const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                                      Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) *
                                      Math.sin(dLon / 2) * Math.sin(dLon / 2);
                            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                            return earthRadius * c;
                        };
                    }

                    window.AttendanceLocationManager = (function() {
                        let branchInfo = null;
                        let watchStarted = false;
                        let watchId = null;
                        let data = { available: false, lat: null, lng: null, distance: null, insideRadius: false, error: null };
                        const listeners = [];

                        function normalizeBranchInfo(info) {
                            if (!info) {
                                return null;
                            }
                            const latitude = info.latitude !== undefined && info.latitude !== null ? parseFloat(info.latitude) : null;
                            const longitude = info.longitude !== undefined && info.longitude !== null ? parseFloat(info.longitude) : null;
                            const radius = info.radius !== undefined && info.radius !== null ? parseFloat(info.radius) : null;
                            if ([latitude, longitude, radius].some(function(value) { return value === null || Number.isNaN(value); })) {
                                return null;
                            }
                            return { ...info, latitude, longitude, radius };
                        }

                        function notify() {
                            listeners.forEach(function(listener) {
                                listener({ ...data });
                            });
                        }

                        function ensureWatcher() {
                            if (watchStarted) {
                                return;
                            }
                            watchStarted = true;

                            if (!('geolocation' in navigator)) {
                                data.error = 'Perangkat tidak mendukung geolokasi.';
                                data.available = false;
                                data.distance = null;
                                data.insideRadius = false;
                                notify();
                                return;
                            }

                            if ('permissions' in navigator) {
                                navigator.permissions.query({name:'geolocation'}).then(function(result) {
                                    if (result.state === 'denied') {
                                        data.error = 'Izin lokasi ditolak. Silakan izinkan lokasi di pengaturan browser Anda.';
                                        data.available = false;
                                        data.distance = null;
                                        data.insideRadius = false;
                                        notify();
                                        return;
                                    }
                                    getInitialLocation();
                                }).catch(function() {
                                    getInitialLocation();
                                });
                            } else {
                                getInitialLocation();
                            }

                            function getInitialLocation() {
                                navigator.geolocation.getCurrentPosition(function(position) {
                                    handleSuccess(position);
                                    startWatching();
                                }, function(error) {
                                    startWatching();
                                }, {
                                    enableHighAccuracy: true,
                                    maximumAge: 10000,
                                    timeout: 5000,
                                });
                            }

                            function startWatching() {
                                watchId = navigator.geolocation.watchPosition(handleSuccess, handleError, {
                                    enableHighAccuracy: true,
                                    maximumAge: 3000,
                                    timeout: 5000,
                                });

                                setTimeout(function() {
                                    if (!data.available && !data.error) {
                                        data.error = 'Tidak dapat memperoleh lokasi. Pastikan GPS aktif dan izinkan akses lokasi di browser.';
                                        notify();
                                    }
                                }, 5000);
                            }
                        }

                        function handleSuccess(position) {
                            data.lat = position.coords.latitude;
                            data.lng = position.coords.longitude;
                            data.available = true;
                            data.error = null;

                            if (branchInfo && branchInfo.latitude !== null && branchInfo.longitude !== null && branchInfo.radius !== null) {
                                data.distance = window.calculateDistanceMeters(data.lat, data.lng, branchInfo.latitude, branchInfo.longitude);
                                data.insideRadius = data.distance !== null ? data.distance <= branchInfo.radius : false;
                            } else {
                                data.distance = null;
                                data.insideRadius = true;
                            }

                            notify();
                        }

                        function handleError(error) {
                            let errorMessage = 'Tidak dapat memperoleh lokasi.';
                            if (error) {
                                switch(error.code) {
                                    case error.PERMISSION_DENIED:
                                        errorMessage = 'Akses lokasi ditolak. Silakan izinkan lokasi di pengaturan browser Anda.';
                                        break;
                                    case error.POSITION_UNAVAILABLE:
                                        errorMessage = 'Informasi lokasi tidak tersedia. Pastikan GPS aktif dan sinyal kuat.';
                                        break;
                                    case error.TIMEOUT:
                                        errorMessage = 'Waktu permintaan lokasi habis. Coba lagi.';
                                        break;
                                    default:
                                        errorMessage = error.message || 'Terjadi kesalahan saat memperoleh lokasi.';
                                        break;
                                }
                            }
                            data.error = errorMessage;
                            data.available = false;
                            data.distance = null;
                            data.insideRadius = false;
                            notify();
                        }

                        return {
                            setBranchInfo(info) {
                                branchInfo = normalizeBranchInfo(info);
                                ensureWatcher();
                                notify();
                            },
                            subscribe(listener) {
                                if (typeof listener === 'function') {
                                    listeners.push(listener);
                                    listener({ ...data });
                                }
                                ensureWatcher();
                            },
                            getData() {
                                return { ...data };
                            },
                            hasBranchInfo() {
                                return !!branchInfo;
                            }
                        };
                    })();

                    if (window.branchInfo) {
                        window.AttendanceLocationManager.setBranchInfo(window.branchInfo);
                    } else {
                        window.AttendanceLocationManager.subscribe(function() {});
                    }

                    if (!window.drawAttendanceOverlay) {
                        window.drawAttendanceOverlay = function(ctx, width, height, options = {}) {
                            if (!ctx) {
                                return;
                            }

                            const now = options.timestamp ? new Date(options.timestamp) : new Date();
                            const timestampText = now.toLocaleString();
                            const hasCoords = typeof options.latitude === 'number' && typeof options.longitude === 'number';
                            const coordsText = hasCoords ? \`Lat: \${options.latitude.toFixed(6)}, Lng: \${options.longitude.toFixed(6)}\` : 'Koordinat tidak tersedia';
                            const distanceText = typeof options.distance === 'number' ? \`Jarak: \${options.distance.toFixed(1)} m\` : null;
                            const statusText = typeof options.insideRadius === 'boolean'
                                ? (options.insideRadius ? 'Status: Dalam radius' : 'Status: Di luar radius')
                                : null;
                            const labelText = options.label ? String(options.label) : null;

                            const lines = [labelText, timestampText, coordsText, distanceText, statusText].filter(Boolean);
                            if (!lines.length) {
                                return;
                            }

                            const padding = 16;
                            const lineHeight = 18;
                            const boxHeight = padding + lineHeight * lines.length;

                            ctx.save();
                            ctx.fillStyle = 'rgba(0, 0, 0, 0.65)';
                            ctx.fillRect(0, height - boxHeight, width, boxHeight);
                            ctx.fillStyle = '#FFFFFF';
                            ctx.font = '16px sans-serif';
                            lines.forEach(function(line, index) {
                                ctx.fillText(line, padding, height - boxHeight + padding + lineHeight * (index + 0.5));
                            });
                            ctx.restore();
                        };
                    }
                `;
                document.head.appendChild(script);
            }
        </script>
    @endpush
</x-employee-layout>
