<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-6 md:mb-8">
    <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-4">
        <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3">Daftar Instansi & Status Subscription</h3>
        <div class="rounded-md bg-gray-50 dark:bg-gray-800 p-3">
            @if($instansiWithStatus->count())
                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($instansiWithStatus as $i)
                        <li class="py-2 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $i->nama_instansi }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $i->subdomain }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full
                                @if($i->status_langganan==='active') bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300
                                @elseif($i->status_langganan==='inactive') bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                                @else bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300 @endif">
                                {{ ucfirst($i->status_langganan) }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="h-40 flex items-center justify-center text-gray-500 dark:text-gray-400 text-sm">Tidak ada data</div>
            @endif
        </div>
    </div>
    <div class="grid grid-cols-1 gap-4">
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