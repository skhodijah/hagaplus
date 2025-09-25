<x-section-card title="Data Operasional" class="mb-10">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="rounded-md bg-gray-50 dark:bg-gray-800 p-3">
            <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">Tren Absensi Bulanan</p>
            <div class="h-32">
                @if($monthlyAttendanceCounts->count())
                    @php $maxAttendance = $monthlyAttendanceCounts->max('total') ?: 1; @endphp
                    <div class="flex items-end space-x-2 h-full">
                        @foreach($monthlyAttendanceCounts as $row)
                            <div class="flex-1 flex flex-col items-center">
                                <div class="bg-blue-500 rounded-t w-full" style="height: {{ min(100, ($row->total / $maxAttendance) * 100) }}%"></div>
                                <span class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ $row->ym }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="h-full flex items-center justify-center text-gray-500 dark:text-gray-400 text-sm">Tidak ada data</div>
                @endif
            </div>
        </div>
        <div class="rounded-md bg-gray-50 dark:bg-gray-800 p-3">
            <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">Tren Cuti Bulanan</p>
            <div class="h-32">
                @if($monthlyLeaveCounts->count())
                    @php $maxLeave = $monthlyLeaveCounts->max('total') ?: 1; @endphp
                    <div class="flex items-end space-x-2 h-full">
                        @foreach($monthlyLeaveCounts as $row)
                            <div class="flex-1 flex flex-col items-center">
                                <div class="bg-red-500 rounded-t w-full" style="height: {{ min(100, ($row->total / $maxLeave) * 100) }}%"></div>
                                <span class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ $row->ym }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="h-full flex items-center justify-center text-gray-500 dark:text-gray-400 text-sm">Tidak ada data</div>
                @endif
            </div>
        </div>
    </div>
    <div class="mt-4">
        <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">Aktivitas Terbaru</p>
        <div class="rounded-md bg-gray-50 dark:bg-gray-800 p-3 max-h-40 overflow-y-auto">
            @if($recentActivityLogs->count())
                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                    @foreach($recentActivityLogs as $log)
                        <li class="flex items-start space-x-2">
                            <i class="fa-solid fa-circle text-xs mt-1 {{ $log->event == 'created' ? 'text-green-500' : ($log->event == 'updated' ? 'text-blue-500' : 'text-gray-500') }}"></i>
                            <div>
                                <span class="font-medium">{{ $log->description }}</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400 block">{{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="text-sm text-gray-500 dark:text-gray-400">Tidak ada aktivitas terbaru</div>
            @endif
        </div>
    </div>
</x-section-card>