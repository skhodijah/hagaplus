<x-superadmin-layout>
    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-page-header
                title="Usage Statistics"
                subtitle="Ringkasan penggunaan sistem oleh instansi"
                :show-period-filter="false"
            />

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <x-stats-card title="Total Instansi" :value="\App\Models\SuperAdmin\Instansi::count()" icon="fa-solid fa-building" />
                <x-stats-card title="Total Karyawan" :value="\Illuminate\Support\Facades\DB::table('employees')->count()" icon="fa-solid fa-users" />
                <x-stats-card title="Absensi Bulan Ini" :value="\Illuminate\Support\Facades\DB::table('attendances')->whereBetween('attendance_date', [now()->startOfMonth(), now()->endOfMonth()])->count()" icon="fa-solid fa-clipboard-check" />
            </div>

            <x-section-card title="Top 10 Instansi Berdasarkan Aktivitas Absensi">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Instansi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jumlah Absensi (30 Hari)</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                            @php
                                $top = \Illuminate\Support\Facades\DB::table('attendances')
                                    ->join('users', 'attendances.user_id', '=', 'users.id')
                                    ->select('users.instansi_id', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
                                    ->where('attendance_date', '>=', now()->subDays(30)->toDateString())
                                    ->groupBy('users.instansi_id')
                                    ->orderByDesc('total')
                                    ->limit(10)
                                    ->get();
                                $instansiMap = \App\Models\SuperAdmin\Instansi::whereIn('id', $top->pluck('instansi_id'))->pluck('nama_instansi', 'id');
                            @endphp
                            @forelse($top as $row)
                                <tr>
                                    <td class="px-6 py-3 text-sm text-gray-900 dark:text-white">{{ $instansiMap[$row->instansi_id] ?? '-' }}</td>
                                    <td class="px-6 py-3 text-sm text-gray-900 dark:text-white">{{ $row->total }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-6 text-center text-gray-500 dark:text-gray-400">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-section-card>
        </div>
    </div>
</x-superadmin-layout> 