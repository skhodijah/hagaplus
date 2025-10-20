<x-employee-layout>
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

            <!-- Camera Test Section -->
            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl p-4 mb-6">
                <h3 class="text-lg font-semibold text-yellow-800 dark:text-yellow-200 mb-2">🧪 Test Kamera</h3>
                <p class="text-sm text-yellow-700 dark:text-yellow-300 mb-3">
                    Cek apakah kamera berfungsi dengan baik sebelum melakukan absensi. Pastikan menggunakan HTTPS atau localhost.
                </p>
                <div class="flex flex-col sm:flex-row gap-2 mb-2">
                    <button type="button" id="test-camera"
                            class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                        Test Kamera Sederhana
                    </button>
                    <button type="button" id="check-browser-support"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                        Cek Dukungan Browser
                    </button>
                </div>
                <div id="test-result" class="mt-2 text-sm"></div>
                <div id="browser-info" class="mt-2 text-xs text-gray-600 dark:text-gray-400 hidden">
                    <strong>Info Browser:</strong><br>
                    <span id="protocol-info"></span><br>
                    <span id="camera-api-info"></span><br>
                    <span id="secure-context-info"></span>
                </div>
            </div>

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
                                            <label for="upload-checkin-photo" class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg cursor-pointer transition-colors duration-200">
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
                                            <label for="upload-checkout-photo" class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg cursor-pointer transition-colors duration-200">
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
        // Attendance functionality with selfie camera
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Attendance JavaScript loaded successfully');

            // Policy Modal
            const policyModal = document.getElementById('policy-modal');
            const showPolicyBtn = document.getElementById('show-policy');
            const closePolicyModal = document.getElementById('close-policy-modal');
            const retryPolicyBtn = document.getElementById('retry-policy');
            const policyLoading = document.getElementById('policy-loading');
            const policyContent = document.getElementById('policy-content');
            const policyError = document.getElementById('policy-error');

            // Show policy modal
            showPolicyBtn.addEventListener('click', function() {
                policyModal.classList.remove('hidden');
                loadPolicy();
            });

            // Close modal when clicking the close button
            closePolicyModal.addEventListener('click', function() {
                policyModal.classList.add('hidden');
            });

            // Close modal when clicking outside the modal
            window.addEventListener('click', function(event) {
                if (event.target === policyModal) {
                    policyModal.classList.add('hidden');
                }
            });

            // Retry loading policy
            retryPolicyBtn?.addEventListener('click', loadPolicy);

            // Load policy data
            function loadPolicy() {
                policyLoading.classList.remove('hidden');
                policyContent.classList.add('hidden');
                policyError.classList.add('hidden');

                fetch('{{ route("employee.attendance.policy") }}', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success && data.data) {
                        // Update the modal with policy data
                        document.getElementById('policy-name').textContent = data.data.name || 'Kebijakan Standar';
                        document.getElementById('policy-work-days').textContent = data.data.work_days || 'Senin - Jumat';
                        document.getElementById('policy-start-time').textContent = data.data.start_time || '08:00';
                        document.getElementById('policy-end-time').textContent = data.data.end_time || '17:00';
                        document.getElementById('policy-late-tolerance').textContent = data.data.late_tolerance || '15 menit';
                        document.getElementById('policy-break-duration').textContent = data.data.break_duration || '60 menit';
                        document.getElementById('policy-methods').textContent = data.data.attendance_methods || 'Selfie';
                        
                        policyLoading.classList.add('hidden');
                        policyContent.classList.remove('hidden');
                    } else {
                        throw new Error('Invalid policy data');
                    }
                })
                .catch(error => {
                    console.error('Error loading policy:', error);
                    policyLoading.classList.add('hidden');
                    policyError.classList.remove('hidden');
                });
            }

            // Test camera availability
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                console.log('Camera API is supported');

                // Check if we're on HTTPS or localhost
                const isSecure = location.protocol === 'https:' || location.hostname === 'localhost' || location.hostname === '127.0.0.1';
                console.log('Is secure context:', isSecure);

                // Enumerate devices
                navigator.mediaDevices.enumerateDevices()
                    .then(function(devices) {
                        const videoDevices = devices.filter(device => device.kind === 'videoinput');
                        console.log('Available video devices:', videoDevices.length);
                        videoDevices.forEach(function(device, index) {
                            console.log('Camera ' + (index + 1) + ':', device.label || 'Camera ' + (index + 1));
                        });
                    })
                    .catch(function(err) {
                        console.log('Error enumerating devices:', err);
                    });
            } else {
                console.log('Camera API is NOT supported');
            }

            let checkinStream = null;
            let checkoutStream = null;
            let testStream = null;

            // Camera test functionality
            const testCameraBtn = document.getElementById('test-camera');
            const checkBrowserBtn = document.getElementById('check-browser-support');
            const testResultDiv = document.getElementById('test-result');
            const browserInfoDiv = document.getElementById('browser-info');
            const protocolInfoSpan = document.getElementById('protocol-info');
            const cameraApiInfoSpan = document.getElementById('camera-api-info');
            const secureContextInfoSpan = document.getElementById('secure-context-info');

            testCameraBtn.addEventListener('click', async function() {
                try {
                    testResultDiv.innerHTML = '<span class="text-blue-600">🔄 Menguji kamera...</span>';

                    // Check if we're on HTTPS or localhost
                    const isSecure = location.protocol === 'https:' || location.hostname === 'localhost' || location.hostname === '127.0.0.1';
                    console.log('Testing camera - Is secure context:', isSecure);

                    if (!isSecure) {
                        testResultDiv.innerHTML = '<span class="text-red-600">❌ Kamera memerlukan HTTPS. Gunakan HTTPS atau localhost.</span>';
                        return;
                    }

                    // Stop any existing test stream
                    if (testStream) {
                        testStream.getTracks().forEach(track => track.stop());
                    }

                    // More specific camera constraints
                    const constraints = {
                        video: {
                            width: { ideal: 640 },
                            height: { ideal: 480 },
                            facingMode: 'user' // Use front camera
                        },
                        audio: false
                    };

                    console.log('Requesting camera with constraints:', constraints);
                    testStream = await navigator.mediaDevices.getUserMedia(constraints);

                    console.log('Camera test successful, tracks:', testStream.getTracks().length);
                    testResultDiv.innerHTML = '<span class="text-green-600">✅ Kamera berhasil! Coba gunakan fitur absensi di atas.</span>';

                    // Stop test stream after 3 seconds
                    setTimeout(() => {
                        if (testStream) {
                            testStream.getTracks().forEach(track => track.stop());
                            testStream = null;
                        }
                        testResultDiv.innerHTML = '';
                    }, 3000);

                } catch (error) {
                    console.error('Camera test failed:', error);
                    console.error('Error name:', error.name);
                    console.error('Error message:', error.message);

                    if (error.name === 'NotAllowedError') {
                        testResultDiv.innerHTML = '<span class="text-red-600">❌ Akses kamera ditolak. Izinkan akses kamera di browser Anda dan refresh halaman.</span>';
                    } else if (error.name === 'NotFoundError') {
                        testResultDiv.innerHTML = '<span class="text-red-600">❌ Tidak ada kamera yang ditemukan. Pastikan kamera terhubung.</span>';
                    } else if (error.name === 'NotReadableError') {
                        testResultDiv.innerHTML = '<span class="text-red-600">❌ Kamera sedang digunakan aplikasi lain. Tutup aplikasi lain yang menggunakan kamera.</span>';
                    } else if (error.name === 'OverconstrainedError') {
                        testResultDiv.innerHTML = '<span class="text-red-600">❌ Kamera tidak mendukung resolusi yang diminta. Coba gunakan kamera lain.</span>';
                    } else if (error.name === 'SecurityError') {
                        testResultDiv.innerHTML = '<span class="text-red-600">❌ Kamera memerlukan HTTPS. Gunakan HTTPS atau localhost.</span>';
                    } else {
                        testResultDiv.innerHTML = '<span class="text-red-600">❌ Kamera gagal: ' + error.name + ' - ' + error.message + '</span>';
                    }
                }
            });

            // Browser support check functionality
            checkBrowserBtn.addEventListener('click', function() {
                const isSecure = location.protocol === 'https:' || location.hostname === 'localhost' || location.hostname === '127.0.0.1';
                const hasGetUserMedia = !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia);
                const isSecureContext = window.isSecureContext;

                protocolInfoSpan.textContent = 'Protocol: ' + location.protocol + ' (Hostname: ' + location.hostname + ')';
                cameraApiInfoSpan.textContent = 'Camera API (getUserMedia): ' + (hasGetUserMedia ? '✅ Supported' : '❌ Not supported');
                secureContextInfoSpan.textContent = 'Secure Context: ' + (isSecureContext ? '✅ Yes' : '❌ No');

                browserInfoDiv.classList.remove('hidden');

                // Overall assessment
                if (!hasGetUserMedia) {
                    testResultDiv.innerHTML = '<span class="text-red-600">❌ Browser tidak mendukung Camera API. Gunakan browser modern seperti Chrome, Firefox, atau Edge.</span>';
                } else if (!isSecureContext) {
                    testResultDiv.innerHTML = '<span class="text-red-600">❌ Kamera memerlukan secure context (HTTPS atau localhost).</span>';
                } else if (!isSecure) {
                    testResultDiv.innerHTML = '<span class="text-yellow-600">⚠️ Browser mendukung kamera tapi direkomendasikan menggunakan HTTPS untuk performa terbaik.</span>';
                } else {
                    testResultDiv.innerHTML = '<span class="text-green-600">✅ Browser mendukung kamera dengan baik. Jika masih bermasalah, coba test kamera di atas.</span>';
                }
            });

            // Check-in functionality
            const startCameraCheckinBtn = document.getElementById('start-camera-checkin');
            const captureCheckinBtn = document.getElementById('capture-checkin');
            const submitCheckinBtn = document.getElementById('submit-checkin');
            const checkinVideo = document.getElementById('checkin-video');
            const checkinCanvas = document.getElementById('checkin-canvas');
            const checkinForm = document.getElementById('checkin-form');
            const uploadCheckinPhoto = document.getElementById('upload-checkin-photo');

            // Check-out functionality
            const startCameraCheckoutBtn = document.getElementById('start-camera-checkout');
            const captureCheckoutBtn = document.getElementById('capture-checkout');
            const submitCheckoutBtn = document.getElementById('submit-checkout');
            const checkoutVideo = document.getElementById('checkout-video');
            const checkoutCanvas = document.getElementById('checkout-canvas');
            const checkoutForm = document.getElementById('checkout-form');
            const uploadCheckoutPhoto = document.getElementById('upload-checkout-photo');

            // Start camera for check-in
            startCameraCheckinBtn.addEventListener('click', async function() {
                try {
                    console.log('Requesting camera access for check-in...');

                    // Check if we're on HTTPS or localhost
                    const isSecure = location.protocol === 'https:' || location.hostname === 'localhost' || location.hostname === '127.0.0.1';
                    console.log('Check-in camera - Is secure context:', isSecure);

                    if (!isSecure) {
                        alert('Kamera memerlukan HTTPS. Gunakan HTTPS atau localhost untuk mengakses kamera.');
                        return;
                    }

                    // More specific camera constraints for better compatibility
                    const constraints = {
                        video: {
                            width: { ideal: 640, min: 320 },
                            height: { ideal: 480, min: 240 },
                            facingMode: 'user' // Use front camera
                        },
                        audio: false
                    };

                    console.log('Check-in camera constraints:', constraints);
                    checkinStream = await navigator.mediaDevices.getUserMedia(constraints);

                    console.log('Camera access granted for check-in, tracks:', checkinStream.getTracks().length);
                    checkinVideo.srcObject = checkinStream;
                    checkinVideo.classList.remove('hidden');
                    document.getElementById('camera-preview').classList.add('hidden');
                    startCameraCheckinBtn.classList.add('hidden');
                    captureCheckinBtn.classList.remove('hidden');

                    // Play video when ready
                    checkinVideo.onloadedmetadata = function() {
                        console.log('Check-in video metadata loaded, playing...');
                        checkinVideo.play();
                    };

                } catch (error) {
                    console.error('Check-in camera error:', error);
                    console.error('Error name:', error.name);
                    console.error('Error message:', error.message);

                    // Detailed error messages
                    if (error.name === 'NotAllowedError') {
                        alert('Akses kamera ditolak. Silakan izinkan akses kamera di browser Anda dan refresh halaman.');
                    } else if (error.name === 'NotFoundError') {
                        alert('Tidak ada kamera yang ditemukan. Pastikan kamera terhubung dan coba gunakan opsi upload foto.');
                    } else if (error.name === 'NotReadableError') {
                        alert('Kamera sedang digunakan aplikasi lain. Tutup aplikasi lain yang menggunakan kamera dan coba lagi.');
                    } else if (error.name === 'OverconstrainedError') {
                        alert('Kamera tidak mendukung resolusi yang diminta. Coba gunakan kamera lain atau opsi upload foto.');
                    } else if (error.name === 'SecurityError') {
                        alert('Kamera memerlukan HTTPS. Gunakan HTTPS atau localhost untuk mengakses kamera.');
                    } else {
                        alert('Tidak dapat mengakses kamera. Error: ' + error.name + ' - ' + error.message + '. Coba gunakan opsi upload foto.');
                    }
                }
            });

            // Capture photo for check-in
            captureCheckinBtn.addEventListener('click', function() {
                const context = checkinCanvas.getContext('2d');
                checkinCanvas.width = checkinVideo.videoWidth;
                checkinCanvas.height = checkinVideo.videoHeight;
                context.drawImage(checkinVideo, 0, 0);

                // Convert to blob
                checkinCanvas.toBlob(function(blob) {
                    const file = new File([blob], 'checkin-selfie.jpg', { type: 'image/jpeg' });
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    document.getElementById('checkin-selfie').files = dataTransfer.files;

                    // Stop camera
                    if (checkinStream) {
                        checkinStream.getTracks().forEach(track => track.stop());
                    }
                    checkinVideo.classList.add('hidden');
                    checkinCanvas.classList.remove('hidden');

                    captureCheckinBtn.classList.add('hidden');
                    submitCheckinBtn.classList.remove('hidden');
                }, 'image/jpeg', 0.8);
            });

            // Handle file upload for check-in
            uploadCheckinPhoto.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    // Validate file type
                    if (!file.type.startsWith('image/')) {
                        alert('Silakan pilih file gambar yang valid.');
                        return;
                    }

                    // Validate file size (5MB max)
                    if (file.size > 5 * 1024 * 1024) {
                        alert('Ukuran file maksimal 5MB.');
                        return;
                    }

                    // Create preview
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        checkinCanvas.width = 640;
                        checkinCanvas.height = 480;
                        const ctx = checkinCanvas.getContext('2d');
                        const img = new Image();
                        img.onload = function() {
                            // Calculate aspect ratio to fit canvas
                            const aspectRatio = img.width / img.height;
                            let drawWidth = checkinCanvas.width;
                            let drawHeight = checkinCanvas.height;

                            if (aspectRatio > 1) {
                                drawHeight = drawWidth / aspectRatio;
                            } else {
                                drawWidth = drawHeight * aspectRatio;
                            }

                            // Center the image
                            const x = (checkinCanvas.width - drawWidth) / 2;
                            const y = (checkinCanvas.height - drawHeight) / 2;

                            ctx.drawImage(img, x, y, drawWidth, drawHeight);

                            // Show canvas and submit button
                            checkinCanvas.classList.remove('hidden');
                            document.getElementById('camera-preview').classList.add('hidden');
                            submitCheckinBtn.classList.remove('hidden');
                        };
                        img.src = e.target.result;
                    };
                    reader.readAsDataURL(file);

                    // Set file to form
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    document.getElementById('checkin-selfie').files = dataTransfer.files;
                }
            });

            // Start camera for check-out
            startCameraCheckoutBtn.addEventListener('click', async function() {
                try {
                    console.log('Requesting camera access for check-out...');

                    // Check if we're on HTTPS or localhost
                    const isSecure = location.protocol === 'https:' || location.hostname === 'localhost' || location.hostname === '127.0.0.1';
                    console.log('Check-out camera - Is secure context:', isSecure);

                    if (!isSecure) {
                        alert('Kamera memerlukan HTTPS. Gunakan HTTPS atau localhost untuk mengakses kamera.');
                        return;
                    }

                    // More specific camera constraints for better compatibility
                    const constraints = {
                        video: {
                            width: { ideal: 640, min: 320 },
                            height: { ideal: 480, min: 240 },
                            facingMode: 'user' // Use front camera
                        },
                        audio: false
                    };

                    console.log('Check-out camera constraints:', constraints);
                    checkoutStream = await navigator.mediaDevices.getUserMedia(constraints);

                    console.log('Camera access granted for check-out, tracks:', checkoutStream.getTracks().length);
                    checkoutVideo.srcObject = checkoutStream;
                    checkoutVideo.classList.remove('hidden');
                    document.getElementById('camera-preview-checkout').classList.add('hidden');
                    startCameraCheckoutBtn.classList.add('hidden');
                    captureCheckoutBtn.classList.remove('hidden');

                    // Play video when ready
                    checkoutVideo.onloadedmetadata = function() {
                        console.log('Check-out video metadata loaded, playing...');
                        checkoutVideo.play();
                    };

                } catch (error) {
                    console.error('Check-out camera error:', error);
                    console.error('Error name:', error.name);
                    console.error('Error message:', error.message);

                    // Detailed error messages
                    if (error.name === 'NotAllowedError') {
                        alert('Akses kamera ditolak. Silakan izinkan akses kamera di browser Anda dan refresh halaman.');
                    } else if (error.name === 'NotFoundError') {
                        alert('Tidak ada kamera yang ditemukan. Pastikan kamera terhubung dan coba gunakan opsi upload foto.');
                    } else if (error.name === 'NotReadableError') {
                        alert('Kamera sedang digunakan aplikasi lain. Tutup aplikasi lain yang menggunakan kamera dan coba lagi.');
                    } else if (error.name === 'OverconstrainedError') {
                        alert('Kamera tidak mendukung resolusi yang diminta. Coba gunakan kamera lain atau opsi upload foto.');
                    } else if (error.name === 'SecurityError') {
                        alert('Kamera memerlukan HTTPS. Gunakan HTTPS atau localhost untuk mengakses kamera.');
                    } else {
                        alert('Tidak dapat mengakses kamera. Error: ' + error.name + ' - ' + error.message + '. Coba gunakan opsi upload foto.');
                    }
                }
            });

            // Capture photo for check-out
            captureCheckoutBtn.addEventListener('click', function() {
                const context = checkoutCanvas.getContext('2d');
                checkoutCanvas.width = checkoutVideo.videoWidth;
                checkoutCanvas.height = checkoutVideo.videoHeight;
                context.drawImage(checkoutVideo, 0, 0);

                // Convert to blob
                checkoutCanvas.toBlob(function(blob) {
                    const file = new File([blob], 'checkout-selfie.jpg', { type: 'image/jpeg' });
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    document.getElementById('checkout-selfie').files = dataTransfer.files;

                    // Stop camera
                    if (checkoutStream) {
                        checkoutStream.getTracks().forEach(track => track.stop());
                    }
                    checkoutVideo.classList.add('hidden');
                    checkoutCanvas.classList.remove('hidden');

                    captureCheckoutBtn.classList.add('hidden');
                    submitCheckoutBtn.classList.remove('hidden');
                }, 'image/jpeg', 0.8);
            });

            // Handle file upload for check-out
            uploadCheckoutPhoto.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    // Validate file type
                    if (!file.type.startsWith('image/')) {
                        alert('Silakan pilih file gambar yang valid.');
                        return;
                    }

                    // Validate file size (5MB max)
                    if (file.size > 5 * 1024 * 1024) {
                        alert('Ukuran file maksimal 5MB.');
                        return;
                    }

                    // Create preview
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        checkoutCanvas.width = 640;
                        checkoutCanvas.height = 480;
                        const ctx = checkoutCanvas.getContext('2d');
                        const img = new Image();
                        img.onload = function() {
                            // Calculate aspect ratio to fit canvas
                            const aspectRatio = img.width / img.height;
                            let drawWidth = checkoutCanvas.width;
                            let drawHeight = checkoutCanvas.height;

                            if (aspectRatio > 1) {
                                drawHeight = drawWidth / aspectRatio;
                            } else {
                                drawWidth = drawHeight * aspectRatio;
                            }

                            // Center the image
                            const x = (checkoutCanvas.width - drawWidth) / 2;
                            const y = (checkoutCanvas.height - drawHeight) / 2;

                            ctx.drawImage(img, x, y, drawWidth, drawHeight);

                            // Show canvas and submit button
                            checkoutCanvas.classList.remove('hidden');
                            document.getElementById('camera-preview-checkout').classList.add('hidden');
                            submitCheckoutBtn.classList.remove('hidden');
                        };
                        img.src = e.target.result;
                    };
                    reader.readAsDataURL(file);

                    // Set file to form
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    document.getElementById('checkout-selfie').files = dataTransfer.files;
                }
            });

            // Get user location
            function getLocation(callback) {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            callback(position.coords.latitude, position.coords.longitude);
                        },
                        function(error) {
                            console.log('Location error:', error);
                            callback(null, null);
                        }
                    );
                } else {
                    callback(null, null);
                }
            }

            // Submit check-in
            checkinForm.addEventListener('submit', function(e) {
                e.preventDefault();

                getLocation(function(latitude, longitude) {
                    document.getElementById('checkin-latitude').value = latitude;
                    document.getElementById('checkin-longitude').value = longitude;

                    const formData = new FormData(checkinForm);

                    fetch('{{ route("employee.attendance.check-in") }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Check in berhasil!');
                            location.reload(); // Reload to update dashboard calendar
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat check in');
                    });
                });
            });

            // Submit check-out
            checkoutForm.addEventListener('submit', function(e) {
                e.preventDefault();

                getLocation(function(latitude, longitude) {
                    document.getElementById('checkout-latitude').value = latitude;
                    document.getElementById('checkout-longitude').value = longitude;

                    const formData = new FormData(checkoutForm);

                    fetch('{{ route("employee.attendance.check-out") }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Check out berhasil!');
                            location.reload(); // Reload to update dashboard calendar
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat check out');
                    });
                });
            });
        });
    </script>
    @endpush
</x-employee-layout>