@extends('layouts.employee')

@section('content')
    @include('components.camera-modal')

    <div class="py-6 md:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Profile Summary -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                @include('employee.partials.profile', ['employee' => $employee, 'user' => $user])
            </div>

            <!-- Today's Status -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                @include('employee.partials.status')
            </div>

            <!-- Quick Actions - Moved to prominent position -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @if($todayStatus === 'Belum Check In')
                    <a href="#" data-camera-trigger
                        class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 rounded-xl p-6 text-white transition-all duration-200 transform hover:scale-105">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold mb-1">Check In Sekarang</h3>
                                <p class="text-green-100 text-sm">Lakukan absensi harian</p>
                            </div>
                            <div class="bg-white/20 rounded-full p-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4v10a2 2 0 002 2h4a2 2 0 002-2V11M9 11h6" />
                                </svg>
                            </div>
                        </div>
                    </a>
                @elseif($todayStatus === 'Sedang Bekerja')
                    <button type="button" data-camera-checkout
                        class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 rounded-xl p-6 text-white transition-all duration-200 transform hover:scale-105 w-full text-left">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold mb-1">Check Out Sekarang</h3>
                                <p class="text-red-100 text-sm">Selesaikan absensi harian</p>
                            </div>
                            <div class="bg-white/20 rounded-full p-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </div>
                        </div>
                    </button>
                @else
                    <div class="bg-gradient-to-r from-gray-400 to-gray-500 rounded-xl p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold mb-1">Absensi Selesai</h3>
                                <p class="text-gray-200 text-sm">Anda sudah check out hari ini</p>
                            </div>
                            <div class="bg-white/20 rounded-full p-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                @endif

                <a href="{{ route('employee.attendance.index') }}"
                    class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 transition-all duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Riwayat Kehadiran</h3>
                            <p class="text-gray-600 dark:text-gray-300 text-sm">Lihat semua absensi Anda</p>
                        </div>
                        <div class="p-2 rounded-lg" style="background-color: rgba(0, 129, 89, 0.1)">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" style="color: var(--color-haga-1, #008159)" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                    </div>
                </a>

                <a href="{{ route('employee.payroll.index') }}"
                    class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 transition-all duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Riwayat Gaji</h3>
                            <p class="text-gray-600 dark:text-gray-300 text-sm">Informasi penggajian</p>
                        </div>
                        <div class="p-2 rounded-lg" style="background-color: rgba(16, 200, 117, 0.1)">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" style="color: var(--color-haga-2, #10c875)" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                            </svg>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Attendance Calendar -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Kalender Absensi</h3>
                    <div class="flex items-center space-x-4">
                        <button id="prev-month" class="p-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                            <i class="fa-solid fa-chevron-left"></i>
                        </button>
                        <span id="current-month" class="text-lg font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::now()->format('F Y') }}</span>
                        <button id="next-month" class="p-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                            <i class="fa-solid fa-chevron-right"></i>
                        </button>
                    </div>
                </div>

                <!-- Calendar Grid -->
                <div class="calendar-container">
                    <!-- Days of week header -->
                    <div class="grid grid-cols-7 gap-1 mb-2">
                        <div class="p-2 text-center text-sm font-medium text-gray-500 dark:text-gray-400">Min</div>
                        <div class="p-2 text-center text-sm font-medium text-gray-500 dark:text-gray-400">Sen</div>
                        <div class="p-2 text-center text-sm font-medium text-gray-500 dark:text-gray-400">Sel</div>
                        <div class="p-2 text-center text-sm font-medium text-gray-500 dark:text-gray-400">Rab</div>
                        <div class="p-2 text-center text-sm font-medium text-gray-500 dark:text-gray-400">Kam</div>
                        <div class="p-2 text-center text-sm font-medium text-gray-500 dark:text-gray-400">Jum</div>
                        <div class="p-2 text-center text-sm font-medium text-gray-500 dark:text-gray-400">Sab</div>
                    </div>

                    <!-- Calendar days -->
                    <div class="grid grid-cols-7 gap-1">
                        @php
                            $currentMonth = \Carbon\Carbon::now();
                            $startOfMonth = $currentMonth->copy()->startOfMonth();
                            $endOfMonth = $currentMonth->copy()->endOfMonth();
                            $startDate = $startOfMonth->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
                            $endDate = $endOfMonth->copy()->endOfWeek(\Carbon\Carbon::SUNDAY);

                            // Create attendance lookup array
                            $attendanceLookup = [];
                            foreach($recentAttendance as $att) {
                                $attendanceLookup[$att->attendance_date->format('Y-m-d')] = $att;
                            }
                        @endphp

                        @for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay())
                            @php
                                $isCurrentMonth = $date->month === $currentMonth->month;
                                $isToday = $date->isToday();
                                $dateKey = $date->format('Y-m-d');
                                $attendance = isset($attendanceLookup[$dateKey]) ? $attendanceLookup[$dateKey] : null;
                            @endphp
                            <div class="min-h-[80px] p-1 border border-gray-200 dark:border-gray-600 rounded-lg {{ $isCurrentMonth ? 'bg-white dark:bg-gray-700' : 'bg-gray-50 dark:bg-gray-800' }} {{ $isToday ? 'ring-2 ring-blue-500' : '' }}">
                                <div class="text-sm font-medium text-gray-900 dark:text-white mb-1">{{ $date->format('d') }}</div>
                                @if($attendance)
                                    @if($attendance->status === 'present')
                                        <div class="text-xs bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 px-1 py-0.5 rounded text-center">✓ Hadir</div>
                                    @elseif($attendance->status === 'late')
                                        <div class="text-xs bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 px-1 py-0.5 rounded text-center">L Telat</div>
                                    @elseif($attendance->status === 'absent')
                                        <div class="text-xs bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 px-1 py-0.5 rounded text-center">✗ Absen</div>
                                    @else
                                        <div class="text-xs bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 px-1 py-0.5 rounded text-center">-</div>
                                    @endif
                                @else
                                    @if($isCurrentMonth && $date->lt(\Carbon\Carbon::today()))
                                        <div class="text-xs bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 px-1 py-0.5 rounded text-center">✗ Absen</div>
                                    @else
                                        <div class="text-xs bg-gray-100 text-gray-400 dark:bg-gray-800 dark:text-gray-600 px-1 py-0.5 rounded text-center">-</div>
                                    @endif
                                @endif
                            </div>
                        @endfor
                    </div>
                </div>

                <!-- Legend -->
                <div class="mt-4 flex flex-wrap gap-4 text-sm">
                    <div class="flex items-center">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 mr-2">✓</span>
                        <span class="text-gray-700 dark:text-gray-300">Hadir</span>
                    </div>
                    <div class="flex items-center">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 mr-2">L</span>
                        <span class="text-gray-700 dark:text-gray-300">Terlambat</span>
                    </div>
                    <div class="flex items-center">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 mr-2">✗</span>
                        <span class="text-gray-700 dark:text-gray-300">Tidak Hadir</span>
                    </div>
                    <div class="flex items-center">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 mr-2">-</span>
                        <span class="text-gray-700 dark:text-gray-300">Tidak Ada Data</span>
                    </div>
                </div>
            </div>

            <!-- Salary Information -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Informasi Gaji</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Current Month Salary -->
                    <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-green-100 text-sm">Gaji Bulan Ini</p>
                                <h3 class="text-2xl font-bold">Rp {{ number_format($lastPayroll) }}</h3>
                                <p class="text-green-100 text-xs mt-1">Estimasi berdasarkan bulan lalu</p>
                            </div>
                            <div class="bg-white/20 rounded-full p-3">
                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Work Hours -->
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-100 text-sm">Jam Kerja Bulan Ini</p>
                                <h3 class="text-2xl font-bold">{{ number_format($monthlyHours, 1) }}h</h3>
                                <p class="text-blue-100 text-xs mt-1">Total jam bekerja</p>
                            </div>
                            <div class="bg-white/20 rounded-full p-3">
                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
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
                    const currentMonth = currentMonthSpan.textContent;
                    const [monthName, year] = currentMonth.split(' ');
                    const monthNames = [
                        'January', 'February', 'March', 'April', 'May', 'June',
                        'July', 'August', 'September', 'October', 'November', 'December'
                    ];
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
                    window.location.href = `${window.location.pathname}?month=${newMonth}`;
                }
            });
        </script>
    @endpush
@endsection
