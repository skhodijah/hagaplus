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
                    <label for="quick-upload-photo" class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg cursor-pointer transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        Upload Foto dari Galeri
                    </label>
                    <input type="file" id="quick-upload-photo" accept="image/*" class="hidden">
                </div>

                <!-- Hidden inputs -->
                <input type="file" id="quick-selfie" name="selfie" class="hidden" accept="image/*">

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
    const uploadInput = document.getElementById('quick-upload-photo');
    const modalTitle = document.getElementById('modal-title');
    const submitButtonText = document.getElementById('submit-button-text');

    let quickStream = null;
    let isCheckout = false;
    let branchName = '';

    console.log('Camera modal initialized');
    console.log('Check-in trigger:', cameraTrigger);
    console.log('Check-out trigger:', checkoutTrigger);

    // Open modal for check-in
    if (cameraTrigger) {
        cameraTrigger.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Check-in button clicked');
            isCheckout = false;
            branchName = cameraTrigger.getAttribute('data-branch') || 'Unknown Branch';
            modalTitle.textContent = 'Check In Cepat';
            submitButtonText.textContent = 'Check In Sekarang';
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        });
    }

    // Open modal for check-out
    if (checkoutTrigger) {
        checkoutTrigger.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Check-out button clicked');
            isCheckout = true;
            branchName = checkoutTrigger.getAttribute('data-branch') || 'Unknown Branch';
            modalTitle.textContent = 'Check Out Cepat';
            submitButtonText.textContent = 'Check Out Sekarang';
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        });
    }

    // Close modal
    closeBtn.addEventListener('click', function() {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        stopQuickCamera();
    });

    // Close on outside click
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            stopQuickCamera();
        }
    });

    function stopQuickCamera() {
        if (quickStream) {
            quickStream.getTracks().forEach(track => track.stop());
            quickStream = null;
        }
        video.classList.add('hidden');
        document.getElementById('quick-camera-preview').classList.remove('hidden');
        startCameraBtn.classList.remove('hidden');
        captureBtn.classList.add('hidden');
        submitBtn.classList.add('hidden');
    }

    // Start camera
    startCameraBtn.addEventListener('click', async function() {
        try {
            const isSecure = location.protocol === 'https:' || location.hostname === 'localhost' || location.hostname === '127.0.0.1';
            if (!isSecure) {
                alert('Kamera memerlukan HTTPS. Gunakan HTTPS atau localhost untuk mengakses kamera.');
                return;
            }

            const constraints = {
                video: {
                    width: { ideal: 640, min: 320 },
                    height: { ideal: 480, min: 240 },
                    facingMode: 'user'
                },
                audio: false
            };

            // Check if camera is available
            const devices = await navigator.mediaDevices.enumerateDevices();
            const videoDevices = devices.filter(device => device.kind === 'videoinput');

            if (videoDevices.length === 0) {
                alert('Tidak ada kamera yang tersedia di perangkat ini.');
                return;
            }

            quickStream = await navigator.mediaDevices.getUserMedia(constraints);
            video.srcObject = quickStream;
            video.classList.remove('hidden');
            document.getElementById('quick-camera-preview').classList.add('hidden');
            startCameraBtn.classList.add('hidden');
            captureBtn.classList.remove('hidden');

            video.onloadedmetadata = function() {
                video.play();
            };

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
    });

    // Capture photo
    captureBtn.addEventListener('click', function() {
        const context = canvas.getContext('2d');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        context.drawImage(video, 0, 0);

        canvas.toBlob(function(blob) {
            const file = new File([blob], 'quick-selfie.jpg', { type: 'image/jpeg' });
            // Create a new DataTransfer and set files directly
            const dt = new DataTransfer();
            dt.items.add(file);
            document.getElementById('quick-selfie').files = dt.files;

            stopQuickCamera();
            canvas.classList.remove('hidden');
            submitBtn.classList.remove('hidden');
        }, 'image/jpeg', 0.8);
    });

    // Handle file upload
    uploadInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            if (!file.type.startsWith('image/')) {
                alert('Silakan pilih file gambar yang valid.');
                return;
            }

            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran file maksimal 5MB.');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                canvas.width = 640;
                canvas.height = 480;
                const ctx = canvas.getContext('2d');
                const img = new Image();
                img.onload = function() {
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

                    ctx.drawImage(img, x, y, drawWidth, drawHeight);

                    canvas.classList.remove('hidden');
                    submitBtn.classList.remove('hidden');
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);

            // Create a new DataTransfer and set files directly
            const dt = new DataTransfer();
            dt.items.add(file);
            document.getElementById('quick-selfie').files = dt.files;
        }
    });

    // Submit check-in
    submitBtn.addEventListener('click', function(e) {
        e.preventDefault();
        console.log('Submit button clicked');
        submitQuickCheckin();
    });

    function submitQuickCheckin() {
        console.log('Submitting check-in...');
        const formData = new FormData();
        const selfieInput = document.getElementById('quick-selfie');

        console.log('Selfie input:', selfieInput);
        console.log('Selfie files:', selfieInput.files);
        console.log('Selfie files length:', selfieInput.files.length);

        if (selfieInput.files[0]) {
            formData.append('selfie', selfieInput.files[0]);
            console.log('Added selfie to form data');
        } else {
            console.log('No selfie file found - checking canvas...');
            // Check if we have canvas data
            const canvas = document.getElementById('quick-camera-canvas');
            if (canvas && canvas.style.display !== 'none') {
                canvas.toBlob(function(blob) {
                    const file = new File([blob], 'quick-selfie.jpg', { type: 'image/jpeg' });
                    formData.append('selfie', file);
                    console.log('Added canvas blob to form data');
                    submitFormData(formData);
                }, 'image/jpeg', 0.8);
                return; // Exit early, submitFormData will be called in callback
            }
        }

        submitFormData(formData);
    }

    function submitFormData(formData) {
        console.log('Submitting form data...');

        const url = isCheckout ? '{{ route("employee.attendance.check-out") }}' : '{{ route("employee.attendance.check-in") }}';
        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            if (!response.ok) {
                throw new Error('HTTP error! status: ' + response.status);
            }
            return response.text(); // Get text first to debug
        })
        .then(text => {
            console.log('Raw response text:', text);
            try {
                const data = JSON.parse(text);
                console.log('Parsed response data:', data);
                if (data.success) {
                    alert(isCheckout ? 'Check out berhasil!' : 'Check in berhasil!');
                    modal.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (e) {
                console.error('JSON parse error:', e);
                console.error('Response was not JSON. Full response:', text);
                alert('Server mengembalikan response yang tidak valid. Periksa console untuk detail.');
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            alert('Terjadi kesalahan saat check in: ' + error.message);
        });
    }
});
</script>