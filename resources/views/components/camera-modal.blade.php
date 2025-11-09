<!-- Camera Modal for Quick Check-in/Check-out -->
<div id="camera-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-4">
                <h3 id="modal-title" class="text-lg font-medium text-gray-900 dark:text-white">Check In Cepat</h3>
                <button id="close-camera-modal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="space-y-4">
                <!-- Location Status -->
                <div id="quick-location-status" class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 space-y-2">
                    <div class="flex flex-wrap items-center gap-4 text-sm">
                        <div class="text-gray-700 dark:text-gray-200">
                            <span class="font-semibold">Koordinat Anda:</span>
                            <span id="quick-location-coordinates">Menunggu lokasi...</span>
                        </div>
                        <div class="text-gray-700 dark:text-gray-200">
                            <span class="font-semibold">Jarak ke kantor:</span>
                            <span id="quick-location-distance">-</span>
                        </div>
                        <div class="text-gray-700 dark:text-gray-200">
                            <span class="font-semibold">Status:</span>
                            <span id="quick-location-status-text" class="font-semibold text-yellow-600 dark:text-yellow-300">Menunggu lokasi...</span>
                        </div>
                    </div>
                    <p id="quick-location-message" class="text-xs text-gray-600 dark:text-gray-300">
                        Pastikan layanan lokasi aktif sebelum melakukan absensi.
                    </p>
                </div>
                <div id="quick-location-alert" class="hidden bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg p-3 text-sm text-red-700 dark:text-red-200">
                    Lokasi Anda berada di luar radius absensi yang diizinkan.
                </div>

                <!-- Camera Preview -->
                <div class="relative">
                    <div id="quick-camera-preview" class="w-full aspect-video bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                        <div class="text-center">
                            <svg class="w-12 h-12 text-gray-400 dark:text-gray-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Klik tombol di bawah untuk mengaktifkan kamera</p>
                        </div>
                    </div>
                    <div id="quick-live-overlay" class="hidden absolute top-3 left-3 bg-black/60 text-white text-xs px-3 py-2 rounded-lg space-y-1">
                        <div id="quick-overlay-coordinates">Lat: -, Lng: -</div>
                        <div id="quick-overlay-distance">Jarak: -</div>
                        <div id="quick-overlay-status">Status: -</div>
                    </div>
                    <video id="quick-camera-video" class="hidden w-full rounded-lg"></video>
                    <canvas id="quick-camera-canvas" class="hidden"></canvas>
                </div>

                <!-- Camera Controls -->
                <div class="flex space-x-3">
                    <button type="button" id="quick-start-camera"
                            class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        Aktifkan Kamera
                    </button>
                    <button type="button" id="quick-capture-photo"
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
                    <label for="quick-upload-photo" id="quick-upload-label" class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg cursor-pointer transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        Upload Foto dari Galeri
                    </label>
                    <input type="file" id="quick-upload-photo" accept="image/*" class="hidden">
                </div>

                <!-- Hidden inputs -->
                <input type="file" id="quick-selfie" name="selfie" class="hidden" accept="image/*">
                <input type="hidden" id="quick-latitude" name="latitude">
                <input type="hidden" id="quick-longitude" name="longitude">

                <!-- Submit Button -->
                <button type="submit" id="quick-submit-checkin"
                        class="w-full bg-green-500 hover:bg-green-600 text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200 hidden items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span id="submit-button-text">Check In Sekarang</span>
                </button>
            </div>
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
    const submitBtn = document.getElementById('quick-submit-checkin');
    const video = document.getElementById('quick-camera-video');
    const canvas = document.getElementById('quick-camera-canvas');
    const preview = document.getElementById('quick-camera-preview');
    const uploadInput = document.getElementById('quick-upload-photo');
    const uploadLabel = document.getElementById('quick-upload-label');
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
        message: document.getElementById('quick-location-message'),
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

    function updateLocationStatus(location) {
        if (!statusPanel.wrapper) {
            return;
        }

        if (statusPanel.coordinates) {
            statusPanel.coordinates.textContent = location.available
                ? `${formatCoordinate(location.lat, '-')} , ${formatCoordinate(location.lng, '-')}`
                : 'Menunggu lokasi...';
        }
        if (statusPanel.distance) {
            statusPanel.distance.textContent = location.distance !== null ? formatDistance(location.distance) : '-';
        }
        if (statusPanel.status) {
            statusPanel.status.classList.remove('text-green-600', 'dark:text-green-300', 'text-red-600', 'dark:text-red-300', 'text-yellow-600', 'dark:text-yellow-300');
            if (!location.available) {
                statusPanel.status.textContent = 'Menunggu lokasi';
                statusPanel.status.classList.add('text-yellow-600', 'dark:text-yellow-300');
            } else if (location.insideRadius) {
                statusPanel.status.textContent = 'Dalam radius';
                statusPanel.status.classList.add('text-green-600', 'dark:text-green-300');
            } else {
                statusPanel.status.textContent = 'Di luar radius';
                statusPanel.status.classList.add('text-red-600', 'dark:text-red-300');
            }
        }
        if (statusPanel.message) {
            if (!location.available) {
                statusPanel.message.textContent = 'Aktifkan layanan lokasi pada perangkat dan pastikan browser memiliki izin lokasi.';
            } else if (!location.insideRadius) {
                statusPanel.message.textContent = 'Anda berada di luar radius absensi. Silakan mendekati lokasi cabang.';
            } else {
                statusPanel.message.textContent = 'Anda berada dalam radius absensi. Silakan lanjutkan proses absensi.';
            }
        }
        if (statusPanel.alert) {
            statusPanel.alert.classList.toggle('hidden', !location.available || location.insideRadius);
        }

        setButtonState(startCameraBtn, location.available && location.insideRadius);
        setLabelState(uploadLabel, uploadInput, location.available && location.insideRadius);

        if (latInput) {
            latInput.value = location.available ? location.lat : '';
        }
        if (lngInput) {
            lngInput.value = location.available ? location.lng : '';
        }

        if (!location.available || !location.insideRadius) {
            stopQuickCamera();
        }
    }

    function updateOverlay(location) {
        if (!overlay.wrapper) {
            return;
        }
        if (location.available && video && !video.classList.contains('hidden')) {
            overlay.wrapper.classList.remove('hidden');
            if (overlay.coordinates) {
                overlay.coordinates.textContent = `Lat: ${formatCoordinate(location.lat, '-')} | Lng: ${formatCoordinate(location.lng, '-')}`;
            }
            if (overlay.distance) {
                overlay.distance.textContent = location.distance !== null ? `Jarak: ${formatDistance(location.distance)}` : 'Jarak: -';
            }
            if (overlay.status) {
                overlay.status.textContent = location.insideRadius ? 'Status: Dalam radius' : 'Status: Di luar radius';
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

    function ensureSecureContext() {
        const isSecure = location.protocol === 'https:' || location.hostname === 'localhost' || location.hostname === '127.0.0.1';
        if (!isSecure) {
            alert('Kamera memerlukan HTTPS. Gunakan HTTPS atau localhost untuk mengakses kamera.');
        }
        return isSecure;
    }

    function stopQuickCamera() {
        if (quickStream) {
            quickStream.getTracks().forEach(function(track) { track.stop(); });
            quickStream = null;
        }
        if (video) {
            video.pause();
            video.srcObject = null;
            video.classList.add('hidden');
        }
        if (preview) {
            preview.classList.remove('hidden');
        }
        if (captureBtn) {
            captureBtn.classList.add('hidden');
        }
        if (submitBtn) {
            submitBtn.classList.add('hidden');
        }
        if (overlay.wrapper) {
            overlay.wrapper.classList.add('hidden');
        }
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
        if (!ensureSecureContext()) {
            return;
        }
        try {
            quickStream = await navigator.mediaDevices.getUserMedia(videoConstraints);
            if (video) {
                video.srcObject = quickStream;
                video.classList.remove('hidden');
                video.onloadedmetadata = function() {
                    video.play();
                    updateOverlay(currentLocation);
                };
            }
            if (preview) {
                preview.classList.add('hidden');
            }
            if (startCameraBtn) {
                startCameraBtn.classList.add('hidden');
            }
            if (captureBtn) {
                captureBtn.classList.remove('hidden');
            }
        } catch (error) {
            console.error('Quick camera error:', error);
            if (error.name === 'NotAllowedError') {
                alert('Akses kamera ditolak. Silakan izinkan akses kamera di browser Anda.');
            } else if (error.name === 'NotFoundError') {
                alert('Kamera tidak ditemukan. Pastikan perangkat Anda memiliki kamera yang terhubung.');
            } else if (error.name === 'NotReadableError') {
                alert('Kamera sedang digunakan oleh aplikasi lain. Tutup aplikasi lain yang menggunakan kamera.');
            } else {
                alert('Tidak dapat mengakses kamera. Error: ' + error.name + ' - ' + error.message);
            }
        }
    }

    function assignCanvasToInput(callback) {
        if (!canvas || !selfieInput) {
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
            const file = new File([blob], isCheckout ? 'quick-checkout-selfie.jpg' : 'quick-checkin-selfie.jpg', { type: 'image/jpeg' });
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            selfieInput.files = dataTransfer.files;
            if (typeof callback === 'function') {
                callback(true);
            }
        }, 'image/jpeg', 0.9);
    }

    function captureQuickPhoto() {
        if (!video || video.classList.contains('hidden')) {
            return;
        }
        const width = video.videoWidth || 640;
        const height = video.videoHeight || 480;
        canvas.width = width;
        canvas.height = height;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, width, height);

        window.drawAttendanceOverlay(ctx, width, height, {
            timestamp: Date.now(),
            latitude: currentLocation.lat,
            longitude: currentLocation.lng,
            distance: currentLocation.distance,
            insideRadius: currentLocation.insideRadius,
            label: branchLabel
        });

        assignCanvasToInput(function(success) {
            if (success) {
                canvas.classList.remove('hidden');
                submitBtn.classList.remove('hidden');
            }
        });

        stopQuickCamera();
    }

    function handleQuickUpload(file) {
        if (!file) {
            return;
        }
        if (!currentLocation.available || !currentLocation.insideRadius) {
            alert('Anda berada di luar radius absensi yang diizinkan.');
            uploadInput.value = '';
            return;
        }
        if (!file.type.startsWith('image/')) {
            alert('Silakan pilih file gambar yang valid.');
            uploadInput.value = '';
            return;
        }
        if (file.size > 5 * 1024 * 1024) {
            alert('Ukuran file maksimal 5MB.');
            uploadInput.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            const img = new Image();
            img.onload = function() {
                canvas.width = 640;
                canvas.height = 480;
                const ctx = canvas.getContext('2d');
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

                assignCanvasToInput(function(success) {
                    if (success) {
                        canvas.classList.remove('hidden');
                        submitBtn.classList.remove('hidden');
                    }
                });
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }

    function closeModal() {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        stopQuickCamera();
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
        modalTitle.textContent = checkout ? 'Check Out Cepat' : 'Check In Cepat';
        submitButtonText.textContent = checkout ? 'Check Out Sekarang' : 'Check In Sekarang';
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        updateLocationStatus(currentLocation);
        updateOverlay(currentLocation);
        if (selfieInput) {
            selfieInput.value = '';
        }
        if (canvas) {
            canvas.classList.add('hidden');
        }
        setButtonState(startCameraBtn, currentLocation.available && currentLocation.insideRadius);
        setLabelState(uploadLabel, uploadInput, currentLocation.available && currentLocation.insideRadius);
    }

    if (cameraTrigger) {
        cameraTrigger.addEventListener('click', function(event) {
            event.preventDefault();
            openModal(false);
        });
    }

    if (checkoutTrigger) {
        checkoutTrigger.addEventListener('click', function(event) {
            event.preventDefault();
            openModal(true);
        });
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', closeModal);
    }

    if (modal) {
        modal.addEventListener('click', function(event) {
            if (event.target === modal) {
                closeModal();
            }
        });
    }

    if (startCameraBtn) {
        startCameraBtn.addEventListener('click', function() {
            startQuickCamera();
        });
    }

    if (captureBtn) {
        captureBtn.addEventListener('click', function() {
            captureQuickPhoto();
        });
    }

    if (uploadInput) {
        uploadInput.addEventListener('change', function(event) {
            const file = event.target.files ? event.target.files[0] : null;
            handleQuickUpload(file);
        });
    }

    if (submitBtn) {
        submitBtn.addEventListener('click', function(event) {
            event.preventDefault();
            if (!currentLocation.available) {
                alert('Lokasi belum tersedia. Pastikan layanan lokasi aktif.');
                return;
            }
            if (!currentLocation.insideRadius) {
                alert('Anda berada di luar radius absensi yang diizinkan.');
                return;
            }
            if (!selfieInput.files || !selfieInput.files.length) {
                alert('Silakan ambil atau unggah foto selfie terlebih dahulu.');
                return;
            }

            if (latInput) {
                latInput.value = currentLocation.lat;
            }
            if (lngInput) {
                lngInput.value = currentLocation.lng;
            }

            const formData = new FormData();
            formData.append('selfie', selfieInput.files[0]);
            if (currentLocation.available) {
                formData.append('latitude', currentLocation.lat);
                formData.append('longitude', currentLocation.lng);
            }

            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : null;
            if (!csrfToken) {
                alert('Token CSRF tidak ditemukan. Muat ulang halaman dan coba lagi.');
                return;
            }

            submitBtn.setAttribute('disabled', 'disabled');
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');

            const url = isCheckout ? '{{ route("employee.attendance.check-out") }}' : '{{ route("employee.attendance.check-in") }}';

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
                    alert(isCheckout ? 'Check out berhasil!' : 'Check in berhasil!');
                    window.location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Terjadi kesalahan tak dikenal.'));
                }
            })
            .catch(function(error) {
                console.error('Quick attendance submit error:', error);
                alert('Terjadi kesalahan saat mengirim data: ' + error.message);
            })
            .finally(function() {
                submitBtn.removeAttribute('disabled');
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            });
        });
    }
});
</script>