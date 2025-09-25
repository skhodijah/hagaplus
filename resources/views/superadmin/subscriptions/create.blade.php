<x-superadmin-layout>
    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-page-header
                title="Buat Subscription Baru"
                subtitle="Tambahkan subscription untuk instansi"
                :show-period-filter="false"
            />

            <div class="flex justify-end mb-6">
                <a href="{{ route('superadmin.subscriptions.index') }}" class="bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 text-gray-800 dark:text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                    <i class="fa-solid fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>

            <x-section-card title="Informasi Subscription">
                <form method="POST" action="{{ route('superadmin.subscriptions.store') }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="instansi_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Instansi <span class="text-red-500">*</span></label>
                            <select name="instansi_id" id="instansi_id" required
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('instansi_id') border-red-300 dark:border-red-600 @enderror">
                                <option value="">Pilih Instansi</option>
                                @foreach($instansis as $instansi)
                                    <option value="{{ $instansi->id }}" {{ old('instansi_id') == $instansi->id ? 'selected' : '' }}>
                                        {{ $instansi->nama_instansi }} ({{ $instansi->subdomain }})
                                    </option>
                                @endforeach
                            </select>
                            @error('instansi_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="package_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Paket <span class="text-red-500">*</span></label>
                            <select name="package_id" id="package_id" required
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('package_id') border-red-300 dark:border-red-600 @enderror">
                                <option value="">Pilih Paket</option>
                                @foreach($packages as $package)
                                    <option value="{{ $package->id }}" {{ old('package_id') == $package->id ? 'selected' : '' }}>
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
                            <input type="date" name="start_date" id="start_date" value="{{ old('start_date', now()->format('Y-m-d')) }}" required
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('start_date') border-red-300 dark:border-red-600 @enderror">
                            @error('start_date')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Berakhir <span class="text-red-500">*</span></label>
                            <input type="date" name="end_date" id="end_date" value="{{ old('end_date', now()->addMonth()->format('Y-m-d')) }}" required
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('end_date') border-red-300 dark:border-red-600 @enderror">
                            @error('end_date')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Harga (Rp) <span class="text-red-500">*</span></label>
                            <input type="number" name="price" id="price" value="{{ old('price') }}" required min="0"
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('price') border-red-300 dark:border-red-600 @enderror">
                            @error('price')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Harga akan otomatis terupdate saat paket dipilih</p>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end space-x-3">
                        <a href="{{ route('superadmin.subscriptions.index') }}" class="bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 text-gray-800 dark:text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                            <i class="fa-solid fa-times mr-2"></i>Batal
                        </a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                            <i class="fa-solid fa-plus mr-2"></i>Buat Subscription
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

        // Auto-calculate end date when start date changes
        document.getElementById('start_date').addEventListener('change', function() {
            const startDate = new Date(this.value);
            if (startDate) {
                const endDate = new Date(startDate);
                endDate.setMonth(endDate.getMonth() + 1);
                document.getElementById('end_date').value = endDate.toISOString().split('T')[0];
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