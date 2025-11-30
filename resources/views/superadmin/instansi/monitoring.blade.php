<x-superadmin-layout>
    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-page-header
                title="Instansi Usage Monitoring"
                subtitle="Pantau status langganan dan penggunaan instansi"
                :show-period-filter="false"
            />

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <x-stats-card title="Total Instansi" :value="$counts['total']" icon="fa-solid fa-building" />
                <x-stats-card title="Aktif" :value="$counts['active']" icon="fa-solid fa-circle-check" />
                <x-stats-card title="Kadaluarsa" :value="$counts['expired']" icon="fa-solid fa-circle-xmark" />
                <x-stats-card title="Hampir Kadaluarsa (<=7 hari)" :value="$counts['expiring_7']" icon="fa-solid fa-hourglass-half" />
            </div>

            <x-section-card title="Daftar Instansi">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Instansi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Paket</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Berakhir</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Sisa Hari</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Karyawan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($items as $i)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $i->nama_instansi }}</div>

                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center space-x-2">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full @if($i->is_active) bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300 @else bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300 @endif">
                                                {{ $i->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                            @if($i->subscription_status)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    @if($i->subscription_status == 'active') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300
                                                    @elseif($i->subscription_status == 'inactive') bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-300
                                                    @elseif($i->subscription_status == 'expired') bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300
                                                    @elseif($i->subscription_status == 'suspended') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-300
                                                    @else bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-300 @endif">
                                                    {{ ucfirst($i->subscription_status) }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $i->package_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $i->subscription_end ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ isset($i->days_remaining) ? $i->days_remaining : '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $i->employees_count }}/{{ $i->max_employees }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a class="text-blue-600 dark:text-blue-400 hover:underline" href="{{ route('superadmin.instansi.show', $i->id) }}">Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-section-card>
        </div>
    </div>
</x-superadmin-layout>