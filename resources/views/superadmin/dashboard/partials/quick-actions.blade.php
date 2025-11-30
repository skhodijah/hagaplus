<div class="mb-8">
    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
        <span class="w-1 h-6 bg-blue-500 rounded-full mr-2"></span>
        Aksi Cepat
    </h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <x-action-button 
            href="{{ route('superadmin.instansi.create') }}" 
            color="blue"
            icon="fas fa-plus-circle"
            class="h-full"
        >
            <div class="space-y-1">
                <div class="font-semibold text-sm">Tambah Instansi</div>
                <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">Buat akun instansi baru</p>
            </div>
        </x-action-button>
        
        <x-action-button 
            href="{{ route('superadmin.subscriptions.index') }}?status=pending" 
            color="green"
            icon="fas fa-check-circle"
            class="h-full"
        >
            <div class="space-y-1">
                <div class="font-semibold text-sm">Kelola Langganan</div>
                <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">Approve/perpanjang langganan</p>
            </div>
        </x-action-button>
        
        <x-action-button 
            href="{{ route('superadmin.reports.activities') }}" 
            color="indigo"
            icon="fas fa-chart-line"
            class="h-full"
        >
            <div class="space-y-1">
                <div class="font-semibold text-sm">Laporan Keuangan</div>
                <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">Analisis pendapatan</p>
            </div>
        </x-action-button>
        

        <x-action-button
            href="#"
            color="red"
            icon="fas fa-database"
            class="h-full"
            x-on:click.prevent="$dispatch('open-modal', 'backup-modal')"
        >
            <div class="space-y-1">
                <div class="font-semibold text-sm">Backup Data</div>
                <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">Cadangkan database</p>
            </div>
        </x-action-button>
    </div>

    <!-- Backup Modal -->
    <x-modal name="backup-modal" maxWidth="md">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Backup Database') }}
            </h2>

            <div class="mt-6 space-y-4">
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                    {{ __('Pilih tipe backup yang ingin Anda lakukan:') }}
                </p>
                
                <button @click="$dispatch('close')" class="w-full flex items-center justify-between p-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                            <i class="fas fa-database text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <div class="ml-3 text-left">
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
                        <div class="ml-3 text-left">
                            <div class="font-medium text-gray-900 dark:text-white">Backup Instansi</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Pilih instansi tertentu</div>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400"></i>
                </button>
            </div>

            <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Batal') }}
                </x-secondary-button>
            </div>
        </div>
    </x-modal>
</div>