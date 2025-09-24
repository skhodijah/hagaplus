<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 mb-6 md:mb-8">
    <x-stat-card
        title="Instansi Aktif"
        :value="$totalActiveCompanies"
        icon="fas fa-building"
        color="blue"
    />

    <x-stat-card
        title="Revenue Bulan Ini"
        :value="$thisMonthRevenueFormatted"
        icon="fas fa-money-bill-wave"
        color="green"
    />

    <x-stat-card
        title="Instansi Baru (7 hari)"
        :value="$newCompanies7d"
        icon="fas fa-plus-circle"
        color="purple"
    />

    <x-stat-card
        title="Akan Berakhir (30 hari)"
        :value="$expiring30d"
        icon="fas fa-exclamation-triangle"
        color="orange"
    />

    <x-stat-card
        title="Pengguna Aktif"
        :value="$activeUsers"
        icon="fas fa-users"
        color="cyan"
    />

    <x-stat-card
        title="Pertumbuhan MRR"
        :value="$mrrGrowthPct"
        icon="fas fa-chart-line"
        color="pink"
    />
</div>