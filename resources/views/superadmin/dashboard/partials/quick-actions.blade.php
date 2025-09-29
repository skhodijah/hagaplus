<x-section-card title="Aksi Cepat">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <x-action-button 
            href="{{ route('superadmin.instansi.create') }}" 
            color="blue"
            icon="fas fa-plus-circle"
        >
            <div class="text-left">
                <div class="font-semibold">Tambah Instansi</div>
                <div class="text-xs text-gray-500">Buat akun instansi baru</div>
            </div>
        </x-action-button>
        
        <x-action-button 
            href="{{ route('superadmin.subscriptions.index') }}?status=pending" 
            color="green"
            icon="fas fa-check-circle"
        >
            <div class="text-left">
                <div class="font-semibold">Kelola Langganan</div>
                <div class="text-xs text-gray-500">Approve/perpanjang langganan</div>
            </div>
        </x-action-button>
        
        <x-action-button 
            href="{{ route('superadmin.support_requests.index') }}" 
            color="orange"
            icon="fas fa-headset"
        >
            <div class="text-left">
                <div class="font-semibold">Support Center</div>
                <div class="text-xs text-gray-500">Lihat tiket support</div>
            </div>
        </x-action-button>
        
        <x-action-button 
            href="{{ route('superadmin.reports.activities') }}" 
            color="purple"
            icon="fas fa-chart-bar"
        >
            <div class="text-left">Laporan Keuangan</div>
        </x-action-button>
        
        <x-action-button 
            href="{{ route('superadmin.settings.index') }}" 
            color="gray"
            icon="fas fa-cog"
        >
            <div class="text-left">Pengaturan Sistem</div>
        </x-action-button>
        
        <x-action-button 
            href="#" 
            @click="$dispatch('open-modal', 'backup-modal')"
            color="indigo"
            icon="fas fa-database"
        >
            <div class="text-left">Backup Database</div>
        </x-action-button>
    </div>
</x-section-card>

<!-- Backup Modal -->
<x-modal name="backup-modal" maxWidth="md">
    <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            Backup Database
        </h2>

        <div class="mt-4">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Pilih tipe backup yang ingin Anda lakukan:
            </p>
            
            <div class="mt-4 space-y-2">
                <button @click="$dispatch('close')" class="w-full flex items-center justify-between p-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                            <i class="fas fa-database text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <div class="font-medium text-gray-900 dark:text-white">Full Backup</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Backup seluruh data sistem</div>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400"></i>
                </button>
                
                <button @click="$dispatch('close')" class="w-full flex items-center justify-between p-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                            <i class="fas fa-building text-green-600 dark:text-green-400"></i>
                        </div>
                        <div class="ml-3">
                            <div class="font-medium text-gray-900 dark:text-white">Backup Instansi</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Pilih instansi tertentu</div>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400"></i>
                </button>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <x-secondary-button @click="$dispatch('close')">
                Tutup
            </x-secondary-button>
        </div>
    </div>
</x-modal>