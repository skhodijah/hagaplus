<x-superadmin-layout>
    <div class="py-6 md:py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 md:mb-8">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100">Customer Monitoring</h1>
                <p class="mt-1 text-gray-600 dark:text-gray-400">Monitor customer payments, support, and usage</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-6 md:mb-8">
                <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-4">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">Instansi Overdue/Pending Payment</h3>
                    <div class="rounded-md bg-gray-50 dark:bg-gray-800 p-3 text-sm text-gray-700 dark:text-gray-300">
                        @if($overduePayments->count())
                            <ul class="space-y-1">
                                @foreach($overduePayments as $p)
                                    <li class="flex justify-between"><span>#{{ $p->transaction_id ?? '—' }}</span> <span class="font-semibold">Rp {{ number_format($p->amount ?? 0,0,',','.') }}</span></li>
                                @endforeach
                            </ul>
                        @else
                            <div class="h-24 flex items-center justify-center text-gray-500 dark:text-gray-400">Tidak ada data</div>
                        @endif
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-4">
                    <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-4">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">Support Tickets Terbuka</h3>
                        <div class="h-24 rounded-md bg-gray-50 dark:bg-gray-800 flex items-center justify-center text-gray-500 dark:text-gray-400 text-sm">Integrasi tiket nanti</div>
                    </div>
                    <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-4">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">Customer Satisfaction Score</h3>
                        <div class="h-24 rounded-md bg-gray-50 dark:bg-gray-800 flex items-center justify-center text-gray-500 dark:text-gray-400 text-sm">Integrasi CSAT nanti</div>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-4 mb-6 md:mb-8">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">Usage Alerts (Melebihi Limit)</h3>
                <div class="h-28 rounded-md bg-gray-50 dark:bg-gray-800 flex items-center justify-center text-gray-500 dark:text-gray-400 text-sm">Integrasi usage alert nanti</div>
            </div>
        </div>
    </div>
</x-superadmin-layout>