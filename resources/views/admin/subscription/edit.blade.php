<x-admin-layout>
    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-page-header
                title="Edit Subscription"
                subtitle="Edit subscription berdasarkan permintaan pembayaran"
                :show-period-filter="false"
            />

            <div class="flex justify-end mb-6">
                <a href="{{ route('admin.subscription.index') }}" class="bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 text-gray-800 dark:text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                    <i class="fa-solid fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>

            <x-section-card title="Edit Subscription">
                <form method="POST" action="{{ route('admin.subscription.update', $subscription->id) }}">
                    @csrf
                    @method('PUT')

                    @if($pendingPayment)
                        <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                            <div class="flex items-center">
                                <i class="fa-solid fa-info-circle text-blue-600 dark:text-blue-400 mr-3"></i>
                                <div>
                                    <h4 class="text-blue-800 dark:text-blue-200 font-medium">Memproses Permintaan Pembayaran</h4>
                                    <p class="text-blue-700 dark:text-blue-300 text-sm">
                                        {{ $pendingPayment->notes }}
                                        <br>
                                        <strong>Jumlah: Rp {{ number_format($pendingPayment->amount, 0, ',', '.') }}</strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="package_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Paket <span class="text-red-500">*</span></label>
                            <select name="package_id" id="package_id" required
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('package_id') border-red-300 dark:border-red-600 @enderror">
                                <option value="">Pilih Paket</option>
                                @foreach($packages as $package)
                                    <option value="{{ $package->id }}" {{ $subscription->package_id == $package->id ? 'selected' : '' }}>
                                        {{ $package->name }} - Rp {{ number_format($package->price, 0, ',', '.') }}/bulan
                                    </option>
                                @endforeach
                            </select>
                            @error('package_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Mulai <span class="text-red-500">*</span></label>
                            <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $subscription->start_date) }}" required
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('start_date') border-red-300 dark:border-red-600 @enderror">
                            @error('start_date')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Berakhir <span class="text-red-500">*</span></label>
                            <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $subscription->end_date) }}" required
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('end_date') border-red-300 dark:border-red-600 @enderror">
                            @error('end_date')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Harga (Rp) <span class="text-red-500">*</span></label>
                            <input type="number" name="price" id="price" value="{{ old('price', $subscription->price) }}" required min="0"
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('price') border-red-300 dark:border-red-600 @enderror">
                            @error('price')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Metode Pembayaran <span class="text-red-500">*</span></label>
                            <select name="payment_method" id="payment_method" required
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('payment_method') border-red-300 dark:border-red-600 @enderror">
                                <option value="">Pilih Metode Pembayaran</option>
                                <option value="transfer" {{ old('payment_method', ($pendingPayment ? $pendingPayment->payment_method : '')) == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                                <option value="bank_transfer" {{ old('payment_method', ($pendingPayment ? $pendingPayment->payment_method : '')) == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="cash" {{ old('payment_method', ($pendingPayment ? $pendingPayment->payment_method : '')) == 'cash' ? 'selected' : '' }}>Tunai</option>
                                <option value="credit_card" {{ old('payment_method', ($pendingPayment ? $pendingPayment->payment_method : '')) == 'credit_card' ? 'selected' : '' }}>Kartu Kredit</option>
                            </select>
                            @error('payment_method')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end space-x-3">
                        <a href="{{ route('admin.subscription.index') }}" class="bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 text-gray-800 dark:text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                            <i class="fa-solid fa-times mr-2"></i>Batalkan
                        </a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                            <i class="fa-solid fa-save mr-2"></i>Simpan
                        </button>
                    </div>
                </form>
            </x-section-card>
        </div>
    </div>

    <script>
        // Auto-update price when package changes
        document.getElementById('package_id').addEventListener('change', function() {
            const selectedPackageId = this.value;
            const packages = @json($packages->pluck('price', 'id'));

            if (selectedPackageId && packages[selectedPackageId]) {
                document.getElementById('price').value = packages[selectedPackageId];
            }
        });

        // Auto-update price on page load if package is already selected
        document.addEventListener('DOMContentLoaded', function() {
            const packageSelect = document.getElementById('package_id');
            if (packageSelect.value) {
                packageSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
</x-admin-layout>