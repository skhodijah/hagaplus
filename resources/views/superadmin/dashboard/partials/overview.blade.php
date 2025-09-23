<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 md:gap-6 mb-6 md:mb-8">
    <div class="p-4 rounded-lg bg-white dark:bg-gray-900 shadow">
        <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Instansi Aktif</p>
        <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $totalActiveCompanies }}</p>
    </div>
    <div class="p-4 rounded-lg bg-white dark:bg-gray-900 shadow">
        <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Revenue Bulan Ini</p>
        <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $thisMonthRevenueFormatted }}</p>
    </div>
    <div class="p-4 rounded-lg bg-white dark:bg-gray-900 shadow">
        <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Instansi Baru (7 hari)</p>
        <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $newCompanies7d }}</p>
    </div>
    <div class="p-4 rounded-lg bg-white dark:bg-gray-900 shadow">
        <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Akan Berakhir (30 hari)</p>
        <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $expiring30d }}</p>
    </div>
    <div class="p-4 rounded-lg bg-white dark:bg-gray-900 shadow">
        <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Pengguna Aktif</p>
        <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $activeUsers }}</p>
    </div>
    <div class="p-4 rounded-lg bg-white dark:bg-gray-900 shadow">
        <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Pertumbuhan MRR</p>
        <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $mrrGrowthPct }}</p>
    </div>
</div> 