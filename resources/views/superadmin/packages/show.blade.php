<x-superadmin-layout>
    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-page-header
                title="{{ $package->name }}"
                subtitle="Detail lengkap paket dan informasi fitur"
                :show-period-filter="false"
            />

            <div class="flex justify-end mb-6">
                <div class="flex space-x-3">
                    <a href="{{ route('superadmin.packages.edit', $package) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                        <i class="fa-solid fa-edit mr-2"></i>Edit Paket
                    </a>
                    <a href="{{ route('superadmin.packages.index') }}" class="bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 text-gray-800 dark:text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                        <i class="fa-solid fa-arrow-left mr-2"></i>Kembali ke Daftar
                    </a>
                </div>
            </div>

            <!-- Package Information -->
            <x-section-card title="Informasi Paket" class="mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Nama Paket</dt>
                        <dd class="text-sm text-gray-900 dark:text-white font-medium">{{ $package->name }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Harga</dt>
                        <dd class="text-sm text-gray-900 dark:text-white font-medium">Rp {{ number_format($package->price) }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Maksimal Karyawan</dt>
                        <dd class="text-sm text-gray-900 dark:text-white">{{ $package->max_employees }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Maksimal Cabang</dt>
                        <dd class="text-sm text-gray-900 dark:text-white">{{ $package->max_branches }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Status Aktif</dt>
                        <dd class="mt-1">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($package->is_active) bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300
                                @else bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300 @endif">
                                {{ $package->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Dibuat Pada</dt>
                        <dd class="text-sm text-gray-900 dark:text-white">{{ $package->created_at->format('d M Y H:i') }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Diupdate Pada</dt>
                        <dd class="text-sm text-gray-900 dark:text-white">{{ $package->updated_at->format('d M Y H:i') }}</dd>
                    </div>

                    <div class="md:col-span-2 lg:col-span-3">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Deskripsi</dt>
                        <dd class="text-sm text-gray-900 dark:text-white">{{ $package->description }}</dd>
                    </div>
                </div>
            </x-section-card>

            <!-- Package Features -->
            <x-section-card title="Fitur Paket" class="mb-6">
                @if(!empty($package->features) && is_array($package->features))
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($package->features as $feature)
                            <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                <i class="fas fa-check text-green-500 mr-3"></i>
                                <span class="text-sm text-gray-900 dark:text-white capitalize">
                                    {{ str_replace('_', ' ', $feature) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-inbox text-4xl text-gray-400 mb-4"></i>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada fitur yang ditentukan untuk paket ini</p>
                    </div>
                @endif
            </x-section-card>

            <!-- Delete Action -->
            <div class="flex justify-end">
                <form method="POST" action="{{ route('superadmin.packages.destroy', $package) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                        <i class="fa-solid fa-trash mr-2"></i>Hapus Paket
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-superadmin-layout>
