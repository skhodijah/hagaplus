<!-- Key Metrics Overview -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6 md:mb-8">
    <x-stat-card
        title="Total Instansi"
        :value="$totalInstansi"
        subtitle="Semua perusahaan terdaftar"
        icon="fas fa-building"
        color="blue"
        :trend="$instansiGrowth"
        trend-label="dari bulan lalu"
    />

    <x-stat-card
        title="Instansi Aktif"
        :value="$totalActiveInstansi"
        subtitle="Sedang berlangganan"
        icon="fas fa-check-circle"
        color="green"
        :trend="$activeInstansiGrowth"
        trend-label="dari bulan lalu"
    />

    <x-stat-card
        title="Revenue Bulan Ini"
        :value="$thisMonthRevenueFormatted"
        subtitle="Total pendapatan"
        icon="fas fa-money-bill-wave"
        color="emerald"
        :trend="$revenueGrowth"
        trend-label="dari bulan lalu"
    />

    <x-stat-card
        title="Paket Tersedia"
        :value="$totalPackages ?? 0"
        subtitle="Total paket sistem"
        icon="fas fa-box"
        color="orange"
        trend=""
    />
</div>

<!-- Secondary Metrics -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6 md:mb-8">
    <x-stat-card
        title="Instansi Baru"
        :value="$newCompanies7d"
        subtitle="7 hari terakhir"
        icon="fas fa-plus-circle"
        color="purple"
    />

    <x-stat-card
        title="Akan Berakhir"
        :value="$expiring30d"
        subtitle="dalam 30 hari"
        icon="fas fa-exclamation-triangle"
        color="yellow"
    />

    <x-stat-card
        title="Paket Populer"
        :value="$mostPopularPackage"
        subtitle="{{ $mostPopularPackageCount }} pengguna"
        icon="fas fa-crown"
        color="indigo"
    />

    <x-stat-card
        title="Rata-rata MRR"
        :value="$avgMrrFormatted"
        subtitle="per instansi"
        icon="fas fa-chart-line"
        color="teal"
    />
</div>