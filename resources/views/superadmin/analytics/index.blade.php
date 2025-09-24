<x-superadmin-layout>
    <div class="py-6 md:py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 md:mb-8">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100">Analytics Dashboard</h1>
                <p class="mt-1 text-gray-600 dark:text-gray-400">Detailed analytics and insights for HagaPlus system</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-6 md:mb-8">
                <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-4">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3">Grafik Pertumbuhan Subscription per Bulan</h3>
                    <div class="rounded-md bg-gray-50 dark:bg-gray-800 p-3 text-sm text-gray-700 dark:text-gray-300">
                        @if($monthlySubscriptionCounts->count())
                            <ul class="space-y-1">
                                @foreach($monthlySubscriptionCounts as $row)
                                    <li class="flex justify-between"><span>{{ $row->ym }}</span> <span class="font-semibold">{{ $row->total }}</span></li>
                                @endforeach
                            </ul>
                        @else
                            <div class="h-40 flex items-center justify-center text-gray-500 dark:text-gray-400">Tidak ada data</div>
                        @endif
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-4">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3">Distribusi Paket yang Digunakan</h3>
                    <div class="rounded-md bg-gray-50 dark:bg-gray-800 p-3 text-sm text-gray-700 dark:text-gray-300">
                        @if($packageDistribution->count())
                            <ul class="space-y-1">
                                @foreach($packageDistribution as $row)
                                    <li class="flex justify-between"><span>{{ $row->package->name ?? ('Package #' . $row->package_id) }}</span> <span class="font-semibold">{{ $row->total }}</span></li>
                                @endforeach
                            </ul>
                        @else
                            <div class="h-40 flex items-center justify-center text-gray-500 dark:text-gray-400">Tidak ada data</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6 mb-6 md:mb-8">
                <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-4">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3">Revenue per Paket</h3>
                    <div class="h-44 rounded-md bg-gray-50 dark:bg-gray-800 flex items-center justify-center text-gray-500 dark:text-gray-400 text-sm">Integrasi chart nanti</div>
                </div>
                <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-4">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3">Churn & Retention</h3>
                    <div class="h-44 rounded-md bg-gray-50 dark:bg-gray-800 flex items-center justify-center text-gray-500 dark:text-gray-400 text-sm">Integrasi chart nanti</div>
                </div>
                <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-4">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3">Utilisasi Fitur per Paket</h3>
                    <div class="h-44 rounded-md bg-gray-50 dark:bg-gray-800 flex items-center justify-center text-gray-500 dark:text-gray-400 text-sm">Integrasi chart nanti</div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-4 mb-6 md:mb-8">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3">Geografi Instansi</h3>
                <div class="h-72 rounded-md bg-gray-50 dark:bg-gray-800 flex items-center justify-center text-gray-500 dark:text-gray-400 text-sm">Peta Indonesia (placeholder)</div>
            </div>
        </div>
    </div>
</x-superadmin-layout>