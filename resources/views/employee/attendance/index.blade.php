<x-employee-layout>
@php
    $branchData = ($employee && $employee->branch) ? [
        'id' => $employee->branch->id,
        'name' => $employee->branch->name,
        'latitude' => $employee->branch->latitude ? (float) $employee->branch->latitude : null,
        'longitude' => $employee->branch->longitude ? (float) $employee->branch->longitude : null,
        'radius' => $employee->branch->radius ? (float) $employee->branch->radius : null,
    ] : null;

    // Determine current state
    $state = 'check_in'; // default
    if (isset($todayLeave) && $todayLeave) {
        $state = 'leave';
    } elseif ($todayAttendance && $todayAttendance->check_in_time && !$todayAttendance->check_out_time) {
        $state = 'check_out';
    } elseif ($todayAttendance && $todayAttendance->check_out_time) {
        $state = 'completed';
    }
@endphp

    @push('styles')
    <!-- Fancybox CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
    @endpush

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Absensi</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Left Column: Main Action -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Main Attendance Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden relative">
                        <!-- Decorative Header -->
                        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-500 to-teal-500"></div>
                        
                        <div class="p-6 md:p-8">
                            <!-- State: Leave -->
                            @if($state === 'leave')
                                <div class="text-center py-8">
                                    <div class="w-20 h-20 bg-blue-50 dark:bg-blue-900/20 rounded-full flex items-center justify-center mx-auto mb-6">
                                        <i class="fa-solid fa-umbrella-beach text-3xl text-blue-500"></i>
                                    </div>
                                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Anda Sedang Cuti</h2>
                                    <p class="text-gray-600 dark:text-gray-300 mb-4">{{ $todayLeave->leave_type }}</p>
                                    <div class="inline-flex items-center px-4 py-2 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-sm font-medium">
                                        {{ $todayLeave->start_date->format('d M Y') }} - {{ $todayLeave->end_date->format('d M Y') }}
                                    </div>
                                </div>

                            <!-- State: Completed -->
                            @elseif($state === 'completed')
                                <div class="text-center py-8">
                                    <div class="w-20 h-20 bg-emerald-50 dark:bg-emerald-900/20 rounded-full flex items-center justify-center mx-auto mb-6">
                                        <i class="fa-solid fa-check-double text-3xl text-emerald-500"></i>
                                    </div>
                                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Absensi Hari Ini Selesai</h2>
                                    <p class="text-gray-600 dark:text-gray-300 mb-6">Terima kasih atas kerja keras Anda hari ini!</p>
                                    
                                    <div class="grid grid-cols-2 gap-4 max-w-md mx-auto">
                                        <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-xl border border-gray-100 dark:border-gray-700">
                                            <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Masuk</div>
                                            <div class="text-lg font-bold text-gray-900 dark:text-white">
                                                {{ $todayAttendance->check_in_time->format('H:i') }}
                                            </div>
                                            <button onclick="openEditModal('check_in', '{{ $todayAttendance->id }}', '{{ $todayAttendance->check_in_time->format('Y-m-d\\TH:i') }}')" 
                                                class="text-xs text-blue-600 hover:text-blue-700 mt-1 font-medium">
                                                Edit
                                            </button>
                                        </div>
                                        <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-xl border border-gray-100 dark:border-gray-700">
                                            <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Pulang</div>
                                            <div class="text-lg font-bold text-gray-900 dark:text-white">
                                                {{ $todayAttendance->check_out_time->format('H:i') }}
                                            </div>
                                            <button onclick="openEditModal('check_out', '{{ $todayAttendance->id }}', '{{ $todayAttendance->check_out_time->format('Y-m-d\\TH:i') }}')"
                                                class="text-xs text-blue-600 hover:text-blue-700 mt-1 font-medium">
                                                Edit
                                            </button>
                                        </div>
                                    </div>
                                    
                                    @if($todayAttendance->work_duration)
                                    <div class="mt-6 inline-flex items-center text-sm text-gray-500 dark:text-gray-400">
                                        <i class="fa-regular fa-clock mr-2"></i>
                                        Total Durasi: {{ floor($todayAttendance->work_duration / 60) }}j {{ $todayAttendance->work_duration % 60 }}m
                                    </div>
                                    @endif
                                </div>

                            <!-- State: Check In or Check Out -->
                            @else
                                <div class="flex flex-col h-full">
                                    <div class="mb-6 flex items-center justify-between">
                                        <div>
                                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                                                {{ $state === 'check_in' ? 'Check In' : 'Check Out' }}
                                            </h2>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $state === 'check_in' ? 'Silakan absen masuk untuk memulai hari.' : 'Silakan absen pulang untuk mengakhiri hari.' }}
                                            </p>
                                        </div>
                                        @if($state === 'check_out')
                                            <div class="text-right">
                                                <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Masuk Pukul</div>
                                                <div class="text-lg font-bold text-gray-900 dark:text-white">
                                                    {{ $todayAttendance->check_in_time->format('H:i') }}
                                                </div>
                                                <button onclick="openEditModal('check_in', '{{ $todayAttendance->id }}', '{{ $todayAttendance->check_in_time->format('Y-m-d\\TH:i') }}')" 
                                                    class="text-xs text-blue-600 hover:text-blue-700 mt-1 font-medium">
                                                    Edit
                                                </button>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Camera Section -->
                                    <div class="relative w-full aspect-square rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-900 shadow-inner ring-1 ring-black/5 dark:ring-white/10 mb-6 group">
                                        <!-- Placeholder / Initial State -->
                                        <div id="camera-placeholder" class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                                            <i class="fa-solid fa-camera text-4xl mb-3"></i>
                                            <p class="text-sm">Kamera belum aktif</p>
                                        </div>

                                        <!-- Video Element -->
                                        <video id="attendance-video" class="w-full h-full object-cover hidden" autoplay playsinline muted style="transform: scaleX(-1)"></video>
                                        <canvas id="attendance-canvas" class="hidden"></canvas>
                                        
                                        <!-- Captured Image Preview -->
                                        <img id="captured-image" class="w-full h-full object-cover hidden" alt="Captured Selfie">

                                        <!-- Live Overlay -->
                                        <div id="live-overlay" class="absolute top-4 left-4 bg-black/50 backdrop-blur-sm text-white text-xs px-3 py-2 rounded-lg space-y-1 hidden">
                                            <div id="overlay-distance" class="font-medium">Jarak: -</div>
                                            <div id="overlay-status" class="font-bold uppercase">Menunggu Lokasi</div>
                                        </div>
                                    </div>

                                    <!-- Location Status Bar -->
                                    <div id="location-status-bar" class="mb-6 p-4 rounded-xl bg-gray-50 dark:bg-gray-700/30 border border-gray-100 dark:border-gray-700 flex items-start gap-3 transition-colors duration-300">
                                        <div id="location-icon" class="mt-0.5 text-gray-400">
                                            <i class="fa-solid fa-location-dot animate-pulse"></i>
                                        </div>
                                        <div class="flex-1">
                                            <h4 id="location-title" class="text-sm font-semibold text-gray-900 dark:text-white">Mencari Lokasi...</h4>
                                            <p id="location-desc" class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                                Mohon tunggu sebentar, sistem sedang mendeteksi lokasi Anda.
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="mt-auto grid grid-cols-1 gap-3">
                                        <button type="button" id="btn-start-camera" class="w-full py-3.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl shadow-sm shadow-blue-200 dark:shadow-none transition-all flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                            <i class="fa-solid fa-camera"></i>
                                            Aktifkan Kamera
                                        </button>
                                        
                                        <button type="button" id="btn-capture" class="hidden w-full py-3.5 px-4 bg-white border-2 border-blue-600 text-blue-600 hover:bg-blue-50 font-bold rounded-xl transition-all flex items-center justify-center gap-2">
                                            <div class="w-4 h-4 rounded-full bg-blue-600"></div>
                                            Ambil Foto
                                        </button>

                                        <button type="button" id="btn-retake" class="hidden w-full py-3.5 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition-all">
                                            <i class="fa-solid fa-rotate-left mr-2"></i>
                                            Foto Ulang
                                        </button>

                                        <form id="attendance-form" method="POST" action="{{ $state === 'check_in' ? route('employee.attendance.check-in') : route('employee.attendance.check-out') }}" class="hidden w-full" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="latitude" id="input-lat">
                                            <input type="hidden" name="longitude" id="input-lng">
                                            <!-- File input will be populated by JS -->
                                            <input type="file" name="selfie" id="input-selfie" class="hidden">
                                            
                                            <button type="submit" id="btn-submit" class="w-full py-3.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-emerald-200 dark:shadow-none transition-all flex items-center justify-center gap-2">
                                                <i class="fa-solid fa-paper-plane"></i>
                                                {{ $state === 'check_in' ? 'Kirim Absen Masuk' : 'Kirim Absen Pulang' }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

                <!-- Right Column: Info & History -->
                <div class="space-y-6">
                    
                    <!-- Policy Info Card -->
                    @if($attendancePolicy)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider mb-4 flex items-center">
                            <i class="fa-solid fa-circle-info text-blue-500 mr-2"></i> Kebijakan Absensi
                        </h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between border-b border-gray-50 dark:border-gray-700 pb-2">
                                <span class="text-gray-500 dark:text-gray-400">Jam Kerja</span>
                                <span class="font-medium text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($attendancePolicy->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($attendancePolicy->end_time)->format('H:i') }}
                                </span>
                            </div>
                            <div class="flex justify-between border-b border-gray-50 dark:border-gray-700 pb-2">
                                <span class="text-gray-500 dark:text-gray-400">Toleransi Telat</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $attendancePolicy->late_tolerance }} Menit</span>
                            </div>
                            <div class="flex justify-between border-b border-gray-50 dark:border-gray-700 pb-2">
                                <span class="text-gray-500 dark:text-gray-400">Hari Kerja</span>
                                <span class="font-medium text-gray-900 dark:text-white text-right">
                                    @php
                                        $daysMap = [1=>'Sen', 2=>'Sel', 3=>'Rab', 4=>'Kam', 5=>'Jum', 6=>'Sab', 7=>'Min'];
                                        $days = collect($attendancePolicy->work_days)->map(fn($d) => $daysMap[$d] ?? $d)->implode(', ');
                                    @endphp
                                    {{ $days }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Location Info Card -->
                    @if($employee && $employee->branch)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider mb-4 flex items-center">
                            <i class="fa-solid fa-map-pin text-red-500 mr-2"></i> Lokasi Kantor
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Nama Cabang</div>
                                <div class="font-medium text-gray-900 dark:text-white">{{ $employee->branch->name }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Radius Absensi</div>
                                <div class="font-medium text-gray-900 dark:text-white">{{ $employee->branch->radius }} Meter</div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Recent History -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="p-5 border-b border-gray-100 dark:border-gray-700">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider">
                                Riwayat Terbaru
                            </h3>
                        </div>
                        <div class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($recentAttendance->take(5) as $history)
                                <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <div class="flex justify-between items-start mb-2">
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $history->attendance_date->format('d M') }}
                                        </span>
                                        <span class="text-xs px-2 py-0.5 rounded-full {{ $history->status === 'present' ? 'bg-green-100 text-green-700' : ($history->status === 'late' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700') }}">
                                            {{ ucfirst($history->status) }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mb-2">
                                        <div class="flex items-center gap-2">
                                            <span>
                                                <i class="fa-solid fa-arrow-right-to-bracket text-emerald-500 mr-1"></i>
                                                {{ $history->check_in_time ? $history->check_in_time->format('H:i') : '-' }}
                                            </span>
                                            @if($history->check_in_photo)
                                            <a href="{{ asset('storage/' . $history->check_in_photo) }}" data-fancybox="gallery-{{ $history->id }}" data-caption="Check In: {{ $history->attendance_date->format('d M Y') }} - {{ $history->check_in_time->format('H:i') }}" class="text-blue-500 hover:text-blue-600">
                                                <i class="fa-regular fa-image"></i>
                                            </a>
                                            @endif
                                            @if($history->check_in_time)
                                            <button onclick="openEditModal('check_in', '{{ $history->id }}', '{{ $history->check_in_time->format('Y-m-d\\TH:i') }}')" class="text-gray-400 hover:text-blue-500 transition-colors" title="Edit Check In">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span>
                                                <i class="fa-solid fa-arrow-right-from-bracket text-red-500 mr-1"></i>
                                                {{ $history->check_out_time ? $history->check_out_time->format('H:i') : '-' }}
                                            </span>
                                            @if($history->check_out_photo)
                                            <a href="{{ asset('storage/' . $history->check_out_photo) }}" data-fancybox="gallery-{{ $history->id }}" data-caption="Check Out: {{ $history->attendance_date->format('d M Y') }} - {{ $history->check_out_time->format('H:i') }}" class="text-blue-500 hover:text-blue-600">
                                                <i class="fa-regular fa-image"></i>
                                            </a>
                                            @endif
                                            @if($history->check_out_time)
                                            <button onclick="openEditModal('check_out', '{{ $history->id }}', '{{ $history->check_out_time->format('Y-m-d\\TH:i') }}')" class="text-gray-400 hover:text-blue-500 transition-colors" title="Edit Check Out">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-6 text-center text-sm text-gray-500">
                                    Belum ada riwayat.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Revision History -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="p-5 border-b border-gray-100 dark:border-gray-700">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider">
                                Riwayat Pengajuan Revisi
                            </h3>
                        </div>
                        <div class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($revisions as $revision)
                                <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $revision->revised_time->format('d M Y') }}
                                            </span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400 block">
                                                {{ $revision->revision_type === 'check_in' ? 'Check In' : 'Check Out' }}
                                            </span>
                                        </div>
                                        @php
                                            $statusClasses = [
                                                'pending' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300',
                                                'approved_supervisor' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300',
                                                'approved' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300',
                                                'rejected' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300',
                                            ];
                                            $statusLabels = [
                                                'pending' => 'Menunggu',
                                                'approved_supervisor' => 'Disetujui SPV',
                                                'approved' => 'Disetujui HRD',
                                                'rejected' => 'Ditolak',
                                            ];
                                        @endphp
                                        <span class="text-xs px-2 py-0.5 rounded-full {{ $statusClasses[$revision->status] ?? 'bg-gray-100 text-gray-700' }}">
                                            {{ $statusLabels[$revision->status] ?? ucfirst($revision->status) }}
                                        </span>
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mb-2">
                                        <p class="truncate" title="{{ $revision->reason }}">
                                            <i class="fa-regular fa-comment-dots mr-1"></i> {{ $revision->reason }}
                                        </p>
                                    </div>
                                    <div class="flex justify-between items-center text-xs">
                                        <span class="text-gray-400">
                                            {{ $revision->created_at->diffForHumans() }}
                                        </span>
                                        @if($revision->proof_photo)
                                            <a href="{{ asset('storage/' . $revision->proof_photo) }}" data-fancybox="revision-proof-{{ $revision->id }}" class="text-blue-500 hover:text-blue-600 flex items-center gap-1">
                                                <i class="fa-regular fa-image"></i> Bukti
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="p-6 text-center text-sm text-gray-500">
                                    Belum ada pengajuan revisi.
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="edit-modal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-blur-sm"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/30 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fa-solid fa-pen-to-square text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white" id="modal-title">Edit Absensi</h3>
                                <div class="mt-4">
                                    <form id="edit-attendance-form" class="space-y-4" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" id="edit-attendance-id" name="attendance_id">
                                        <input type="hidden" id="edit-revision-type" name="revision_type">
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Waktu Tercatat</label>
                                            <input type="text" id="edit-original-time" readonly class="w-full rounded-lg border-gray-300 bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 text-sm">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Waktu Baru <span class="text-red-500">*</span></label>
                                            <input type="datetime-local" id="edit-revised-time" name="revised_time" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alasan <span class="text-red-500">*</span></label>
                                            <textarea id="edit-reason" name="reason" rows="3" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500" placeholder="Jelaskan alasan perubahan..."></textarea>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bukti Foto <span class="text-red-500">*</span></label>
                                            <input type="file" name="proof_photo" accept="image/*" required class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900/30 dark:file:text-blue-300">
                                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Unggah foto bukti (misal: jam tangan, suasana sekitar, dll)</p>
                                        </div>
                                        
                                        <div class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg text-xs text-blue-700 dark:text-blue-300">
                                            Permohonan edit akan ditinjau oleh atasan atau admin sebelum disetujui.
                                        </div>

                                        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                                            <button type="submit" class="inline-flex w-full justify-center rounded-lg border border-transparent bg-blue-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm">
                                                Kirim Permohonan
                                            </button>
                                            <button type="button" onclick="closeEditModal()" class="mt-3 inline-flex w-full justify-center rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-2 text-base font-medium text-gray-700 dark:text-gray-200 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:w-auto sm:text-sm">
                                                Batal
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <!-- Fancybox JS -->
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Initialize Fancybox
        Fancybox.bind("[data-fancybox]", {
            // Your custom options
        });

        // Pass PHP data to JS
        window.branchInfo = @json($branchData);
        window.attendanceState = '{{ $state }}';
        
        // --- Location & Camera Logic ---
        document.addEventListener('DOMContentLoaded', function() {
            const els = {
                video: document.getElementById('attendance-video'),
                canvas: document.getElementById('attendance-canvas'),
                placeholder: document.getElementById('camera-placeholder'),
                capturedImage: document.getElementById('captured-image'),
                overlay: document.getElementById('live-overlay'),
                // overlayCoords removed
                overlayDistance: document.getElementById('overlay-distance'),
                overlayStatus: document.getElementById('overlay-status'),
                
                locationBar: document.getElementById('location-status-bar'),
                locationTitle: document.getElementById('location-title'),
                locationDesc: document.getElementById('location-desc'),
                locationIcon: document.getElementById('location-icon'),
                
                btnStart: document.getElementById('btn-start-camera'),
                btnCapture: document.getElementById('btn-capture'),
                btnRetake: document.getElementById('btn-retake'),
                btnSubmit: document.getElementById('btn-submit'),
                
                form: document.getElementById('attendance-form'),
                inputLat: document.getElementById('input-lat'),
                inputLng: document.getElementById('input-lng'),
                inputSelfie: document.getElementById('input-selfie'),
            };

            let stream = null;
            let currentLocation = null;

            // 1. Geolocation Logic
            function updateLocationUI(loc) {
                currentLocation = loc;
                
                if (loc.error) {
                    els.locationTitle.textContent = "Lokasi Tidak Tersedia";
                    els.locationDesc.textContent = loc.error;
                    els.locationBar.className = "mb-6 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800 flex items-start gap-3";
                    els.locationIcon.innerHTML = '<i class="fa-solid fa-triangle-exclamation text-red-500"></i>';
                    if(els.btnStart) els.btnStart.disabled = true;
                } else if (!loc.available) {
                    // Waiting
                    if(els.btnStart) els.btnStart.disabled = true;
                } else {
                    // Location available
                    const dist = loc.distance !== null ? Math.round(loc.distance) + 'm' : '-';
                    
                    if (loc.insideRadius) {
                        els.locationTitle.textContent = "Anda berada di lokasi kantor";
                        els.locationDesc.textContent = `Jarak: ${dist}. Anda dapat melakukan absensi.`;
                        els.locationBar.className = "mb-6 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800 flex items-start gap-3";
                        els.locationIcon.innerHTML = '<i class="fa-solid fa-circle-check text-emerald-500"></i>';
                        if(els.btnStart) els.btnStart.disabled = false;
                    } else {
                        els.locationTitle.textContent = "Anda berada di luar jangkauan";
                        els.locationDesc.textContent = `Jarak: ${dist}. Maksimal radius: ${window.branchInfo.radius}m.`;
                        els.locationBar.className = "mb-6 p-4 rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800 flex items-start gap-3";
                        els.locationIcon.innerHTML = '<i class="fa-solid fa-location-crosshairs text-amber-500"></i>';
                        if(els.btnStart) els.btnStart.disabled = true;
                    }

                    // Update Overlay
                    // els.overlayCoords.textContent = `Lat: ${loc.lat.toFixed(6)}, Lng: ${loc.lng.toFixed(6)}`; // Removed
                    els.overlayDistance.textContent = `Jarak: ${dist}`;
                    els.overlayStatus.textContent = loc.insideRadius ? 'DALAM RADIUS' : 'LUAR RADIUS';
                    els.overlayStatus.className = loc.insideRadius ? 'font-bold uppercase text-emerald-400' : 'font-bold uppercase text-red-400';
                }

                // Update Form Inputs
                if (loc.available) {
                    els.inputLat.value = loc.lat;
                    els.inputLng.value = loc.lng;
                }
            }

            // Simple Geolocation Watcher
            if ("geolocation" in navigator) {
                navigator.geolocation.watchPosition(
                    (pos) => {
                        const lat = pos.coords.latitude;
                        const lng = pos.coords.longitude;
                        let dist = null;
                        let inside = true; // Default true if no branch info

                        if (window.branchInfo && window.branchInfo.latitude) {
                            dist = calculateDistance(lat, lng, window.branchInfo.latitude, window.branchInfo.longitude);
                            inside = dist <= window.branchInfo.radius;
                        }

                        updateLocationUI({
                            available: true,
                            lat: lat,
                            lng: lng,
                            distance: dist,
                            insideRadius: inside,
                            error: null
                        });
                    },
                    (err) => {
                        let msg = "Gagal mengambil lokasi.";
                        if (err.code === 1) msg = "Izin lokasi ditolak.";
                        else if (err.code === 2) msg = "Lokasi tidak tersedia.";
                        else if (err.code === 3) msg = "Waktu habis saat mencari lokasi.";
                        
                        updateLocationUI({ available: false, error: msg });
                    },
                    { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                );
            } else {
                updateLocationUI({ available: false, error: "Browser tidak mendukung geolokasi." });
            }

            // 2. Camera Logic
            if(els.btnStart) {
                els.btnStart.addEventListener('click', async () => {
                    try {
                        stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' }, audio: false });
                        els.video.srcObject = stream;
                        els.video.classList.remove('hidden');
                        els.placeholder.classList.add('hidden');
                        els.overlay.classList.remove('hidden');
                        
                        els.btnStart.classList.add('hidden');
                        els.btnCapture.classList.remove('hidden');
                    } catch (err) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Akses Kamera Ditolak',
                            text: 'Gagal mengakses kamera: ' + err.message
                        });
                    }
                });
            }

            if(els.btnCapture) {
                els.btnCapture.addEventListener('click', () => {
                    if (!stream) return;
                    
                    // Draw to canvas
                    const w = els.video.videoWidth;
                    const h = els.video.videoHeight;
                    els.canvas.width = w;
                    els.canvas.height = h;
                    const ctx = els.canvas.getContext('2d');
                    
                    // FLIP CONTEXT HORIZONTALLY to match the mirrored video preview
                    ctx.translate(w, 0);
                    ctx.scale(-1, 1);
                    
                    ctx.drawImage(els.video, 0, 0, w, h);
                    
                    // Reset transform for text overlay (so text is readable)
                    ctx.setTransform(1, 0, 0, 1, 0, 0);
                    
                    // Add Overlay Text to Image (Improved)
                    const fontSize = Math.max(16, Math.floor(w * 0.03)); // Dynamic font size
                    const padding = Math.floor(fontSize * 0.8);
                    // Increase background height to accommodate 3 lines of text
                    const bgHeight = fontSize * 4.5;

                    ctx.fillStyle = 'rgba(0, 0, 0, 0.6)';
                    ctx.fillRect(0, h - bgHeight, w, bgHeight);
                    
                    ctx.fillStyle = 'white';
                    ctx.textBaseline = 'bottom';
                    
                    // Line 1: Branch Name
                    ctx.font = `bold ${fontSize}px sans-serif`;
                    const branchName = (window.branchInfo && window.branchInfo.name) ? window.branchInfo.name : 'Kantor';
                    ctx.fillText(branchName, padding, h - (fontSize * 2.8));

                    // Line 2: Timestamp
                    ctx.font = `${fontSize * 0.9}px sans-serif`;
                    const dateStr = new Date().toLocaleString('id-ID', { dateStyle: 'full', timeStyle: 'medium' });
                    ctx.fillText(dateStr, padding, h - (fontSize * 1.6));
                    
                    // Line 3: Coordinates
                    if (currentLocation && currentLocation.available) {
                        const locStr = `Lat: ${currentLocation.lat.toFixed(6)}, Lng: ${currentLocation.lng.toFixed(6)}`;
                        ctx.font = `${fontSize * 0.75}px monospace`;
                        ctx.fillText(locStr, padding, h - (fontSize * 0.5));
                    }

                    // Convert to file
                    els.canvas.toBlob((blob) => {
                        const file = new File([blob], "attendance_selfie.jpg", { type: "image/jpeg" });
                        const container = new DataTransfer();
                        container.items.add(file);
                        els.inputSelfie.files = container.files;
                        
                        // Show preview
                        els.capturedImage.src = URL.createObjectURL(blob);
                        els.capturedImage.classList.remove('hidden');
                        els.video.classList.add('hidden');
                        els.overlay.classList.add('hidden');
                        
                        // Stop stream
                        stream.getTracks().forEach(track => track.stop());
                        
                        // Switch buttons
                        els.btnCapture.classList.add('hidden');
                        els.btnRetake.classList.remove('hidden');
                        els.form.classList.remove('hidden');
                    }, 'image/jpeg', 0.85);
                });
            }

            if(els.btnRetake) {
                els.btnRetake.addEventListener('click', () => {
                    els.capturedImage.classList.add('hidden');
                    els.form.classList.add('hidden');
                    els.btnRetake.classList.add('hidden');
                    els.btnStart.click(); // Restart camera
                });
            }

            // Helper: Haversine Distance
            function calculateDistance(lat1, lon1, lat2, lon2) {
                const R = 6371e3; // metres
                const φ1 = lat1 * Math.PI/180;
                const φ2 = lat2 * Math.PI/180;
                const Δφ = (lat2-lat1) * Math.PI/180;
                const Δλ = (lon2-lon1) * Math.PI/180;

                const a = Math.sin(Δφ/2) * Math.sin(Δφ/2) +
                        Math.cos(φ1) * Math.cos(φ2) *
                        Math.sin(Δλ/2) * Math.sin(Δλ/2);
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));

                return R * c;
            }

            // --- Main Attendance Form Submission (AJAX) ---
            if(els.form) {
                els.form.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    const btn = els.btnSubmit;
                    const originalText = btn.innerHTML;
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mengirim...';

                    try {
                        const formData = new FormData(this);
                        const res = await fetch(this.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: formData
                        });
                        const data = await res.json();

                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: data.message,
                                confirmButtonColor: '#10B981',
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: data.message || 'Terjadi kesalahan.',
                                confirmButtonColor: '#EF4444',
                            });
                        }
                    } catch (err) {
                        console.error(err);
                        Swal.fire({
                            icon: 'error',
                            title: 'Kesalahan',
                            text: 'Terjadi kesalahan koneksi atau server.',
                            confirmButtonColor: '#EF4444',
                        });
                    } finally {
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                    }
                });
            }
        });

        // --- Edit Modal Logic ---
        function openEditModal(type, id, time) {
            const modal = document.getElementById('edit-modal');
            modal.classList.remove('hidden');
            
            document.getElementById('edit-attendance-id').value = id;
            document.getElementById('edit-revision-type').value = type;
            document.getElementById('edit-original-time').value = new Date(time).toLocaleString('id-ID');
            document.getElementById('edit-revised-time').value = time;
        }

        function closeEditModal() {
            document.getElementById('edit-modal').classList.add('hidden');
        }

        document.getElementById('edit-attendance-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            const btn = this.querySelector('button[type="submit"]');
            const originalText = btn.innerText;
            btn.disabled = true;
            btn.innerText = 'Mengirim...';

            try {
                const formData = new FormData(this);
                const res = await fetch('{{ route("employee.attendance.revision.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                const data = await res.json();
                
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Permohonan edit absensi berhasil dikirim dan menunggu persetujuan.',
                        confirmButtonColor: '#3085d6',
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.message || 'Terjadi kesalahan saat mengirim permohonan.'
                    });
                }
            } catch (err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan Koneksi',
                    text: 'Tidak dapat menghubungi server.'
                });
                console.error(err);
            } finally {
                btn.disabled = false;
                btn.innerText = originalText;
            }
        });

    </script>
    @endpush
</x-employee-layout>