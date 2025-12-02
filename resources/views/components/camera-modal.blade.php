<!-- Camera Modal for Attendance -->
<div id="camera-modal" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center p-4 transition-opacity duration-300">
    <div class="relative w-full max-w-lg bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden transform transition-all scale-100">
        
        <!-- Minimalist Header -->
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between bg-white dark:bg-gray-800">
            <div>
                <h3 id="modal-title" class="text-lg font-bold text-gray-900 dark:text-white tracking-tight">Foto Absensi</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Verifikasi wajah untuk kehadiran</p>
            </div>
            <button id="close-camera-modal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 space-y-6">
            
            <!-- Location Status (Soft Style) -->
            <div id="quick-location-status" class="flex items-center gap-4 p-4 rounded-xl bg-gray-50 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-700">
                <div class="w-10 h-10 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0 text-blue-600 dark:text-blue-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status Lokasi</span>
                        <span id="quick-location-status-text" class="text-xs font-bold px-2 py-0.5 rounded-full bg-gray-200 text-gray-600">Menunggu...</span>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-sm text-gray-600 dark:text-gray-300">Jarak ke kantor:</span>
                        <span id="quick-location-distance" class="text-sm font-bold text-gray-900 dark:text-white">-</span>
                    </div>
                    <!-- Hidden Coordinates -->
                    <div class="hidden">
                        <span id="quick-location-coordinates"></span>
                    </div>
                </div>
            </div>

            <!-- Alert (Soft Style) -->
            <div id="quick-location-alert" class="hidden p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800/50 flex items-start gap-3">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <p class="text-sm text-red-600 dark:text-red-300 font-medium">Anda berada di luar radius absensi.</p>
            </div>

            <!-- Camera Viewport -->
            <div class="relative w-full aspect-square rounded-2xl overflow-hidden bg-gray-100 dark:bg-gray-900 shadow-inner ring-1 ring-black/5 dark:ring-white/10">
                <!-- Placeholder -->
                <div id="quick-camera-preview" class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                    <div class="w-16 h-16 mb-4 rounded-full bg-gray-200 dark:bg-gray-800 flex items-center justify-center">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-medium">Kamera belum aktif</span>
                </div>
                
                <!-- Video (Mirrored via CSS) -->
                <video id="quick-camera-video" class="hidden w-full h-full object-cover" autoplay playsinline style="transform: scaleX(-1);"></video>
                
                <!-- Canvas (Result) -->
                <canvas id="quick-camera-canvas" class="hidden w-full h-full object-cover"></canvas>
                
                <!-- Minimal Overlay -->
                <div id="quick-live-overlay" class="hidden absolute bottom-0 inset-x-0 p-4 bg-gradient-to-t from-black/60 to-transparent text-white">
                    <div class="flex items-center justify-between text-xs font-medium">
                        <span id="quick-overlay-distance" class="bg-black/30 px-2 py-1 rounded-lg backdrop-blur-sm">Jarak: -</span>
                        <span id="quick-overlay-status" class="bg-black/30 px-2 py-1 rounded-lg backdrop-blur-sm">Status: -</span>
                    </div>
                    <div id="quick-overlay-coordinates" class="hidden"></div>
                </div>
            </div>

            <!-- Controls -->
            <div class="flex flex-col gap-3">
                <button type="button" id="quick-start-camera"
                        class="w-full py-3.5 rounded-xl bg-gray-900 dark:bg-white text-white dark:text-gray-900 font-bold hover:bg-gray-800 dark:hover:bg-gray-100 transition-all transform active:scale-[0.98] shadow-lg shadow-gray-200 dark:shadow-none flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    Aktifkan Kamera
                </button>
                
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" id="quick-capture-photo"
                            class="hidden col-span-2 py-3.5 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-700 transition-all transform active:scale-[0.98] shadow-lg shadow-blue-200 dark:shadow-none flex items-center justify-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-white animate-pulse"></div>
                        Ambil Foto
                    </button>
                    
                    <button type="button" id="quick-retake-photo"
                            class="hidden py-3.5 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-semibold hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                        Foto Ulang
                    </button>
                    
                    <button type="submit" id="quick-submit-checkin"
                            class="hidden py-3.5 rounded-xl bg-emerald-500 text-white font-bold hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-200 dark:shadow-none flex items-center justify-center gap-2">
                        <span id="submit-button-text">Check In</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </button>
                </div>
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
            facingMode: 'user',
            width: { ideal: 1280 },
            height: { ideal: 720 }
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
            statusPanel.status.classList.remove('bg-emerald-100', 'text-emerald-700', 'bg-red-100', 'text-red-700', 'bg-gray-200', 'text-gray-600');
            if (!location.available) {
                statusPanel.status.textContent = 'Menunggu';
                statusPanel.status.classList.add('bg-gray-200', 'text-gray-600');
            } else if (location.insideRadius) {
                statusPanel.status.textContent = 'Dalam Radius';
                statusPanel.status.classList.add('bg-emerald-100', 'text-emerald-700');
            } else {
                statusPanel.status.textContent = 'Luar Radius';
                statusPanel.status.classList.add('bg-red-100', 'text-red-700');
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
                overlay.status.textContent = location.insideRadius ? 'Dalam Radius' : 'Luar Radius';
                overlay.status.className = location.insideRadius 
                    ? 'bg-emerald-500/80 px-2 py-1 rounded-lg backdrop-blur-sm' 
                    : 'bg-red-500/80 px-2 py-1 rounded-lg backdrop-blur-sm';
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
            Swal.fire('Lokasi Belum Tersedia', 'Pastikan layanan lokasi aktif.', 'warning');
            return;
        }
        if (!currentLocation.insideRadius) {
            Swal.fire('Di Luar Jangkauan', 'Anda berada di luar radius absensi yang diizinkan.', 'error');
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
            let msg = 'Pastikan Anda memberikan izin akses kamera.';
            
            if (error.name === 'OverconstrainedError') {
                // Fallback: try without constraints
                try {
                    quickStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
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
                    return; // Success on fallback
                } catch (fallbackError) {
                    console.error('Fallback camera error:', fallbackError);
                    msg = 'Kamera tidak mendukung resolusi yang diminta.';
                }
            } else if (error.name === 'NotAllowedError' || error.name === 'PermissionDeniedError') {
                msg = 'Akses kamera ditolak. Silakan izinkan akses kamera di browser Anda.';
            } else if (error.name === 'NotFoundError') {
                msg = 'Perangkat kamera tidak ditemukan.';
            } else if (error.name === 'NotReadableError') {
                msg = 'Kamera sedang digunakan oleh aplikasi lain.';
            }
            
            Swal.fire('Gagal Akses Kamera', msg, 'error');
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
        
        // FLIP CONTEXT HORIZONTALLY to match the mirrored video preview
        ctx.translate(size, 0);
        ctx.scale(-1, 1);
        
        ctx.drawImage(video, offsetX, offsetY, size, size, 0, 0, size, size);
        
        // Reset transform for text overlay (so text is readable)
        ctx.setTransform(1, 0, 0, 1, 0, 0);

        // Inline drawing logic to match attendance page
        const fontSize = Math.max(16, Math.floor(size * 0.03)); // Dynamic font size
        const padding = Math.floor(fontSize * 0.8);
        const bgHeight = fontSize * 4.5;

        ctx.fillStyle = 'rgba(0, 0, 0, 0.6)';
        ctx.fillRect(0, size - bgHeight, size, bgHeight);
        
        ctx.fillStyle = 'white';
        ctx.textBaseline = 'bottom';
        
        // Line 1: Branch Name
        ctx.font = `bold ${fontSize}px sans-serif`;
        ctx.fillText(branchLabel, padding, size - (fontSize * 2.8));

        // Line 2: Timestamp
        ctx.font = `${fontSize * 0.9}px sans-serif`;
        const dateStr = new Date().toLocaleString('id-ID', { dateStyle: 'full', timeStyle: 'medium' });
        ctx.fillText(dateStr, padding, size - (fontSize * 1.6));
        
        // Line 3: Coordinates
        if (currentLocation && currentLocation.available) {
            const locStr = `Lat: ${currentLocation.lat.toFixed(6)}, Lng: ${currentLocation.lng.toFixed(6)}`;
            ctx.font = `${fontSize * 0.75}px monospace`;
            ctx.fillText(locStr, padding, size - (fontSize * 0.5));
        }

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
            Swal.fire('Lokasi Belum Tersedia', 'Pastikan layanan lokasi aktif.', 'warning');
            return;
        }
        if (!currentLocation.insideRadius) {
            Swal.fire('Di Luar Jangkauan', 'Anda berada di luar radius absensi yang diizinkan.', 'error');
            return;
        }
        isCheckout = checkout;
        modalTitle.textContent = checkout ? 'Check Out' : 'Check In';
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
                Swal.fire('Error', 'Anda berada di luar radius absensi yang diizinkan.', 'error');
                return;
            }
            if (!selfieInput.files || !selfieInput.files.length) {
                Swal.fire('Error', 'Silakan ambil foto selfie terlebih dahulu.', 'error');
                return;
            }

            const formData = new FormData();
            formData.append('selfie', selfieInput.files[0]);
            formData.append('latitude', currentLocation.lat);
            formData.append('longitude', currentLocation.lng);

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!csrfToken) {
                Swal.fire('Error', 'Token CSRF tidak ditemukan.', 'error');
                return;
            }

            submitBtn.setAttribute('disabled', 'disabled');
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mengirim...';

            const url = isCheckout ? '{{ route("employee.attendance.check-out") }}' : '{{ route("employee.attendance.check-in") }}';

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: isCheckout ? 'Check out berhasil!' : 'Check in berhasil!',
                        confirmButtonColor: '#10B981',
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire('Gagal', data.message || 'Terjadi kesalahan.', 'error');
                    submitBtn.removeAttribute('disabled');
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    submitBtn.innerHTML = isCheckout ? 'Check Out' : 'Check In';
                }
            })
            .catch(error => {
                console.error('Submit error:', error);
                Swal.fire('Error', 'Terjadi kesalahan saat mengirim data.', 'error');
                submitBtn.removeAttribute('disabled');
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                submitBtn.innerHTML = isCheckout ? 'Check Out' : 'Check In';
            });
        });
    }
});
</script>