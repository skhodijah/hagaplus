<x-superadmin-layout>
    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-page-header
                title="{{ $instansi->nama_instansi }}"
                subtitle="Detail lengkap instansi dan informasi perusahaan"
                :show-period-filter="false"
            />

            <div class="flex justify-end mb-6">
                <div class="flex space-x-3">
                    <a href="{{ route('superadmin.instansi.edit', $instansi) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                        <i class="fa-solid fa-edit mr-2"></i>Edit Instansi
                    </a>
                    <a href="{{ route('superadmin.instansi.index') }}" class="bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 text-gray-800 dark:text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                        <i class="fa-solid fa-arrow-left mr-2"></i>Kembali ke Daftar
                    </a>
                </div>
            </div>

            <!-- Company Information -->
            <x-section-card title="Informasi Perusahaan" class="mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Nama Instansi</dt>
                        <dd class="text-sm text-gray-900 dark:text-white font-medium">{{ $instansi->nama_instansi }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Email</dt>
                        <dd class="text-sm text-gray-900 dark:text-white">{{ $instansi->email ?? '-' }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Telepon</dt>
                        <dd class="text-sm text-gray-900 dark:text-white">{{ $instansi->phone ?? '-' }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Alamat</dt>
                        <dd class="text-sm text-gray-900 dark:text-white">{{ $instansi->address ?? '-' }}</dd>
                    </div>


                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Status Aktif</dt>
                        <dd class="mt-1">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($instansi->is_active) bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300
                                @else bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300 @endif">
                                {{ $instansi->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </dd>
                    </div>


                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Dibuat Pada</dt>
                        <dd class="text-sm text-gray-900 dark:text-white">{{ $instansi->created_at->format('d M Y H:i') }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Diupdate Pada</dt>
                        <dd class="text-sm text-gray-900 dark:text-white">{{ $instansi->updated_at->format('d M Y H:i') }}</dd>
                    </div>
                </div>
            </x-section-card>


            <!-- Additional Information -->
            @if($instansi->settings)
                <x-section-card title="Pengaturan & Konfigurasi" class="mb-6">
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Konfigurasi Sistem</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            @if(isset($instansi->settings['currency']))
                                <div><span class="font-medium">Mata Uang:</span> {{ $instansi->settings['currency'] }}</div>
                            @endif
                            @if(isset($instansi->settings['timezone']))
                                <div><span class="font-medium">Timezone:</span> {{ $instansi->settings['timezone'] }}</div>
                            @endif
                            @if(isset($instansi->settings['date_format']))
                                <div><span class="font-medium">Format Tanggal:</span> {{ $instansi->settings['date_format'] }}</div>
                            @endif
                        </div>
                    </div>
                </x-section-card>
            @endif
        </div>
    </div>
</x-superadmin-layout>
