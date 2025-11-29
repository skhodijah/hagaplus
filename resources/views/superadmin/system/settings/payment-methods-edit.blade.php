<x-superadmin-layout>
    <div class="py-6 md:py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 md:mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100">Edit Payment Method</h1>
                        <p class="mt-1 text-gray-600 dark:text-gray-400">Update payment method details</p>
                    </div>
                    <a href="{{ route('superadmin.payment-methods.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fa-solid fa-arrow-left mr-2"></i>Back to Payment Methods
                    </a>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <form method="POST" action="{{ route('superadmin.payment-methods.update', $paymentMethod) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Payment Method Name -->
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Payment Method Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $paymentMethod->name) }}"
                               class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('name') border-red-300 dark:border-red-600 @enderror"
                               placeholder="e.g., BCA Transfer, QRIS, GoPay" required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Payment Type -->
                    <div class="mb-6">
                        <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Payment Type <span class="text-red-500">*</span>
                        </label>
                        <select name="type" id="type"
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('type') border-red-300 dark:border-red-600 @enderror" required>
                            <option value="">Select payment type</option>
                            <option value="bank_transfer" {{ old('type', $paymentMethod->type) == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="qris" {{ old('type', $paymentMethod->type) == 'qris' ? 'selected' : '' }}>QRIS</option>
                            <option value="ewallet" {{ old('type', $paymentMethod->type) == 'ewallet' ? 'selected' : '' }}>E-Wallet</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bank Transfer Fields -->
                    <div id="bankFields" class="{{ $paymentMethod->type !== 'bank_transfer' ? 'hidden' : '' }} space-y-6">
                        <div>
                            <label for="account_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Account Name
                            </label>
                            <input type="text" name="account_name" id="account_name" value="{{ old('account_name', $paymentMethod->account_name) }}"
                                   class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                   placeholder="e.g., PT. Company Name">
                            @error('account_name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="account_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Account Number
                            </label>
                            <input type="text" name="account_number" id="account_number" value="{{ old('account_number', $paymentMethod->account_number) }}"
                                   class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                   placeholder="e.g., 1234567890">
                            @error('account_number')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="bank_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Bank Name
                            </label>
                            <input type="text" name="bank_name" id="bank_name" value="{{ old('bank_name', $paymentMethod->bank_name) }}"
                                   class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                   placeholder="e.g., Bank Central Asia (BCA)">
                            @error('bank_name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- QRIS Fields -->
                    <div id="qrisFields" class="{{ $paymentMethod->type !== 'qris' ? 'hidden' : '' }} space-y-6">
                        <div>
                            <label for="qris_image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                QRIS Image
                            </label>
                            @if($paymentMethod->qris_image)
                                <div class="mb-3">
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Current QRIS Image:</p>
                                    <img src="{{ $paymentMethod->qris_image_url }}" alt="Current QRIS" class="w-24 h-24 object-contain border border-gray-200 dark:border-gray-600 rounded">
                                </div>
                            @endif
                            <input type="file" name="qris_image" id="qris_image" accept="image/*"
                                   class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('qris_image') border-red-300 dark:border-red-600 @enderror">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Upload new QRIS code image (JPEG, PNG, JPG, GIF). Max 2MB. Leave empty to keep current image.</p>
                            @error('qris_image')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <input type="hidden" name="qris_data" id="qris_data" value="{{ old('qris_data', $paymentMethod->qris_data) }}">
                            <p id="qris_status" class="mt-2 text-sm text-gray-500 hidden"></p>
                        </div>
                    </div>

                    <!-- E-Wallet Fields -->
                    <div id="ewalletFields" class="{{ $paymentMethod->type !== 'ewallet' ? 'hidden' : '' }} space-y-6">
                        <div>
                            <label for="account_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Account Name
                            </label>
                            <input type="text" name="account_name" id="ewallet_account_name" value="{{ old('account_name', $paymentMethod->account_name) }}"
                                   class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                   placeholder="e.g., John Doe">
                            @error('account_name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="account_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Phone/Account Number
                            </label>
                            <input type="text" name="account_number" id="ewallet_account_number" value="{{ old('account_number', $paymentMethod->account_number) }}"
                                   class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                   placeholder="e.g., 081234567890">
                            @error('account_number')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Description
                        </label>
                        <textarea name="description" id="description" rows="3"
                                  class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                  placeholder="Optional description or instructions">{{ old('description', $paymentMethod->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Active Status -->
                    <div class="mb-6">
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $paymentMethod->is_active) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm text-gray-900 dark:text-white">
                                Active (available for selection)
                            </label>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center justify-end space-x-3">
                        <a href="{{ route('superadmin.payment-methods.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                            <i class="fa-solid fa-save mr-2"></i>Update Payment Method
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
    <script>
        document.getElementById('type').addEventListener('change', function() {
            const type = this.value;
            const bankFields = document.getElementById('bankFields');
            const qrisFields = document.getElementById('qrisFields');
            const ewalletFields = document.getElementById('ewalletFields');

            // Hide all fields first
            bankFields.classList.add('hidden');
            qrisFields.classList.add('hidden');
            ewalletFields.classList.add('hidden');

            // Show relevant fields based on type
            if (type === 'bank_transfer') {
                bankFields.classList.remove('hidden');
            } else if (type === 'qris') {
                qrisFields.classList.remove('hidden');
            } else if (type === 'ewallet') {
                ewalletFields.classList.remove('hidden');
            }
        });

        // Trigger change event on page load
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('type').dispatchEvent(new Event('change'));
        });

        // QR Code Extraction
        document.getElementById('qris_image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            const status = document.getElementById('qris_status');
            status.textContent = 'Scanning QR code...';
            status.classList.remove('hidden', 'text-green-600', 'text-red-600');
            status.classList.add('text-gray-500');

            const reader = new FileReader();
            reader.onload = function(event) {
                const img = new Image();
                img.onload = function() {
                    const canvas = document.createElement('canvas');
                    const context = canvas.getContext('2d');
                    canvas.width = img.width;
                    canvas.height = img.height;
                    context.drawImage(img, 0, 0, img.width, img.height);
                    const imageData = context.getImageData(0, 0, img.width, img.height);
                    const code = jsQR(imageData.data, imageData.width, imageData.height, {
                        inversionAttempts: "dontInvert",
                    });

                    if (code) {
                        console.log("Found QR code", code.data);
                        document.getElementById('qris_data').value = code.data;
                        status.textContent = 'QR Code data extracted successfully!';
                        status.classList.remove('text-gray-500');
                        status.classList.add('text-green-600');
                    } else {
                        console.log("No QR code found.");
                        status.textContent = 'Could not extract QR data. Please ensure the image is clear.';
                        status.classList.remove('text-gray-500');
                        status.classList.add('text-red-600');
                    }
                };
                img.src = event.target.result;
            };
            reader.readAsDataURL(file);
        });
    </script>
</x-superadmin-layout>
