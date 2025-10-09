<x-admin-layout>
    <div class="py-6 md:py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 md:mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100">Complete Payment</h1>
                        <p class="mt-1 text-gray-600 dark:text-gray-400">Upload payment proof to complete your subscription request</p>
                    </div>
                    <a href="{{ route('admin.subscription.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fa-solid fa-arrow-left mr-2"></i>Back to Subscription
                    </a>
                </div>
            </div>

            <!-- Request Details -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Request Details</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Transaction ID</label>
                        <p class="text-sm text-gray-900 dark:text-white font-mono">{{ $subscriptionRequest->transaction_id }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Package</label>
                        <p class="text-sm text-gray-900 dark:text-white">{{ $subscriptionRequest->package_name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Amount</label>
                        <p class="text-lg font-semibold text-green-600 dark:text-green-400">Rp {{ number_format($subscriptionRequest->amount, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Request Date</label>
                        <p class="text-sm text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($subscriptionRequest->created_at)->format('d M Y H:i') }}</p>
                    </div>
                </div>

                @if($subscriptionRequest->notes)
                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Notes</label>
                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ $subscriptionRequest->notes }}</p>
                    </div>
                @endif
            </div>

            <!-- Payment Methods -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Select Payment Method</h2>

                @if($paymentMethods->count() > 0)
                    <form id="paymentForm" method="POST" action="{{ route('admin.subscription.transaction.process', $subscriptionRequest->id) }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Payment Method Selection -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                Choose Payment Method <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($paymentMethods as $paymentMethod)
                                    <div class="payment-method-option border border-gray-200 dark:border-gray-600 rounded-lg p-4 cursor-pointer transition-all hover:border-blue-500 {{ $loop->first ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : '' }}"
                                         data-method-id="{{ $paymentMethod->id }}">
                                        <div class="flex items-start space-x-3">
                                            <input type="radio" name="payment_method_id" value="{{ $paymentMethod->id }}"
                                                   class="mt-1" {{ $loop->first ? 'checked' : '' }}>
                                            <div class="flex-1">
                                                <div class="flex items-center space-x-2 mb-2">
                                                    @if($paymentMethod->type === 'qris')
                                                        <div class="w-6 h-6 bg-purple-100 dark:bg-purple-900/30 rounded flex items-center justify-center">
                                                            <i class="fa-solid fa-qrcode text-purple-600 dark:text-purple-400 text-xs"></i>
                                                        </div>
                                                    @elseif($paymentMethod->type === 'bank_transfer')
                                                        <div class="w-6 h-6 bg-blue-100 dark:bg-blue-900/30 rounded flex items-center justify-center">
                                                            <i class="fa-solid fa-building-columns text-blue-600 dark:text-blue-400 text-xs"></i>
                                                        </div>
                                                    @else
                                                        <div class="w-6 h-6 bg-green-100 dark:bg-green-900/30 rounded flex items-center justify-center">
                                                            <i class="fa-solid fa-mobile-screen-button text-green-600 dark:text-green-400 text-xs"></i>
                                                        </div>
                                                    @endif
                                                    <span class="font-medium text-gray-900 dark:text-white">{{ $paymentMethod->name }}</span>
                                                </div>

                                                @if($paymentMethod->type === 'bank_transfer')
                                                    <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                                                        @if($paymentMethod->account_name)
                                                            <p><strong>Account Name:</strong> {{ $paymentMethod->account_name }}</p>
                                                        @endif
                                                        @if($paymentMethod->account_number)
                                                            <p><strong>Account Number:</strong> <span class="font-mono">{{ $paymentMethod->account_number }}</span></p>
                                                        @endif
                                                        @if($paymentMethod->bank_name)
                                                            <p><strong>Bank:</strong> {{ $paymentMethod->bank_name }}</p>
                                                        @endif
                                                    </div>
                                                @elseif($paymentMethod->type === 'qris')
                                                    <div class="text-sm text-gray-600 dark:text-gray-400">
                                                        <p>Scan QRIS code to pay</p>
                                                        @if($paymentMethod->qris_image)
                                                            <div class="mt-2">
                                                                <img src="{{ $paymentMethod->qris_image_url }}" alt="QRIS Code" class="w-20 h-20 object-contain border border-gray-200 dark:border-gray-600 rounded">
                                                            </div>
                                                        @endif
                                                    </div>
                                                @else
                                                    <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                                                        @if($paymentMethod->account_number)
                                                            <p><strong>Phone/Account:</strong> {{ $paymentMethod->account_number }}</p>
                                                        @endif
                                                        @if($paymentMethod->account_name)
                                                            <p><strong>Name:</strong> {{ $paymentMethod->account_name }}</p>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Payment Proof Upload -->
                        <div class="mb-6">
                            <label for="payment_proof" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Upload Payment Proof <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md hover:border-gray-400 dark:hover:border-gray-500 transition-colors">
                                <div class="space-y-1 text-center">
                                    <i class="fa-solid fa-cloud-upload-alt text-gray-400 text-3xl mb-3"></i>
                                    <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                        <label for="payment_proof" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Upload payment proof</span>
                                            <input id="payment_proof" name="payment_proof" type="file" accept="image/*" class="sr-only" required>
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        PNG, JPG, GIF up to 5MB
                                    </p>
                                </div>
                            </div>
                            <div id="file-preview" class="mt-3 hidden">
                                <img id="preview-image" src="" alt="Payment Proof Preview" class="max-w-xs max-h-48 object-contain border border-gray-200 dark:border-gray-600 rounded">
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                <i class="fa-solid fa-upload mr-2"></i>Upload Payment Proof
                            </button>
                        </div>
                    </form>
                @else
                    <div class="text-center py-8">
                        <i class="fa-solid fa-exclamation-triangle text-yellow-500 text-4xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Payment Methods Available</h3>
                        <p class="text-gray-500 dark:text-gray-400">Please contact superadmin to set up payment methods.</p>
                    </div>
                @endif
            </div>

            <!-- Instructions -->
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
                <div class="flex items-start space-x-3">
                    <i class="fa-solid fa-info-circle text-blue-600 dark:text-blue-400 mt-1"></i>
                    <div>
                        <h3 class="text-lg font-medium text-blue-900 dark:text-blue-100 mb-2">Payment Instructions</h3>
                        <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-1">
                            <li>• Select your preferred payment method from the options above</li>
                            <li>• Make the payment using the details provided</li>
                            <li>• Take a clear photo/screenshot of the payment proof</li>
                            <li>• Upload the payment proof using the form above</li>
                            <li>• Your request will be processed within 1-2 business days</li>
                        </ul>
                        <p class="mt-3 text-sm text-blue-700 dark:text-blue-300">
                            <strong>Note:</strong> Make sure the payment proof clearly shows the transaction details, amount, and date.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Payment method selection
        document.querySelectorAll('.payment-method-option').forEach(option => {
            option.addEventListener('click', function() {
                // Remove selected class from all options
                document.querySelectorAll('.payment-method-option').forEach(opt => {
                    opt.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
                });

                // Add selected class to clicked option
                this.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');

                // Check the radio button
                const radio = this.querySelector('input[type="radio"]');
                radio.checked = true;
            });
        });

        // File preview
        document.getElementById('payment_proof').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('file-preview');
            const previewImage = document.getElementById('preview-image');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                preview.classList.add('hidden');
            }
        });
    </script>
</x-admin-layout>