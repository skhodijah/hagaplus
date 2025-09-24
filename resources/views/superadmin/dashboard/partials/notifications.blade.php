<x-section-card title="Notifications Center" class="mb-10">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="rounded-md bg-gray-50 dark:bg-gray-800 p-3">
            <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">Subscription Expiring & Payment Overdue</p>
            <div class="h-28"></div>
        </div>
        <div class="rounded-md bg-gray-50 dark:bg-gray-800 p-3">
            <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">Maintenance, Feature Requests, Security Alerts</p>
            @if($recentNotifications->count())
                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                    @foreach($recentNotifications as $n)
                        <li class="flex items-start justify-between gap-3">
                            <span class="truncate">{{ $n->title }}</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">{{
                                \Carbon\Carbon::parse($n->created_at)->diffForHumans() }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="h-28"></div>
            @endif
        </div>
    </div>
</x-section-card>