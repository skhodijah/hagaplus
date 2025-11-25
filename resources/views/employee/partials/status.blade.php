<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
    <div class="flex items-center gap-3 mb-4">
        <div class="w-10 h-10 flex items-center justify-center rounded-lg border-2 border-[#049460] dark:border-[#10C874]">
            <svg class="w-5 h-5 text-[#049460] dark:text-[#10C874]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Status Hari Ini</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::now()->format('l, d F Y') }}</p>
        </div>
    </div>
    <div class="flex items-center justify-between">
        <p class="text-2xl font-bold {{ 
            $todayStatus === 'Belum Check In' ? 'text-gray-500 dark:text-gray-400' : 
            ($todayStatus === 'Sedang Bekerja' ? 'text-[#049460] dark:text-[#10C874]' : 'text-emerald-600 dark:text-emerald-400') 
        }}">
            {{ $todayStatus ?? 'Belum Check In' }}
        </p>
        @if(isset($todayStatus) && $todayStatus !== 'Belum Check In')
            <div class="w-3 h-3 rounded-full {{ $todayStatus === 'Sedang Bekerja' ? 'bg-[#049460]' : 'bg-emerald-500' }} animate-pulse"></div>
        @endif
    </div>
</div>