<!-- Camera Modal for Attendance -->
<div id="camera-modal" class="fixed inset-0 bg-black/50 overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center p-4">
    <div class="relative w-full max-w-2xl bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden">
        <!-- Modal Header -->
        <div class="relative bg-blue-600 dark:bg-blue-700 px-6 py-4">
            <button id="close-camera-modal" class="absolute top-4 right-4 text-white/80 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 id="modal-title" class="text-lg font-semibold text-white">Foto Absensi</h3>
                    <p class="text-blue-100 text-sm">Ambil foto selfie untuk verifikasi</p>
                </div>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="p-6 space-y-4">
            <!-- Location Status -->
            <div id="quick-location-status" class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 border border-blue-200 dark:border-blue-800">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="grid grid-cols-2 gap-2 text-xs mb-2">
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Koordinat:</span>
                                <span id="quick-location-coordinates" class="ml-1 font-medium text-gray-900 dark:text-white">Menunggu...</span>
                            </div>
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Jarak:</span>
                                <span id="quick-location-distance" class="ml-1 font-medium text-gray-900 dark:text-white">-</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-yellow-400"></div>
                            <span id="quick-location-status-text" class="text-sm font-medium text-yellow-600 dark:text-yellow-400">Menunggu lokasi...</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alert -->
            <div id="quick-location-alert" class="hidden bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg p-3">
                <div class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <p class="text-sm text-red-700 dark:text-red-300">Anda berada di luar radius absensi yang diizinkan.</p>
                </div>
            </div>

            <!-- Camera/Photo Container -->
            <div class="relative">
                <!-- Camera Preview (before camera starts) -->
                <div id="quick-camera-preview" class="w-full aspect-square bg-gray-100 dark:bg-gray-700 flex items-center justify-center border-2 border-gray-200 dark:border-gray-600">
                    <div class="text-center p-8">
                        <div class="w-16 h-16 bg-gray-200 dark:bg-gray-600 rounded-lg flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-gray-400 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300 font-medium">Kamera Belum Aktif</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Klik tombol di bawah untuk memulai</p>
                    </div>
                </div>
                
                <!-- Live Video -->
                <video id="quick-camera-video" class="hidden w-full aspect-square object-cover bg-black" autoplay playsinline></video>
                
                <!-- Captured Photo -->
                <canvas id="quick-camera-canvas" class="hidden w-full aspect-square object-cover"></canvas>
                
                <!-- Live Location Overlay (only shown during video) -->
                <div id="quick-live-overlay" class="hidden absolute bottom-3 left-3 right-3 bg-black/70 text-white text-xs px-3 py-2 rounded space-y-1">
                    <div id="quick-overlay-coordinates">Lat: -, Lng: -</div>
                    <div id="quick-overlay-distance">Jarak: -</div>
                    <div id="quick-overlay-status">Status: -</div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3">
                <!-- Start Camera Button -->
                <button type="button" id="quick-start-camera"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg transition-colors flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    <span>Aktifkan Kamera</span>
                </button>
                
                <!-- Capture Photo Button -->
                <button type="button" id="quick-capture-photo"
                        class="hidden flex-1 bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-4 rounded-lg transition-colors items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>Ambil Foto</span>
                </button>
                
                <!-- Retake Photo Button -->
                <button type="button" id="quick-retake-photo"
                        class="hidden flex-1 bg-gray-600 hover:bg-gray-700 text-white font-medium py-3 px-4 rounded-lg transition-colors items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    <span>Foto Ulang</span>
                </button>
                
                <!-- Submit Button -->
                <button type="submit" id="quick-submit-checkin"
                        class="hidden flex-1 bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-4 rounded-lg transition-colors items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span id="submit-button-text">Check In</span>
                </button>
            </div>

            <!-- Hidden inputs -->
            <input type="file" id="quick-selfie" name="selfie" class="hidden" accept="image/*">
            <input type="hidden" id="quick-latitude" name="latitude">
            <input type="hidden" id="quick-longitude" name="longitude">
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('camera-modal');
    const closeBtn = document.getElementById('close-camera-modal');
    const cameraTrigger = document.querySelector('[data-camera-trigger]');
    const checkoutTrigger = document.querySelector('[data-camera-checkout]');

    const startCameraBtn = document.getElementById('quick-start-camera');
    const captureBtn = document.getElementById('quick-capture-photo');
    const retakeBtn = document.getElementById('quick-retake-photo');
    const submitBtn = document.getElementById('quick-submit-checkin');
    const video = document.getElementById('quick-camera-video');
    const canvas = document.getElementById('quick-camera-canvas');
    const preview = document.getElementById('quick-camera-preview');
    const selfieInput = document.getElementById('quick-selfie');
    const latInput = document.getElementById('quick-latitude');
    const lngInput = document.getElementById('quick-longitude');
    const modalTitle = document.getElementById('modal-title');
    const submitButtonText = document.getElementById('submit-button-text');

    const statusPanel = {
        wrapper: document.getElementById('quick-location-status'),
        coordinates: document.getElementById('quick-location-coordinates'),
        distance: document.getElementById('quick-location-distance'),
        status: document.getElementById('quick-location-status-text'),
        alert: document.getElementById('quick-location-alert')
    };

    const overlay = {
        wrapper: document.getElementById('quick-live-overlay'),
        coordinates: document.getElementById('quick-overlay-coordinates'),
        distance: document.getElementById('quick-overlay-distance'),
        status: document.getElementById('quick-overlay-status')
    };

    const locationManager = window.AttendanceLocationManager;
    const branchLabel = window.branchName || 'Lokasi Kantor';
    let currentLocation = locationManager ? locationManager.getData() : { available: false, lat: null, lng: null, distance: null, insideRadius: false, error: 'Lokasi belum tersedia.' };
    let quickStream = null;
    let isCheckout = false;

    const videoConstraints = {
        video: {
            width: { ideal: 1280, min: 640 },
            height: { ideal: 1280, min: 640 },
            facingMode: 'user',
            aspectRatio: 1
        },
        audio: false
    };

    function setButtonState(button, enabled) {
        if (!button) return;
        if (enabled) {
            button.removeAttribute('disabled');
            button.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            button.setAttribute('disabled', 'disabled');
            button.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }

    function formatCoordinate(value, fallback) {
        return (typeof value === 'number' && !Number.isNaN(value)) ? value.toFixed(6) : fallback;
    }

    function formatDistance(value) {
        return (typeof value === 'number' && !Number.isNaN(value)) ? value.toFixed(1) + ' m' : '-';
    }

    function updateLocationStatus(location) {
        if (!statusPanel.wrapper) return;

        if (statusPanel.coordinates) {
            statusPanel.coordinates.textContent = location.available
                ? `${formatCoordinate(location.lat, '-')}, ${formatCoordinate(location.lng, '-')}`
                : 'Menunggu...';
        }
        if (statusPanel.distance) {
            statusPanel.distance.textContent = formatDistance(location.distance);
        }
        if (statusPanel.status) {
            statusPanel.status.classList.remove('text-green-600', 'dark:text-green-400', 'text-red-600', 'dark:text-red-400', 'text-yellow-600', 'dark:text-yellow-400');
            if (!location.available) {
                statusPanel.status.textContent = 'Menunggu lokasi';
                statusPanel.status.classList.add('text-yellow-600', 'dark:text-yellow-400');
            } else if (location.insideRadius) {
                statusPanel.status.textContent = 'Dalam radius ✓';
                statusPanel.status.classList.add('text-green-600', 'dark:text-green-400');
            } else {
                statusPanel.status.textContent = 'Di luar radius';
                statusPanel.status.classList.add('text-red-600', 'dark:text-red-400');
            }
        }
        if (statusPanel.alert) {
            statusPanel.alert.classList.toggle('hidden', !location.available || location.insideRadius);
        }

        setButtonState(startCameraBtn, location.available && location.insideRadius);
        if (latInput) latInput.value = location.available ? location.lat : '';
        if (lngInput) lngInput.value = location.available ? location.lng : '';

        if (!location.available || !location.insideRadius) {
            stopQuickCamera();
        }
    }

    function updateOverlay(location) {
        if (!overlay.wrapper) return;
        if (location.available && video && !video.classList.contains('hidden')) {
            overlay.wrapper.classList.remove('hidden');
            if (overlay.coordinates) {
                overlay.coordinates.textContent = `Lat: ${formatCoordinate(location.lat, '-')} | Lng: ${formatCoordinate(location.lng, '-')}`;
            }
            if (overlay.distance) {
                overlay.distance.textContent = `Jarak: ${formatDistance(location.distance)}`;
            }
            if (overlay.status) {
                overlay.status.textContent = location.insideRadius ? 'Status: Dalam radius ✓' : 'Status: Di luar radius';
            }
        } else {
            overlay.wrapper.classList.add('hidden');
        }
    }

    if (locationManager) {
        locationManager.subscribe(function(location) {
            currentLocation = location;
            updateLocationStatus(location);
            updateOverlay(location);
        });
    } else {
        updateLocationStatus(currentLocation);
    }

    function stopQuickCamera() {
        if (quickStream) {
            quickStream.getTracks().forEach(track => track.stop());
            quickStream = null;
        }
        if (video) {
            video.pause();
            video.srcObject = null;
            video.classList.add('hidden');
        }
        if (overlay.wrapper) overlay.wrapper.classList.add('hidden');
    }

    function resetModal() {
        stopQuickCamera();
        preview.classList.remove('hidden');
        video.classList.add('hidden');
        canvas.classList.add('hidden');
        startCameraBtn.classList.remove('hidden');
        captureBtn.classList.add('hidden');
        retakeBtn.classList.add('hidden');
        submitBtn.classList.add('hidden');
        if (selfieInput) selfieInput.value = '';
    }

    async function startQuickCamera() {
        if (!currentLocation.available) {
            alert('Lokasi belum tersedia. Pastikan layanan lokasi aktif.');
            return;
        }
        if (!currentLocation.insideRadius) {
            alert('Anda berada di luar radius absensi yang diizinkan.');
            return;
        }
        
        try {
            quickStream = await navigator.mediaDevices.getUserMedia(videoConstraints);
            if (video) {
                video.srcObject = quickStream;
                video.classList.remove('hidden');
                video.onloadedmetadata = () => {
                    video.play();
                    updateOverlay(currentLocation);
                };
            }
            preview.classList.add('hidden');
            startCameraBtn.classList.add('hidden');
            captureBtn.classList.remove('hidden');
        } catch (error) {
            console.error('Camera error:', error);
            if (error.name === 'NotAllowedError') {
                alert('Akses kamera ditolak. Silakan izinkan akses kamera di browser Anda.');
            } else if (error.name === 'NotFoundError') {
                alert('Kamera tidak ditemukan.');
            } else if (error.name === 'NotReadableError') {
                alert('Kamera sedang digunakan oleh aplikasi lain.');
            } else {
                alert('Tidak dapat mengakses kamera: ' + error.message);
            }
        }
    }

    function assignCanvasToInput(callback) {
        if (!canvas || !selfieInput) {
            if (typeof callback === 'function') callback(false);
            return;
        }
        canvas.toBlob(blob => {
            if (!blob) {
                if (typeof callback === 'function') callback(false);
                return;
            }
            const file = new File([blob], isCheckout ? 'checkout.jpg' : 'checkin.jpg', { type: 'image/jpeg' });
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            selfieInput.files = dataTransfer.files;
            if (typeof callback === 'function') callback(true);
        }, 'image/jpeg', 0.92);
    }

    function captureQuickPhoto() {
        if (!video || video.classList.contains('hidden')) return;
        
        const size = Math.min(video.videoWidth, video.videoHeight);
        canvas.width = size;
        canvas.height = size;
        
        const ctx = canvas.getContext('2d');
        const offsetX = (video.videoWidth - size) / 2;
        const offsetY = (video.videoHeight - size) / 2;
        
        ctx.drawImage(video, offsetX, offsetY, size, size, 0, 0, size, size);

        window.drawAttendanceOverlay(ctx, size, size, {
            timestamp: Date.now(),
            latitude: currentLocation.lat,
            longitude: currentLocation.lng,
            distance: currentLocation.distance,
            insideRadius: currentLocation.insideRadius,
            label: branchLabel
        });

        assignCanvasToInput(success => {
            if (success) {
                stopQuickCamera();
                video.classList.add('hidden');
                canvas.classList.remove('hidden');
                captureBtn.classList.add('hidden');
                retakeBtn.classList.remove('hidden');
                submitBtn.classList.remove('hidden');
            }
        });
    }

    function retakePhoto() {
        canvas.classList.add('hidden');
        retakeBtn.classList.add('hidden');
        submitBtn.classList.add('hidden');
        if (selfieInput) selfieInput.value = '';
        startQuickCamera();
    }

    function closeModal() {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        resetModal();
    }

    function openModal(checkout) {
        if (!currentLocation.available) {
            alert('Lokasi belum tersedia. Pastikan layanan lokasi aktif.');
            return;
        }
        if (!currentLocation.insideRadius) {
            alert('Anda berada di luar radius absensi yang diizinkan.');
            return;
        }
        isCheckout = checkout;
        modalTitle.textContent = checkout ? 'Foto Check Out' : 'Foto Check In';
        submitButtonText.textContent = checkout ? 'Check Out' : 'Check In';
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        resetModal();
        updateLocationStatus(currentLocation);
        setButtonState(startCameraBtn, currentLocation.available && currentLocation.insideRadius);
    }

    if (cameraTrigger) {
        cameraTrigger.addEventListener('click', e => {
            e.preventDefault();
            openModal(false);
        });
    }

    if (checkoutTrigger) {
        checkoutTrigger.addEventListener('click', e => {
            e.preventDefault();
            openModal(true);
        });
    }

    if (closeBtn) closeBtn.addEventListener('click', closeModal);
    if (modal) {
        modal.addEventListener('click', e => {
            if (e.target === modal) closeModal();
        });
    }

    if (startCameraBtn) startCameraBtn.addEventListener('click', startQuickCamera);
    if (captureBtn) captureBtn.addEventListener('click', captureQuickPhoto);
    if (retakeBtn) retakeBtn.addEventListener('click', retakePhoto);

    if (submitBtn) {
        submitBtn.addEventListener('click', e => {
            e.preventDefault();
            if (!currentLocation.available || !currentLocation.insideRadius) {
                alert('Anda berada di luar radius absensi yang diizinkan.');
                return;
            }
            if (!selfieInput.files || !selfieInput.files.length) {
                alert('Silakan ambil foto selfie terlebih dahulu.');
                return;
            }

            const formData = new FormData();
            formData.append('selfie', selfieInput.files[0]);
            formData.append('latitude', currentLocation.lat);
            formData.append('longitude', currentLocation.lng);

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!csrfToken) {
                alert('Token CSRF tidak ditemukan.');
                return;
            }

            submitBtn.setAttribute('disabled', 'disabled');
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');

            const url = isCheckout ? '{{ route("employee.attendance.check-out") }}' : '{{ route("employee.attendance.check-in") }}';

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: { 'X-CSRF-TOKEN': csrfToken }
            })
            .then(response => response.ok ? response.json() : Promise.reject('HTTP error'))
            .then(data => {
                if (data.success) {
                    alert(isCheckout ? 'Check out berhasil!' : 'Check in berhasil!');
                    window.location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Terjadi kesalahan.'));
                }
            })
            .catch(error => {
                console.error('Submit error:', error);
                alert('Terjadi kesalahan saat mengirim data.');
            })
            .finally(() => {
                submitBtn.removeAttribute('disabled');
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            });
        });
    }
});
</script>