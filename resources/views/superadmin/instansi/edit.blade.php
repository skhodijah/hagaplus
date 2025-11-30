<x-superadmin-layout>
    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-page-header
                title="Edit Instansi"
                subtitle="Perbarui informasi instansi"
                :show-period-filter="false"
            />

            <div class="flex justify-end mb-6">
                <a href="{{ route('superadmin.instansi.show', $instansi) }}" class="bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 text-gray-800 dark:text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                    <i class="fa-solid fa-eye mr-2"></i>Lihat Detail
                </a>
            </div>

            <x-section-card title="Informasi Dasar">
                <form method="POST" action="{{ route('superadmin.instansi.update', $instansi) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nama_instansi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Instansi</label>
                            <input type="text" name="nama_instansi" id="nama_instansi" value="{{ old('nama_instansi', $instansi->nama_instansi) }}"
                                   class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('nama_instansi') border-red-300 dark:border-red-600 @enderror">
                            @error('nama_instansi')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $instansi->email) }}"
                                   class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('email') border-red-300 dark:border-red-600 @enderror">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Telepon</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $instansi->phone) }}"
                                   class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('phone') border-red-300 dark:border-red-600 @enderror">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="alamat" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Alamat</label>
                            <textarea name="alamat" id="alamat" rows="3"
                                      class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('alamat') border-red-300 dark:border-red-600 @enderror">{{ old('alamat', $instansi->address) }}</textarea>
                            @error('alamat')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="is_active" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status Aktif Instansi</label>
                            <select name="is_active" id="is_active"
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('is_active') border-red-300 dark:border-red-600 @enderror">
                                <option value="1" {{ old('is_active', $instansi->is_active) == 1 ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('is_active', $instansi->is_active) == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                            @error('is_active')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Status aktif menentukan apakah instansi dapat mengakses sistem</p>
                        </div>

                        <div class="md:col-span-2">
                            <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg border border-blue-200 dark:border-blue-800">
                                <div class="flex items-start space-x-3">
                                    <i class="fa-solid fa-info-circle text-blue-500 mt-1"></i>
                                    <div>
                                        <h4 class="text-sm font-medium text-blue-900 dark:text-blue-100 mb-1">Informasi Langganan</h4>
                                        <p class="text-sm text-blue-700 dark:text-blue-300">
                                            Untuk mengelola langganan dan paket, silakan gunakan menu <strong>Subscriptions</strong>.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end space-x-3">
                        <a href="{{ route('superadmin.instansi.index') }}" class="bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 text-gray-800 dark:text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                            <i class="fa-solid fa-times mr-2"></i>Batal
                        </a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                            <i class="fa-solid fa-save mr-2"></i>Perbarui Instansi
                        </button>
                    </div>
                </form>
            </x-section-card>
        </div>
    </div>

</x-superadmin-layout>
