<div class="bg-white dark:bg-gray-900 rounded-lg shadow p-4 mb-6 md:mb-8">
    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3">Recent Activities</h3>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="rounded-md bg-gray-50 dark:bg-gray-800 p-3">
            <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">Subscription Logs</p>
            @if($recentSubscriptionLogs->count())
                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                    @foreach($recentSubscriptionLogs as $log)
                        <li class="flex items-center justify-between">
                            <span>#{{ $log->transaction_id ?? '—' }}</span>
                            <span class="font-semibold">Rp {{ number_format($log->amount ?? 0,0,',','.') }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="h-32"></div>
            @endif
        </div>
        <div class="rounded-md bg-gray-50 dark:bg-gray-800 p-3">
            <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">System Changes & Updates</p>
            <div class="h-32"></div>
        </div>
        <div class="rounded-md bg-gray-50 dark:bg-gray-800 p-3">
            <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">Login History & Alerts</p>
            <div class="h-32"></div>
        </div>
    </div>
</div> 