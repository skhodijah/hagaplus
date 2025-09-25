<x-superadmin-layout>
    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-page-header
                title="Performance Reports"
                subtitle="Kinerja sistem dan performa fitur"
                :show-period-filter="false"
            />

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <x-stats-card title="Rata-rata Waktu Respon" :value="'—'" icon="fa-solid fa-gauge" />
                <x-stats-card title="Uptime (30 Hari)" :value="'—'" icon="fa-solid fa-signal" />
                <x-stats-card title="Error Rate" :value="'—'" icon="fa-solid fa-bug" />
            </div>

            <x-section-card title="Ringkasan">
                <p class="text-sm text-gray-700 dark:text-gray-300">Integrasi metrik performa dapat ditambahkan kemudian (APM, logs, dsb).</p>
            </x-section-card>
        </div>
    </div>
</x-superadmin-layout> 