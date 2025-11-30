<x-superadmin-layout>
    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-page-header
                title="Edit Subscription"
                subtitle="Perbarui informasi subscription"
                :show-period-filter="false"
            />

            <div class="flex justify-end mb-6">
                <a href="{{ route('superadmin.subscriptions.show', $subscription) }}" class="bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 text-gray-800 dark:text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                    <i class="fa-solid fa-eye mr-2"></i>Lihat Detail
                </a>
            </div>

            <x-section-card title="Informasi Subscription">
                @if(isset($pendingPayment) && $pendingPayment)
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

                <form method="POST" action="{{ route('superadmin.subscriptions.update', $subscription) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="package_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Paket <span class="text-red-500">*</span></label>
                            <select name="package_id" id="package_id" required
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('package_id') border-red-300 dark:border-red-600 @enderror">
                                <option value="">Pilih Paket</option>
                                @foreach($packages as $package)
                                    <option value="{{ $package->id }}" {{ old('package_id', (isset($autoDetected['package_id']) ? $autoDetected['package_id'] : $subscription->package_id)) == $package->id ? 'selected' : '' }}>
                                        {{ $package->name }} - Rp {{ number_format($package->price, 0, ',', '.') }}/bulan
                                    </option>
                                @endforeach
                            </select>
                            @error('package_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status <span class="text-red-500">*</span></label>
                            <select name="status" id="status" required
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('status') border-red-300 dark:border-red-600 @enderror">
                                <option value="active" {{ old('status', $subscription->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ old('status', $subscription->status) == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                <option value="suspended" {{ old('status', $subscription->status) == 'suspended' ? 'selected' : '' }}>Ditangguhkan</option>
                                <option value="expired" {{ old('status', $subscription->status) == 'expired' ? 'selected' : '' }}>Kadaluarsa</option>
                                <option value="canceled" {{ old('status', $subscription->status) == 'canceled' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Mulai <span class="text-red-500">*</span></label>
                            <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $subscription->start_date->format('Y-m-d')) }}" required
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('start_date') border-red-300 dark:border-red-600 @enderror">
                            @error('start_date')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Berakhir <span class="text-red-500">*</span></label>
                            <input type="date" name="end_date" id="end_date" value="{{ old('end_date', (isset($autoDetected['end_date']) ? $autoDetected['end_date'] : $subscription->end_date->format('Y-m-d'))) }}" required
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('end_date') border-red-300 dark:border-red-600 @enderror">
                            @error('end_date')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Harga (Rp) <span class="text-red-500">*</span></label>
                            <input type="number" name="price" id="price" value="{{ old('price', (isset($autoDetected['price']) ? $autoDetected['price'] : $subscription->price)) }}" required min="0"
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('price') border-red-300 dark:border-red-600 @enderror">
                            @error('price')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Harga akan otomatis terupdate saat paket dipilih</p>
                        </div>


                        <div class="md:col-span-2">
                            <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg border border-blue-200 dark:border-blue-800">
                                <div class="flex items-start space-x-3">
                                    <i class="fa-solid fa-info-circle text-blue-500 mt-1"></i>
                                    <div>
                                        <h4 class="text-sm font-medium text-blue-900 dark:text-blue-100 mb-1">Informasi Instansi</h4>
                                        <p class="text-sm text-blue-700 dark:text-blue-300">
                                            <strong>{{ $subscription->instansi->nama_instansi }}</strong><br>

                                            Email: {{ $subscription->instansi->email ?? 'Tidak ada' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end space-x-3">
                        @if($subscription->canBeExtended() || $subscription->current_status === 'expired')
                            <form method="POST" action="{{ route('superadmin.subscriptions.extend', $subscription) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin memperpanjang subscription 1 bulan?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                    <i class="fa-solid fa-calendar-plus mr-2"></i>Perpanjang 1 Bulan
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('superadmin.subscriptions.index') }}" class="bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 text-gray-800 dark:text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                            <i class="fa-solid fa-times mr-2"></i>Batal
                        </a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                            <i class="fa-solid fa-save mr-2"></i>Perbarui Subscription
                        </button>
                    </div>
                </form>
            </x-section-card>
        </div>
    </div>

    <script>
        // Package data for auto-updating price
        const packages = @json($packages->pluck('price', 'id'));

        document.getElementById('package_id').addEventListener('change', function() {
            const selectedPackageId = this.value;
            const priceInput = document.getElementById('price');

            if (selectedPackageId && packages[selectedPackageId]) {
                priceInput.value = packages[selectedPackageId];
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
</x-superadmin-layout>