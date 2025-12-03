<x-superadmin-layout>
    <div class="container-fluid px-4 py-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create New Subscription</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Add a new subscription for an instansi</p>
            </div>
            <a href="{{ route('superadmin.subscriptions.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-all duration-200">
                <i class="fa-solid fa-arrow-left mr-2"></i>Back
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Form -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Subscription Information</h2>
                    </div>
                    <div class="p-6">
                        <form method="POST" action="{{ route('superadmin.subscriptions.store') }}" id="subscriptionForm">
                            @csrf
                            
                            @if(isset($subscriptionRequest))
                                <div class="mb-6 bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 p-4 rounded">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="fa-solid fa-info-circle text-blue-400"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-blue-700 dark:text-blue-300">
                                                Processing subscription request #{{ $subscriptionRequest->transaction_id }}.
                                                Please verify details before creating.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="request_id" value="{{ $subscriptionRequest->id }}">
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="instansi_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Instansi <span class="text-red-500">*</span></label>
                                    <select name="instansi_id" id="instansi_id" required
                                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white @error('instansi_id') border-red-300 dark:border-red-600 @enderror">
                                        <option value="">Select Instansi</option>
                                        @foreach($instansis as $instansi)
                                            <option value="{{ $instansi->id }}" 
                                                    data-email="{{ $instansi->email ?? '' }}"
                                                    data-phone="{{ $instansi->phone ?? '' }}"
                                                    {{ (old('instansi_id') ?? ($subscriptionRequest->instansi_id ?? '')) == $instansi->id ? 'selected' : '' }}>
                                                {{ $instansi->nama_instansi }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('instansi_id')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="package_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Package <span class="text-red-500">*</span></label>
                                    <select name="package_id" id="package_id" required
                                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white @error('package_id') border-red-300 dark:border-red-600 @enderror">
                                        <option value="">Select Package</option>
                                        @foreach($packages as $package)
                                            <option value="{{ $package->id }}" 
                                                    data-price="{{ $package->price }}"
                                                    data-name="{{ $package->name }}"
                                                    {{ (old('package_id') ?? ($subscriptionRequest->package_id ?? '')) == $package->id ? 'selected' : '' }}>
                                                {{ $package->name }} - Rp {{ number_format($package->price, 0, ',', '.') }}/month
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('package_id')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Start Date <span class="text-red-500">*</span></label>
                                    <input type="date" name="start_date" id="start_date" value="{{ old('start_date', now()->format('Y-m-d')) }}" required
                                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white @error('start_date') border-red-300 dark:border-red-600 @enderror">
                                    @error('start_date')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">End Date <span class="text-red-500">*</span></label>
                                    <input type="date" name="end_date" id="end_date" value="{{ old('end_date', now()->addMonth()->format('Y-m-d')) }}" required
                                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white @error('end_date') border-red-300 dark:border-red-600 @enderror">
                                    @error('end_date')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Price (Rp) <span class="text-red-500">*</span></label>
                                    <input type="number" name="price" id="price" value="{{ old('price') ?? ($subscriptionRequest->amount ?? '') }}" required min="0"
                                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white @error('price') border-red-300 dark:border-red-600 @enderror">
                                    @error('price')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Price will auto-update when package is selected</p>
                                </div>
                            </div>

                            <div class="mt-6 flex items-center justify-end space-x-3">
                                <a href="{{ route('superadmin.subscriptions.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-all duration-200">
                                    <i class="fa-solid fa-times mr-2"></i>Cancel
                                </a>
                                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all duration-200 shadow-sm hover:shadow">
                                    <i class="fa-solid fa-plus mr-2"></i>Create Subscription
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Invoice Preview -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden sticky top-6">
                    <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white">
                        <h2 class="text-lg font-semibold">Invoice Preview</h2>
                        <p class="text-xs text-blue-100 mt-1">Auto-generated invoice</p>
                    </div>
                    <div class="p-6" id="invoicePreview">
                        <!-- Invoice Header -->
                        <div class="text-center mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">INVOICE</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1" id="invoiceNumber">INV-{{ date('Ymd') }}-XXXX</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1" id="invoiceDate">{{ date('d M Y') }}</p>
                        </div>

                        <!-- Bill To -->
                        <div class="mb-6">
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">Bill To:</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white" id="billToName">-</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1" id="billToEmail">-</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400" id="billToPhone">-</p>
                        </div>

                        <!-- Items -->
                        <div class="mb-6">
                            <table class="w-full text-sm">
                                <thead class="border-b border-gray-200 dark:border-gray-700">
                                    <tr class="text-xs text-gray-500 dark:text-gray-400 uppercase">
                                        <th class="text-left pb-2">Item</th>
                                        <th class="text-right pb-2">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-b border-gray-100 dark:border-gray-700">
                                        <td class="py-3">
                                            <p class="font-medium text-gray-900 dark:text-white" id="itemName">-</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400" id="itemPeriod">-</p>
                                        </td>
                                        <td class="text-right font-semibold text-gray-900 dark:text-white" id="itemPrice">Rp 0</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="font-bold text-gray-900 dark:text-white">
                                        <td class="pt-4">TOTAL</td>
                                        <td class="text-right pt-4" id="totalPrice">Rp 0</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Actions -->
                        <div class="space-y-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <button type="button" onclick="sendInvoiceEmail()" class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-all duration-200 text-sm">
                                <i class="fa-solid fa-envelope mr-2"></i>Send via Email
                            </button>
                            <button type="button" onclick="sendInvoiceWhatsApp()" class="w-full px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-all duration-200 text-sm">
                                <i class="fa-brands fa-whatsapp mr-2"></i>Send via WhatsApp
                            </button>
                            <button type="button" onclick="printInvoice()" class="w-full px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-all duration-200 text-sm">
                                <i class="fa-solid fa-print mr-2"></i>Print Invoice
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Package data
        const packages = @json($packages->keyBy('id'));
        const instansis = @json($instansis->keyBy('id'));

        // Update invoice when form changes
        function updateInvoice() {
            const instansiSelect = document.getElementById('instansi_id');
            const packageSelect = document.getElementById('package_id');
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            const price = document.getElementById('price').value;

            // Update Bill To
            if (instansiSelect.value && instansis[instansiSelect.value]) {
                const instansi = instansis[instansiSelect.value];
                document.getElementById('billToName').textContent = instansi.nama_instansi;
                document.getElementById('billToEmail').textContent = instansi.email || '-';
                document.getElementById('billToPhone').textContent = instansi.phone || '-';
            } else {
                document.getElementById('billToName').textContent = '-';
                document.getElementById('billToEmail').textContent = '-';
                document.getElementById('billToPhone').textContent = '-';
            }

            // Update Item
            if (packageSelect.value && packages[packageSelect.value]) {
                const pkg = packages[packageSelect.value];
                document.getElementById('itemName').textContent = pkg.name + ' Subscription';
            } else {
                document.getElementById('itemName').textContent = '-';
            }

            // Update Period
            if (startDate && endDate) {
                const start = new Date(startDate).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
                const end = new Date(endDate).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
                document.getElementById('itemPeriod').textContent = `${start} - ${end}`;
            } else {
                document.getElementById('itemPeriod').textContent = '-';
            }

            // Update Price
            if (price) {
                const formatted = 'Rp ' + parseInt(price).toLocaleString('id-ID');
                document.getElementById('itemPrice').textContent = formatted;
                document.getElementById('totalPrice').textContent = formatted;
            } else {
                document.getElementById('itemPrice').textContent = 'Rp 0';
                document.getElementById('totalPrice').textContent = 'Rp 0';
            }

            // Update Invoice Number
            if (instansiSelect.value) {
                const random = Math.floor(Math.random() * 9999).toString().padStart(4, '0');
                document.getElementById('invoiceNumber').textContent = `INV-{{ date('Ymd') }}-${random}`;
            }
        }

        // Auto-update price when package changes
        document.getElementById('package_id').addEventListener('change', function() {
            const selectedPackageId = this.value;
            const priceInput = document.getElementById('price');

            if (selectedPackageId && packages[selectedPackageId]) {
                priceInput.value = packages[selectedPackageId].price;
            }
            updateInvoice();
        });

        // Auto-calculate end date when start date changes
        document.getElementById('start_date').addEventListener('change', function() {
            const startDate = new Date(this.value);
            if (startDate) {
                const endDate = new Date(startDate);
                endDate.setMonth(endDate.getMonth() + 1);
                document.getElementById('end_date').value = endDate.toISOString().split('T')[0];
            }
            updateInvoice();
        });

        // Update invoice on any change
        document.getElementById('instansi_id').addEventListener('change', updateInvoice);
        document.getElementById('end_date').addEventListener('change', updateInvoice);
        document.getElementById('price').addEventListener('input', updateInvoice);

        // Send invoice via email
        function sendInvoiceEmail() {
            const email = document.getElementById('billToEmail').textContent;
            const instansiId = document.getElementById('instansi_id').value;
            
            if (email && email !== '-' && instansiId) {
                // Open print page in new window for email preview
                const url = `/superadmin/subscriptions/invoice-preview?instansi_id=${instansiId}&package_id=${document.getElementById('package_id').value}&start_date=${document.getElementById('start_date').value}&end_date=${document.getElementById('end_date').value}&price=${document.getElementById('price').value}`;
                window.open(url, '_blank');
                alert('Invoice preview opened. You can send this via email to: ' + email);
            } else {
                alert('Please select an instansi first');
            }
        }

        // Send invoice via WhatsApp
        function sendInvoiceWhatsApp() {
            const phone = document.getElementById('billToPhone').textContent;
            const instansiName = document.getElementById('billToName').textContent;
            const packageName = document.getElementById('itemName').textContent;
            const total = document.getElementById('totalPrice').textContent;
            const period = document.getElementById('itemPeriod').textContent;

            if (phone && phone !== '-') {
                const message = `Halo ${instansiName},\n\nBerikut invoice untuk ${packageName}:\n\nPeriode: ${period}\nTotal: ${total}\n\nTerima kasih!`;
                const whatsappUrl = `https://wa.me/${phone.replace(/\D/g, '')}?text=${encodeURIComponent(message)}`;
                window.open(whatsappUrl, '_blank');
            } else {
                alert('No phone number available for this instansi');
            }
        }

        // Print invoice
        function printInvoice() {
            const instansiId = document.getElementById('instansi_id').value;
            const packageId = document.getElementById('package_id').value;
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            const price = document.getElementById('price').value;

            if (!instansiId || !packageId) {
                alert('Please fill in all required fields');
                return;
            }

            // Open print page in new window
            const url = `/superadmin/subscriptions/invoice-preview?instansi_id=${instansiId}&package_id=${packageId}&start_date=${startDate}&end_date=${endDate}&price=${price}`;
            window.open(url, '_blank');
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', updateInvoice);
    </script>
</x-superadmin-layout>