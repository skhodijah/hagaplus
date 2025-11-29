<x-admin-layout>
    <div class="py-8" x-data="{ selectedMethod: {{ $paymentMethods->first()->id ?? 'null' }} }">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Complete Your Payment</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Please select a payment method and upload your proof of payment.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Payment Methods -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Payment Method Selection -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Select Payment Method</h2>
                        </div>
                        <div class="p-6">
                            @if($paymentMethods->count() > 0)
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    @foreach($paymentMethods as $method)
                                        <div @click="selectedMethod = {{ $method->id }}"
                                             :class="{ 'ring-2 ring-blue-500 bg-blue-50 dark:bg-blue-900/20': selectedMethod === {{ $method->id }}, 'border-gray-200 dark:border-gray-700 hover:border-blue-300 dark:hover:border-blue-700': selectedMethod !== {{ $method->id }} }"
                                             class="cursor-pointer rounded-xl border p-4 transition-all duration-200 flex items-center space-x-4">
                                            <div class="flex-shrink-0">
                                                <!-- Icon based on type -->
                                                @if($method->type === 'bank_transfer')
                                                    <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center text-blue-600 dark:text-blue-400">
                                                        <i class="fa-solid fa-building-columns"></i>
                                                    </div>
                                                @elseif($method->type === 'qris')
                                                    <div class="w-10 h-10 rounded-full bg-purple-100 dark:bg-purple-900/50 flex items-center justify-center text-purple-600 dark:text-purple-400">
                                                        <i class="fa-solid fa-qrcode"></i>
                                                    </div>
                                                @else
                                                    <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/50 flex items-center justify-center text-green-600 dark:text-green-400">
                                                        <i class="fa-solid fa-wallet"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <h3 class="font-medium text-gray-900 dark:text-white">{{ $method->name }}</h3>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst(str_replace('_', ' ', $method->type)) }}</p>
                                            </div>
                                            <div class="ml-auto" x-show="selectedMethod === {{ $method->id }}">
                                                <i class="fa-solid fa-check-circle text-blue-500 text-xl"></i>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Dynamic Payment Details -->
                                <div class="mt-8 bg-gray-50 dark:bg-gray-700/30 rounded-xl p-6 border border-gray-100 dark:border-gray-700 transition-all duration-300"
                                     x-show="selectedMethod" x-transition>
                                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4">Payment Details</h3>
                                    
                                    <!-- Total Amount Display -->
                                    <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-100 dark:border-blue-800 flex flex-col sm:flex-row justify-between items-center text-center sm:text-left">
                                        <span class="text-gray-600 dark:text-gray-300 font-medium mb-2 sm:mb-0">Total Amount to Transfer</span>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-2xl font-bold text-blue-600 dark:text-blue-400">Rp {{ number_format($subscriptionRequest->amount, 0, ',', '.') }}</span>
                                            <button onclick="navigator.clipboard.writeText('{{ $subscriptionRequest->amount }}')" class="text-blue-400 hover:text-blue-600 transition-colors" title="Copy Amount">
                                                <i class="fa-regular fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    @foreach($paymentMethods as $method)
                                        <div x-show="selectedMethod === {{ $method->id }}" style="display: none;">
                                            @if($method->type === 'bank_transfer')
                                                <div class="space-y-4">
                                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center p-4 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600">
                                                        <div>
                                                            <p class="text-xs text-gray-500 dark:text-gray-400">Bank Name</p>
                                                            <p class="font-semibold text-gray-900 dark:text-white text-lg">{{ $method->bank_name }}</p>
                                                        </div>
                                                        <div class="mt-2 sm:mt-0 text-right">
                                                            <!-- Placeholder for bank logo if needed -->
                                                            <i class="fa-solid fa-building-columns text-gray-300 text-2xl"></i>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center p-4 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600">
                                                        <div>
                                                            <p class="text-xs text-gray-500 dark:text-gray-400">Account Number</p>
                                                            <div class="flex items-center space-x-2">
                                                                <p class="font-mono font-bold text-gray-900 dark:text-white text-xl tracking-wider" id="acc-num-{{ $method->id }}">{{ $method->account_number }}</p>
                                                                <button onclick="navigator.clipboard.writeText('{{ $method->account_number }}')" class="text-gray-400 hover:text-blue-500 transition-colors" title="Copy">
                                                                    <i class="fa-regular fa-copy"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center p-4 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600">
                                                        <div>
                                                            <p class="text-xs text-gray-500 dark:text-gray-400">Account Name</p>
                                                            <p class="font-medium text-gray-900 dark:text-white">{{ $method->account_name }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif($method->type === 'qris')
                                                <div class="text-center" x-data x-init="$nextTick(() => { generateQRIS('{{ $method->qris_data }}', {{ $subscriptionRequest->amount }}, 'qrcode-{{ $method->id }}') })">
                                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Scan this QR code with your preferred payment app</p>
                                                    <div class="flex justify-center">
                                                        <div id="qrcode-{{ $method->id }}" class="inline-block p-4 bg-white rounded-xl shadow-sm border border-gray-200 w-64 h-64 flex items-center justify-center">
                                                            @if($method->qris_image)
                                                                <img src="{{ $method->qris_image_url }}" alt="QRIS Code" class="w-full h-full object-contain">
                                                            @else
                                                                <span class="text-gray-500">Loading QR...</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="space-y-4">
                                                    <div class="p-4 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600">
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">Payment Instructions</p>
                                                        <p class="font-medium text-gray-900 dark:text-white mt-1">{{ $method->description ?? 'Please follow the instructions provided.' }}</p>
                                                    </div>
                                                    @if($method->account_number)
                                                    <div class="p-4 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600">
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">Number/ID</p>
                                                        <div class="flex items-center space-x-2">
                                                            <p class="font-mono font-bold text-gray-900 dark:text-white text-xl">{{ $method->account_number }}</p>
                                                            <button onclick="navigator.clipboard.writeText('{{ $method->account_number }}')" class="text-gray-400 hover:text-blue-500 transition-colors" title="Copy">
                                                                <i class="fa-regular fa-copy"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    @endif
                                                    @if($method->account_name)
                                                    <div class="p-4 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600">
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">Account Name</p>
                                                        <p class="font-medium text-gray-900 dark:text-white">{{ $method->account_name }}</p>
                                                    </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <p class="text-gray-500">No payment methods available.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Upload Form -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Upload Proof</h2>
                        </div>
                        <div class="p-6">
                            <form method="POST" action="{{ route('admin.subscription.transaction.process', $subscriptionRequest->id) }}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="payment_method_id" :value="selectedMethod">
                                
                                <div class="mb-6">
                                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-xl hover:border-blue-500 dark:hover:border-blue-500 transition-colors cursor-pointer bg-gray-50 dark:bg-gray-900/50"
                                         onclick="document.getElementById('payment_proof').click()">
                                        <div class="space-y-1 text-center">
                                            <i class="fa-solid fa-cloud-arrow-up text-blue-500 text-4xl mb-3"></i>
                                            <div class="flex text-sm text-gray-600 dark:text-gray-400 justify-center">
                                                <span class="font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500">Click to upload</span>
                                                <span class="pl-1">or drag and drop</span>
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF up to 5MB</p>
                                        </div>
                                        <input id="payment_proof" name="payment_proof" type="file" accept="image/*" class="sr-only" required onchange="previewFile(this)">
                                    </div>
                                    <div id="preview-container" class="mt-4 hidden text-center">
                                        <img id="preview-img" src="" alt="Preview" class="max-h-64 mx-auto rounded-lg shadow-md border border-gray-200">
                                        <button type="button" onclick="clearFile()" class="mt-2 text-sm text-red-500 hover:text-red-700">Remove</button>
                                    </div>
                                </div>

                                <button type="submit" class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center">
                                    <i class="fa-solid fa-paper-plane mr-2"></i> Submit Payment Proof
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Summary -->
                <div class="space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden sticky top-6">
                        <div class="p-6 bg-gray-50 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Order Summary</h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-500 dark:text-gray-400">Transaction ID</span>
                                <span class="font-mono font-medium text-gray-900 dark:text-white">{{ $subscriptionRequest->transaction_id }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-500 dark:text-gray-400">Package</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $subscriptionRequest->package_name }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-500 dark:text-gray-400">Date</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($subscriptionRequest->created_at)->format('d M Y') }}</span>
                            </div>
                            
                            <div class="pt-4 border-t border-gray-100 dark:border-gray-700">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-semibold text-gray-900 dark:text-white">Total</span>
                                    <span class="text-2xl font-bold text-blue-600 dark:text-blue-400">Rp {{ number_format($subscriptionRequest->amount, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="px-6 pb-6">
                            <form action="{{ route('admin.subscription.cancel-payment', $subscriptionRequest->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this transaction?');">
                                @csrf
                                <button type="submit" class="block w-full py-2 text-center text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 text-sm font-medium transition-colors">
                                    Cancel Transaction
                                </button>
                            </form>
                            <a href="{{ route('admin.subscription.index') }}" class="block w-full py-2 text-center text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 text-sm font-medium transition-colors mt-2">
                                Return to List
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
    <script>
        function previewFile(input) {
            const preview = document.getElementById('preview-img');
            const container = document.getElementById('preview-container');
            const file = input.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    container.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        }

        function clearFile() {
            document.getElementById('payment_proof').value = '';
            document.getElementById('preview-container').classList.add('hidden');
        }

        // QRIS Generation Logic
        function pad(number) {
            return number < 10 ? "0" + number : number.toString();
        }

        function toCRC16(input) {
            let crc = 0xffff;
            for (let i = 0; i < input.length; i++) {
                crc ^= input.charCodeAt(i) << 8;
                for (let j = 0; j < 8; j++) {
                    crc = crc & 0x8000 ? (crc << 1) ^ 0x1021 : crc << 1;
                }
            }
            let hex = (crc & 0xffff).toString(16).toUpperCase();
            return hex.length === 3 ? "0" + hex : hex;
        }

        function makeString(qris, { nominal } = {}) {
            if (!qris) return null;
            if (!nominal) return null;

            let qrisModified = qris.slice(0, -4).replace("010211", "010212");
            let qrisParts = qrisModified.split("5802ID");

            if (qrisParts.length < 2) return qris; // Fallback if format doesn't match

            let amount = "54" + pad(nominal.toString().length) + nominal;
            amount += "5802ID";

            let output = qrisParts[0].trim() + amount + qrisParts[1].trim();
            output += toCRC16(output);

            return output;
        }

        function generateQRIS(qrisData, amount, elementId) {
            if (!qrisData || !amount) return;

            try {
                const qrisDinamis = makeString(qrisData, { nominal: amount });
                const container = document.getElementById(elementId);
                
                if (container && qrisDinamis) {
                    container.innerHTML = ""; // Clear previous
                    QRCode.toCanvas(
                        qrisDinamis,
                        { margin: 2, width: 256, color: { dark: "#000000", light: "#ffffff" } },
                        function (err, canvas) {
                            if (err) {
                                console.error("QR Code Error:", err);
                                return;
                            }
                            canvas.style.width = "100%";
                            canvas.style.height = "auto";
                            container.appendChild(canvas);
                        }
                    );
                }
            } catch (e) {
                console.error("Error generating QRIS:", e);
            }
        }
    </script>
</x-admin-layout>