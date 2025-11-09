<x-employee-layout>
@php
    $branchData = ($employee && $employee->branch) ? [
        'id' => $employee->branch->id,
        'name' => $employee->branch->name,
        'latitude' => $employee->branch->latitude ? (float) $employee->branch->latitude : null,
        'longitude' => $employee->branch->longitude ? (float) $employee->branch->longitude : null,
        'radius' => $employee->branch->radius ? (float) $employee->branch->radius : null,
    ] : null;
@endphp
    <div class="py-6 md:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Header -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-1">Absensi Karyawan</h1>
                        <p class="text-gray-600 dark:text-gray-300">Lakukan check in dan check out dengan foto selfie</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3 mt-4 md:mt-0">
                        <button type="button" id="show-policy" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-haga-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Lihat Kebijakan Absensi
                        </button>
                        <div class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-white" style="background-color: var(--color-haga-1, #008159)">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ now()->translatedFormat('l, d F Y') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Today's Status -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                @include('employee.partials.status')
            </div>

            <!-- Location Information -->
            @if($employee && $employee->branch)
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4 mb-6">
                <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-200 mb-2">📍 Lokasi Cabang</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white dark:bg-gray-800 p-3 rounded-lg">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Nama Cabang</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $employee->branch->name }}</p>
                    </div>
                    @if($employee->branch->latitude && $employee->branch->longitude)
                    <div class="bg-white dark:bg-gray-800 p-3 rounded-lg">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Koordinat</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ number_format($employee->branch->latitude, 6) }}, {{ number_format($employee->branch->longitude, 6) }}
                        </p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-3 rounded-lg">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Radius Absensi</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $employee->branch->radius }} meter</p>
                    </div>
                    @endif
                </div>
                <div class="mt-3 p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                    <p class="text-sm text-yellow-800 dark:text-yellow-200">
                        <strong>Penting:</strong> Anda harus berada dalam radius {{ $employee->branch->radius }} meter dari lokasi cabang untuk dapat melakukan absensi.
                    </p>
                </div>
            </div>
            @endif


            <!-- Attendance Actions -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Check In Section -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Check In</h3>
                            @if($todayAttendance && $todayAttendance->check_in_time)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    Sudah Check In
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                    </svg>
                                    Belum Check In
                                </span>
                            @endif
                        </div>

                        @if($todayAttendance && $todayAttendance->check_in_time)
                            <!-- Already checked in -->
                            <div class="text-center py-8">
                                <div class="w-16 h-16 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Check In Berhasil!</h4>
                                <p class="text-gray-600 dark:text-gray-300 mb-4">
                                    Waktu: {{ $todayAttendance->check_in_time->format('H:i:s') }}
                                </p>
                                @if($todayAttendance->check_in_photo)
                                    <div class="inline-block">
                                        <img src="{{ Storage::url($todayAttendance->check_in_photo) }}"
                                             alt="Check In Photo"
                                             class="w-32 h-32 object-cover rounded-lg border border-gray-200 dark:border-gray-600">
                                    </div>
                                @endif
                            </div>
                        @else
                            <!-- Check In Form -->
                            <form id="checkin-form" enctype="multipart/form-data">
                                @csrf
                                <div class="space-y-4">
                                    <div id="checkin-location-status" class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 space-y-2">
                                        <div class="flex flex-wrap items-center gap-4 text-sm">
                                            <div class="text-gray-700 dark:text-gray-200">
                                                <span class="font-semibold">Koordinat Anda:</span>
                                                <span id="checkin-coordinates">Menunggu lokasi...</span>
                                            </div>
                                            <div class="text-gray-700 dark:text-gray-200">
                                                <span class="font-semibold">Jarak ke kantor:</span>
                                                <span id="checkin-distance">-</span>
                                            </div>
                                            <div class="text-gray-700 dark:text-gray-200">
                                                <span class="font-semibold">Status:</span>
                                                <span id="checkin-radius-status" class="font-semibold">Menunggu lokasi...</span>
                                            </div>
                                        </div>
                                        <p id="checkin-location-message" class="text-xs text-gray-600 dark:text-gray-300">
                                            Pastikan Anda berada dalam radius lokasi cabang sebelum melakukan absensi.
                                        </p>
                                    </div>
                                    <div id="checkin-location-alert" class="hidden bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg p-3 text-sm text-red-700 dark:text-red-200">
                                        Lokasi Anda berada di luar radius absensi yang diizinkan.
                                    </div>

                                    <!-- Camera Preview -->
                                    <div class="relative">
                                        <div id="camera-preview" class="w-full aspect-video bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                            <div class="text-center">
                                                <svg class="w-12 h-12 text-gray-400 dark:text-gray-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                <p class="text-gray-500 dark:text-gray-400 text-sm">Klik tombol di bawah untuk mengaktifkan kamera</p>
                                            </div>
                                        </div>
                                        <div id="checkin-live-overlay" class="hidden absolute top-3 left-3 bg-black/60 text-white text-xs px-3 py-2 rounded-lg space-y-1">
                                            <div id="checkin-overlay-coordinates">Lat: -, Lng: -</div>
                                            <div id="checkin-overlay-distance">Jarak: -</div>
                                            <div id="checkin-overlay-status">Status: -</div>
                                        </div>
                                        <video id="checkin-video" class="hidden w-full rounded-lg"></video>
                                        <canvas id="checkin-canvas" class="hidden"></canvas>
                                    </div>

                                    <!-- Camera Controls -->
                                    <div class="space-y-3">
                                        <div class="flex space-x-3">
                                            <button type="button" id="start-camera-checkin"
                                                    class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center gap-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                </svg>
                                                Aktifkan Kamera
                                            </button>
                                            <button type="button" id="capture-checkin"
                                                    class="flex-1 bg-green-500 hover:bg-green-600 text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200 hidden items-center justify-center gap-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                Ambil Foto
                                            </button>
                                        </div>

                                        <!-- Alternative: Upload Photo -->
                                        <div class="text-center">
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Atau</p>
                                            <label for="upload-checkin-photo" id="upload-checkin-label" class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg cursor-pointer transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                                </svg>
                                                Upload Foto dari Galeri
                                            </label>
                                            <input type="file" id="upload-checkin-photo" accept="image/*" class="hidden">
                                        </div>
                                    </div>

                                    <!-- Hidden inputs -->
                                    <input type="file" id="checkin-selfie" name="selfie" class="hidden" accept="image/*">
                                    <input type="hidden" id="checkin-latitude" name="latitude">
                                    <input type="hidden" id="checkin-longitude" name="longitude">

                                    <!-- Submit Button -->
                                    <button type="submit" id="submit-checkin"
                                            class="w-full bg-green-500 hover:bg-green-600 text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200 hidden items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Check In Sekarang
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Check Out Section -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Check Out</h3>
                            @if($todayAttendance && $todayAttendance->check_out_time)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    Sudah Check Out
                                </span>
                            @elseif($todayAttendance && $todayAttendance->check_in_time)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                    </svg>
                                    Sedang Bekerja
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                    </svg>
                                    Belum Check In
                                </span>
                            @endif
                        </div>

                        @if($todayAttendance && $todayAttendance->check_out_time)
                            <!-- Already checked out -->
                            <div class="text-center py-8">
                                <div class="w-16 h-16 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Check Out Berhasil!</h4>
                                <p class="text-gray-600 dark:text-gray-300 mb-2">
                                    Waktu: {{ $todayAttendance->check_out_time->format('H:i:s') }}
                                </p>
                                @if($todayAttendance->work_duration)
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                                        Durasi kerja: {{ floor($todayAttendance->work_duration / 60) }}j {{ $todayAttendance->work_duration % 60 }}m
                                    </p>
                                @endif
                                @if($todayAttendance->check_out_photo)
                                    <div class="inline-block">
                                        <img src="{{ Storage::url($todayAttendance->check_out_photo) }}"
                                             alt="Check Out Photo"
                                             class="w-32 h-32 object-cover rounded-lg border border-gray-200 dark:border-gray-600">
                                    </div>
                                @endif
                            </div>
                        @elseif($todayAttendance && $todayAttendance->check_in_time)
                            <!-- Check Out Form -->
                            <form id="checkout-form" enctype="multipart/form-data">
                                @csrf
                                <div class="space-y-4">
                                    <div id="checkout-location-status" class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 space-y-2">
                                        <div class="flex flex-wrap items-center gap-4 text-sm">
                                            <div class="text-gray-700 dark:text-gray-200">
                                                <span class="font-semibold">Koordinat Anda:</span>
                                                <span id="checkout-coordinates">Menunggu lokasi...</span>
                                            </div>
                                            <div class="text-gray-700 dark:text-gray-200">
                                                <span class="font-semibold">Jarak ke kantor:</span>
                                                <span id="checkout-distance">-</span>
                                            </div>
                                            <div class="text-gray-700 dark:text-gray-200">
                                                <span class="font-semibold">Status:</span>
                                                <span id="checkout-radius-status" class="font-semibold">Menunggu lokasi...</span>
                                            </div>
                                        </div>
                                        <p id="checkout-location-message" class="text-xs text-gray-600 dark:text-gray-300">
                                            Pastikan Anda berada dalam radius lokasi cabang sebelum melakukan absensi.
                                        </p>
                                    </div>
                                    <div id="checkout-location-alert" class="hidden bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg p-3 text-sm text-red-700 dark:text-red-200">
                                        Lokasi Anda berada di luar radius absensi yang diizinkan.
                                    </div>

                                    <!-- Camera Preview -->
                                    <div class="relative">
                                        <div id="camera-preview-checkout" class="w-full aspect-video bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                            <div class="text-center">
                                                <svg class="w-12 h-12 text-gray-400 dark:text-gray-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                <p class="text-gray-500 dark:text-gray-400 text-sm">Klik tombol di bawah untuk mengaktifkan kamera</p>
                                            </div>
                                        </div>
                                        <div id="checkout-live-overlay" class="hidden absolute top-3 left-3 bg-black/60 text-white text-xs px-3 py-2 rounded-lg space-y-1">
                                            <div id="checkout-overlay-coordinates">Lat: -, Lng: -</div>
                                            <div id="checkout-overlay-distance">Jarak: -</div>
                                            <div id="checkout-overlay-status">Status: -</div>
                                        </div>
                                        <video id="checkout-video" class="hidden w-full rounded-lg"></video>
                                        <canvas id="checkout-canvas" class="hidden"></canvas>
                                    </div>

                                    <!-- Camera Controls -->
                                    <div class="space-y-3">
                                        <div class="flex space-x-3">
                                            <button type="button" id="start-camera-checkout"
                                                    class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center gap-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                </svg>
                                                Aktifkan Kamera
                                            </button>
                                            <button type="button" id="capture-checkout"
                                                    class="flex-1 bg-green-500 hover:bg-green-600 text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200 hidden items-center justify-center gap-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                Ambil Foto
                                            </button>
                                        </div>

                                        <!-- Alternative: Upload Photo -->
                                        <div class="text-center">
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Atau</p>
                                            <label for="upload-checkout-photo" id="upload-checkout-label" class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg cursor-pointer transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                                </svg>
                                                Upload Foto dari Galeri
                                            </label>
                                            <input type="file" id="upload-checkout-photo" accept="image/*" class="hidden">
                                        </div>
                                    </div>

                                    <!-- Hidden inputs -->
                                    <input type="file" id="checkout-selfie" name="selfie" class="hidden" accept="image/*">
                                    <input type="hidden" id="checkout-latitude" name="latitude">
                                    <input type="hidden" id="checkout-longitude" name="longitude">

                                    <!-- Submit Button -->
                                    <button type="submit" id="submit-checkout"
                                            class="w-full bg-red-500 hover:bg-red-600 text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200 hidden items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        Check Out Sekarang
                                    </button>
                                </div>
                            </form>
                        @else
                            <!-- Cannot check out yet -->
                            <div class="text-center py-8">
                                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Belum Bisa Check Out</h4>
                                <p class="text-gray-600 dark:text-gray-300">Anda harus check in terlebih dahulu</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Attendance History -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Riwayat Absensi Terbaru</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Check In</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Check Out</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Durasi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($recentAttendance as $attendance)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $attendance->attendance_date->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $attendance->check_in_time ? $attendance->check_in_time->format('H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $attendance->check_out_time ? $attendance->check_out_time->format('H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    @if($attendance->work_duration)
                                        {{ floor($attendance->work_duration / 60) }}j {{ $attendance->work_duration % 60 }}m
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $attendance->status === 'present' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' :
                                           ($attendance->status === 'late' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' :
                                           'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400') }}">
                                        {{ ucfirst($attendance->status ?? 'absent') }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    Belum ada data absensi
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Policy Modal -->
    <div id="policy-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-xl bg-white dark:bg-gray-800">
            <div class="flex justify-between items-center pb-3">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Kebijakan Absensi</h3>
                <button id="close-policy-modal" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="mt-4">
                <div id="policy-loading" class="text-center py-8">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-haga-500 mx-auto"></div>
                    <p class="mt-2 text-gray-600 dark:text-gray-300">Memuat kebijakan absensi...</p>
                </div>
                <div id="policy-content" class="hidden space-y-4">
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <h4 class="font-medium text-gray-900 dark:text-white mb-2" id="policy-name"></h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Hari Kerja:</p>
                                <p class="font-medium" id="policy-work-days"></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Jam Masuk:</p>
                                <p class="font-medium" id="policy-start-time"></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Jam Pulang:</p>
                                <p class="font-medium" id="policy-end-time"></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Toleransi Keterlambatan:</p>
                                <p class="font-medium" id="policy-late-tolerance"></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Durasi Istirahat:</p>
                                <p class="font-medium" id="policy-break-duration"></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Metode Absensi:</p>
                                <p class="font-medium" id="policy-methods"></p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg border-l-4 border-yellow-400 dark:border-yellow-500">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Perhatian</h4>
                                <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                                    <p>Pastikan untuk mematuhi jadwal dan aturan absensi yang telah ditetapkan. Keterlambatan atau pelanggaran lainnya akan dicatat dalam sistem.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="policy-error" class="hidden text-center py-8">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30">
                        <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h3 class="mt-3 text-lg font-medium text-gray-900 dark:text-white">Gagal memuat kebijakan</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Terjadi kesalahan saat memuat kebijakan absensi. Silakan coba lagi nanti.</p>
                    <div class="mt-6">
                        <button type="button" id="retry-policy" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-haga-600 hover:bg-haga-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-haga-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Coba Lagi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        window.branchInfo = window.branchInfo || @json($branchData);
        window.branchName = window.branchName || @json($branchData['name'] ?? null);

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

        if (!window.AttendanceLocationManager) {
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
                    return {
                        ...info,
                        latitude,
                        longitude,
                        radius,
                    };
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

                    watchId = navigator.geolocation.watchPosition(handleSuccess, handleError, {
                        enableHighAccuracy: true,
                        maximumAge: 5000,
                        timeout: 10000,
                    });
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
                    data.error = (error && error.message) ? error.message : 'Tidak dapat memperoleh lokasi.';
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
        }

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
                const coordsText = hasCoords ? `Lat: ${options.latitude.toFixed(6)}, Lng: ${options.longitude.toFixed(6)}` : 'Koordinat tidak tersedia';
                const distanceText = typeof options.distance === 'number' ? `Jarak: ${options.distance.toFixed(1)} m` : null;
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
    </script>
    <script src="{{ asset('js/attendance.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const locationManager = window.AttendanceLocationManager;
            const branchLabel = window.branchName || 'Lokasi Kantor';
            let currentLocation = locationManager ? locationManager.getData() : { available: false, lat: null, lng: null, distance: null, insideRadius: false, error: 'Lokasi belum tersedia.' };

            const checkInElements = {
                form: document.getElementById('checkin-form'),
                startBtn: document.getElementById('start-camera-checkin'),
                captureBtn: document.getElementById('capture-checkin'),
                submitBtn: document.getElementById('submit-checkin'),
                video: document.getElementById('checkin-video'),
                canvas: document.getElementById('checkin-canvas'),
                preview: document.getElementById('camera-preview'),
                overlay: document.getElementById('checkin-live-overlay'),
                overlayCoords: document.getElementById('checkin-overlay-coordinates'),
                overlayDistance: document.getElementById('checkin-overlay-distance'),
                overlayStatus: document.getElementById('checkin-overlay-status'),
                coordsText: document.getElementById('checkin-coordinates'),
                distanceText: document.getElementById('checkin-distance'),
                statusText: document.getElementById('checkin-radius-status'),
                messageText: document.getElementById('checkin-location-message'),
                alertBox: document.getElementById('checkin-location-alert'),
                uploadInput: document.getElementById('upload-checkin-photo'),
                uploadLabel: document.getElementById('upload-checkin-label'),
                latInput: document.getElementById('checkin-latitude'),
                lngInput: document.getElementById('checkin-longitude'),
                selfieInput: document.getElementById('checkin-selfie')
            };

            const checkOutElements = {
                form: document.getElementById('checkout-form'),
                startBtn: document.getElementById('start-camera-checkout'),
                captureBtn: document.getElementById('capture-checkout'),
                submitBtn: document.getElementById('submit-checkout'),
                video: document.getElementById('checkout-video'),
                canvas: document.getElementById('checkout-canvas'),
                preview: document.getElementById('camera-preview-checkout'),
                overlay: document.getElementById('checkout-live-overlay'),
                overlayCoords: document.getElementById('checkout-overlay-coordinates'),
                overlayDistance: document.getElementById('checkout-overlay-distance'),
                overlayStatus: document.getElementById('checkout-overlay-status'),
                coordsText: document.getElementById('checkout-coordinates'),
                distanceText: document.getElementById('checkout-distance'),
                statusText: document.getElementById('checkout-radius-status'),
                messageText: document.getElementById('checkout-location-message'),
                alertBox: document.getElementById('checkout-location-alert'),
                uploadInput: document.getElementById('upload-checkout-photo'),
                uploadLabel: document.getElementById('upload-checkout-label'),
                latInput: document.getElementById('checkout-latitude'),
                lngInput: document.getElementById('checkout-longitude'),
                selfieInput: document.getElementById('checkout-selfie')
            };

            const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : null;
            const checkInUrl = '{{ route("employee.attendance.check-in") }}';
            const checkOutUrl = '{{ route("employee.attendance.check-out") }}';

            let checkInStream = null;
            let checkOutStream = null;

            const videoConstraints = {
                video: {
                    width: { ideal: 640, min: 320 },
                    height: { ideal: 480, min: 240 },
                    facingMode: 'user'
                },
                audio: false
            };

            function setButtonState(button, enabled) {
                if (!button) {
                    return;
                }
                if (enabled) {
                    button.removeAttribute('disabled');
                    button.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    button.setAttribute('disabled', 'disabled');
                    button.classList.add('opacity-50', 'cursor-not-allowed');
                }
            }

            function setLabelState(label, input, enabled) {
                if (!label) {
                    return;
                }
                if (enabled) {
                    label.classList.remove('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
                    if (input) {
                        input.removeAttribute('disabled');
                    }
                } else {
                    label.classList.add('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
                    if (input) {
                        input.setAttribute('disabled', 'disabled');
                        input.value = '';
                    }
                }
            }

            function formatCoordinate(value, fallback) {
                if (typeof value === 'number' && !Number.isNaN(value)) {
                    return value.toFixed(6);
                }
                return fallback;
            }

            function formatDistance(value) {
                if (typeof value === 'number' && !Number.isNaN(value)) {
                    return value.toFixed(1) + ' m';
                }
                return '-';
            }

            function updateStatusBadge(element, location) {
                if (!element) {
                    return;
                }
                element.classList.remove('text-green-600', 'dark:text-green-300', 'text-red-600', 'dark:text-red-300', 'text-yellow-600', 'dark:text-yellow-300');
                if (!location.available) {
                    element.textContent = 'Menunggu lokasi';
                    element.classList.add('text-yellow-600', 'dark:text-yellow-300');
                } else if (location.insideRadius) {
                    element.textContent = 'Dalam radius';
                    element.classList.add('text-green-600', 'dark:text-green-300');
                } else {
                    element.textContent = 'Di luar radius';
                    element.classList.add('text-red-600', 'dark:text-red-300');
                }
            }

            function updateHiddenInputs(elements, location) {
                if (elements.latInput) {
                    elements.latInput.value = location.available ? location.lat : '';
                }
                if (elements.lngInput) {
                    elements.lngInput.value = location.available ? location.lng : '';
                }
            }

            function stopCamera(elements, type) {
                if (type === 'checkin' && checkInStream) {
                    checkInStream.getTracks().forEach(function(track) { track.stop(); });
                    checkInStream = null;
                }
                if (type === 'checkout' && checkOutStream) {
                    checkOutStream.getTracks().forEach(function(track) { track.stop(); });
                    checkOutStream = null;
                }
                if (elements.video) {
                    elements.video.pause();
                    elements.video.srcObject = null;
                    elements.video.classList.add('hidden');
                }
                if (elements.preview) {
                    elements.preview.classList.remove('hidden');
                }
                if (elements.captureBtn) {
                    elements.captureBtn.classList.add('hidden');
                }
                if (elements.submitBtn) {
                    elements.submitBtn.classList.add('hidden');
                }
                if (elements.overlay) {
                    elements.overlay.classList.add('hidden');
                }
            }

            function updateOverlay(elements, location) {
                if (!elements.overlay) {
                    return;
                }
                if (location.available && elements.video && !elements.video.classList.contains('hidden')) {
                    elements.overlay.classList.remove('hidden');
                    if (elements.overlayCoords) {
                        elements.overlayCoords.textContent = `Lat: ${formatCoordinate(location.lat, '-')} | Lng: ${formatCoordinate(location.lng, '-')}`;
                    }
                    if (elements.overlayDistance) {
                        elements.overlayDistance.textContent = location.distance !== null ? `Jarak: ${formatDistance(location.distance)}` : 'Jarak: -';
                    }
                    if (elements.overlayStatus) {
                        elements.overlayStatus.textContent = location.insideRadius ? 'Status: Dalam radius' : 'Status: Di luar radius';
                        elements.overlayStatus.classList.toggle('text-green-300', location.insideRadius);
                        elements.overlayStatus.classList.toggle('text-red-300', !location.insideRadius);
                    }
                } else {
                    elements.overlay.classList.add('hidden');
                }
            }

            function updateLocationPanel(elements, location, type) {
                if (!elements.form) {
                    return;
                }

                const enabled = location.available && location.insideRadius;

                if (elements.coordsText) {
                    elements.coordsText.textContent = location.available
                        ? `${formatCoordinate(location.lat, '-')} , ${formatCoordinate(location.lng, '-')}`
                        : 'Menunggu lokasi...';
                }

                if (elements.distanceText) {
                    elements.distanceText.textContent = location.distance !== null ? formatDistance(location.distance) : '-';
                }

                if (elements.messageText) {
                    if (!location.available) {
                        elements.messageText.textContent = 'Aktifkan layanan lokasi pada perangkat dan pastikan browser memiliki izin lokasi.';
                    } else if (!location.insideRadius) {
                        elements.messageText.textContent = 'Anda berada di luar radius absensi. Silakan mendekati lokasi cabang.';
                    } else {
                        elements.messageText.textContent = 'Anda berada dalam radius absensi. Silakan lanjutkan proses check in/out.';
                    }
                }

                if (elements.alertBox) {
                    elements.alertBox.classList.toggle('hidden', !location.available || location.insideRadius);
                }

                updateStatusBadge(elements.statusText, location);
                updateHiddenInputs(elements, location);
                setButtonState(elements.startBtn, enabled);
                setLabelState(elements.uploadLabel, elements.uploadInput, enabled);
                updateOverlay(elements, location);

                if (!enabled) {
                    stopCamera(elements, type);
                }
            }

            if (locationManager) {
                locationManager.subscribe(function(location) {
                    currentLocation = location;
                    updateLocationPanel(checkInElements, location, 'checkin');
                    updateLocationPanel(checkOutElements, location, 'checkout');
                });
            } else {
                updateLocationPanel(checkInElements, currentLocation, 'checkin');
                updateLocationPanel(checkOutElements, currentLocation, 'checkout');
            }

            function ensureSecureContext() {
                const isSecure = location.protocol === 'https:' || location.hostname === 'localhost' || location.hostname === '127.0.0.1';
                if (!isSecure) {
                    alert('Kamera memerlukan HTTPS. Gunakan HTTPS atau localhost untuk mengakses kamera.');
                }
                return isSecure;
            }

            function handleCameraError(error) {
                console.error('Camera error:', error);
                if (error.name === 'NotAllowedError') {
                    alert('Akses kamera ditolak. Silakan izinkan akses kamera di browser Anda.');
                } else if (error.name === 'NotFoundError') {
                    alert('Tidak ada kamera yang ditemukan. Pastikan perangkat Anda memiliki kamera.');
                } else if (error.name === 'NotReadableError') {
                    alert('Kamera sedang digunakan oleh aplikasi lain. Tutup aplikasi lain yang menggunakan kamera.');
                } else if (error.name === 'OverconstrainedError') {
                    alert('Kamera tidak mendukung resolusi yang diminta. Coba gunakan kamera lain.');
                } else if (error.name === 'SecurityError') {
                    alert('Kamera memerlukan HTTPS. Gunakan HTTPS atau localhost untuk mengakses kamera.');
                } else {
                    alert('Tidak dapat mengakses kamera. Error: ' + error.name + ' - ' + error.message);
                }
            }

            async function startCamera(elements, type) {
                if (!elements.startBtn) {
                    return;
                }

                if (!currentLocation.available) {
                    alert('Lokasi belum tersedia. Pastikan layanan lokasi aktif.');
                    return;
                }
                if (!currentLocation.insideRadius) {
                    alert('Anda berada di luar radius absensi yang diizinkan.');
                    return;
                }
                if (!ensureSecureContext()) {
                    return;
                }
                try {
                    const stream = await navigator.mediaDevices.getUserMedia(videoConstraints);
                    if (type === 'checkin') {
                        if (checkInStream) {
                            checkInStream.getTracks().forEach(function(track) { track.stop(); });
                        }
                        checkInStream = stream;
                    } else {
                        if (checkOutStream) {
                            checkOutStream.getTracks().forEach(function(track) { track.stop(); });
                        }
                        checkOutStream = stream;
                    }

                    if (elements.video) {
                        elements.video.srcObject = stream;
                        elements.video.classList.remove('hidden');
                        elements.video.onloadedmetadata = function() {
                            elements.video.play();
                            updateOverlay(elements, currentLocation);
                        };
                    }
                    if (elements.preview) {
                        elements.preview.classList.add('hidden');
                    }
                    if (elements.startBtn) {
                        elements.startBtn.classList.add('hidden');
                    }
                    if (elements.captureBtn) {
                        elements.captureBtn.classList.remove('hidden');
                    }

                } catch (error) {
                    handleCameraError(error);
                }
            }

            function assignCanvasToInput(canvas, input, filename, callback) {
                if (!canvas || !input) {
                    if (typeof callback === 'function') {
                        callback(false);
                    }
                    return;
                }
                canvas.toBlob(function(blob) {
                    if (!blob) {
                        if (typeof callback === 'function') {
                            callback(false);
                        }
                        return;
                    }
                    const file = new File([blob], filename, { type: 'image/jpeg' });
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    input.files = dataTransfer.files;
                    if (typeof callback === 'function') {
                        callback(true);
                    }
                }, 'image/jpeg', 0.9);
            }

            function capturePhoto(elements, type) {
                if (!elements.video || elements.video.classList.contains('hidden') || !elements.canvas) {
                    return;
                }
                const canvas = elements.canvas;
                const context = canvas.getContext('2d');
                const width = elements.video.videoWidth || 640;
                const height = elements.video.videoHeight || 480;
                canvas.width = width;
                canvas.height = height;
                context.drawImage(elements.video, 0, 0, width, height);

                window.drawAttendanceOverlay(context, width, height, {
                    timestamp: Date.now(),
                    latitude: currentLocation.lat,
                    longitude: currentLocation.lng,
                    distance: currentLocation.distance,
                    insideRadius: currentLocation.insideRadius,
                    label: branchLabel
                });

                assignCanvasToInput(canvas, elements.selfieInput, `${type}-selfie.jpg`, function(success) {
                    if (success) {
                        if (elements.video) {
                            elements.video.pause();
                            elements.video.srcObject = null;
                            elements.video.classList.add('hidden');
                        }
                        if (elements.overlay) {
                            elements.overlay.classList.add('hidden');
                        }
                        if (elements.captureBtn) {
                            elements.captureBtn.classList.add('hidden');
                        }
                        if (elements.submitBtn) {
                            elements.submitBtn.classList.remove('hidden');
                        }
                        canvas.classList.remove('hidden');
                    }
                });

                stopCamera(elements, type);
            }

            function handleUpload(elements, file, type) {
                if (!file || !elements.canvas) {
                    return;
                }
                if (!currentLocation.available || !currentLocation.insideRadius) {
                    alert('Anda berada di luar radius absensi yang diizinkan.');
                    elements.uploadInput.value = '';
                    return;
                }

                if (!file.type.startsWith('image/')) {
                    alert('Silakan pilih file gambar yang valid.');
                    elements.uploadInput.value = '';
                    return;
                }
                if (file.size > 5 * 1024 * 1024) {
                    alert('Ukuran file maksimal 5MB.');
                    elements.uploadInput.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = new Image();
                    img.onload = function() {
                        const canvas = elements.canvas;
                        const ctx = canvas.getContext('2d');
                        canvas.width = 640;
                        canvas.height = 480;
                        const aspectRatio = img.width / img.height;
                        let drawWidth = canvas.width;
                        let drawHeight = canvas.height;
                        if (aspectRatio > 1) {
                            drawHeight = drawWidth / aspectRatio;
                        } else {
                            drawWidth = drawHeight * aspectRatio;
                        }
                        const x = (canvas.width - drawWidth) / 2;
                        const y = (canvas.height - drawHeight) / 2;
                        ctx.clearRect(0, 0, canvas.width, canvas.height);
                        ctx.drawImage(img, x, y, drawWidth, drawHeight);

                        window.drawAttendanceOverlay(ctx, canvas.width, canvas.height, {
                            timestamp: Date.now(),
                            latitude: currentLocation.lat,
                            longitude: currentLocation.lng,
                            distance: currentLocation.distance,
                            insideRadius: currentLocation.insideRadius,
                            label: branchLabel
                        });

                        assignCanvasToInput(canvas, elements.selfieInput, `${type}-selfie.jpg`, function(success) {
                            if (success) {
                                canvas.classList.remove('hidden');
                                if (elements.preview) {
                                    elements.preview.classList.add('hidden');
                                }
                                if (elements.video) {
                                    elements.video.classList.add('hidden');
                                }
                                if (elements.captureBtn) {
                                    elements.captureBtn.classList.add('hidden');
                                }
                                if (elements.submitBtn) {
                                    elements.submitBtn.classList.remove('hidden');
                                }
                            }
                        });
                    };
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }

            function submitAttendance(elements, type, url) {
                if (!elements.form || !elements.selfieInput) {
                    return;
                }

                if (!currentLocation.available) {
                    alert('Lokasi belum tersedia. Pastikan layanan lokasi aktif.');
                    return;
                }
                if (!currentLocation.insideRadius) {
                    alert('Anda berada di luar radius absensi yang diizinkan.');
                    return;
                }
                if (!elements.selfieInput.files || !elements.selfieInput.files.length) {
                    alert('Silakan ambil atau unggah foto selfie terlebih dahulu.');
                    return;
                }
                if (!csrfToken) {
                    alert('Token CSRF tidak ditemukan. Muat ulang halaman dan coba lagi.');
                    return;
                }

                updateHiddenInputs(elements, currentLocation);

                const formData = new FormData(elements.form);
                if (currentLocation.available) {
                    formData.set('latitude', currentLocation.lat);
                    formData.set('longitude', currentLocation.lng);
                }

                if (elements.submitBtn) {
                    elements.submitBtn.setAttribute('disabled', 'disabled');
                    elements.submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                }

                fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(function(response) {
                    if (!response.ok) {
                        throw new Error('HTTP error! status: ' + response.status);
                    }
                    return response.json();
                })
                .then(function(data) {
                    if (data.success) {
                        alert(type === 'checkin' ? 'Check in berhasil!' : 'Check out berhasil!');
                        window.location.reload();
                    } else {
                        alert('Error: ' + (data.message || 'Terjadi kesalahan tak dikenal.'));
                    }
                })
                .catch(function(error) {
                    console.error('Attendance submit error:', error);
                    alert('Terjadi kesalahan saat mengirim data: ' + error.message);
                })
                .finally(function() {
                    if (elements.submitBtn) {
                        elements.submitBtn.removeAttribute('disabled');
                        elements.submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    }
                });
            }

            // Initialize panels once
            updateLocationPanel(checkInElements, currentLocation, 'checkin');
            updateLocationPanel(checkOutElements, currentLocation, 'checkout');

            // Event bindings for check-in
            if (checkInElements.startBtn) {
                checkInElements.startBtn.addEventListener('click', function() {
                    startCamera(checkInElements, 'checkin');
                });
            }
            if (checkInElements.captureBtn) {
                checkInElements.captureBtn.addEventListener('click', function() {
                    capturePhoto(checkInElements, 'checkin');
                });
            }
            if (checkInElements.uploadInput) {
                checkInElements.uploadInput.addEventListener('change', function(event) {
                    const file = event.target.files ? event.target.files[0] : null;
                    handleUpload(checkInElements, file, 'checkin');
                });
            }
            if (checkInElements.form) {
                checkInElements.form.addEventListener('submit', function(event) {
                    event.preventDefault();
                    submitAttendance(checkInElements, 'checkin', checkInUrl);
                });
            }

            // Event bindings for check-out
            if (checkOutElements.startBtn) {
                checkOutElements.startBtn.addEventListener('click', function() {
                    startCamera(checkOutElements, 'checkout');
                });
            }
            if (checkOutElements.captureBtn) {
                checkOutElements.captureBtn.addEventListener('click', function() {
                    capturePhoto(checkOutElements, 'checkout');
                });
            }
            if (checkOutElements.uploadInput) {
                checkOutElements.uploadInput.addEventListener('change', function(event) {
                    const file = event.target.files ? event.target.files[0] : null;
                    handleUpload(checkOutElements, file, 'checkout');
                });
            }
            if (checkOutElements.form) {
                checkOutElements.form.addEventListener('submit', function(event) {
                    event.preventDefault();
                    submitAttendance(checkOutElements, 'checkout', checkOutUrl);
                });
            }
        });
    </script>
    @endpush
</x-employee-layout>