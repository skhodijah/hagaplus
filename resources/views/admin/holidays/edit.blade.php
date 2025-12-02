<x-admin-layout>
    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <a href="{{ route('admin.holidays.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 mb-4 transition-colors">
                    <i class="fa-solid fa-arrow-left mr-2"></i>
                    Kembali ke Daftar
                </a>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Hari Libur</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Perbarui informasi hari libur.</p>
            </div>

            <!-- Form Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <form action="{{ route('admin.holidays.update', $holiday->id) }}" method="POST" class="p-6 md:p-8 space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Date Range -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Mulai <span class="text-red-500">*</span></label>
                            <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $holiday->start_date->format('Y-m-d')) }}" required
                                class="w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            @error('start_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Selesai <span class="text-red-500">*</span></label>
                            <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $holiday->end_date->format('Y-m-d')) }}" required
                                class="w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            @error('end_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <script>
                        document.getElementById('start_date').addEventListener('change', function() {
                            // Only update end date if it's currently empty or same as old start date
                            // But for simplicity, let's just ensure end date is at least start date
                            document.getElementById('end_date').min = this.value;
                        });
                    </script>

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Hari Libur <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $holiday->name) }}" required placeholder="Contoh: Hari Raya Idul Fitri"
                            class="w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi (Opsional)</label>
                        <textarea name="description" id="description" rows="3" placeholder="Tambahkan keterangan tambahan..."
                            class="w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">{{ old('description', $holiday->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Is National Holiday -->
                    <div class="flex items-center">
                        <input type="hidden" name="is_national_holiday" value="0">
                        <input type="checkbox" name="is_national_holiday" id="is_national_holiday" value="1" {{ old('is_national_holiday', $holiday->is_national_holiday) ? 'checked' : '' }}
                            class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 transition-colors">
                        <label for="is_national_holiday" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Hari Libur Nasional
                        </label>
                    </div>

                    <!-- Actions -->
                    <div class="pt-6 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.holidays.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-xl transition-colors">
                            Batal
                        </a>
                        <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl shadow-lg shadow-blue-200 dark:shadow-none transition-all">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
