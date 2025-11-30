<x-employee-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Riwayat Penggajian</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Lihat dan unduh slip gaji bulanan Anda.</p>
                </div>
            </div>

            <!-- Latest Payroll Card -->
            @if($payrolls->count() > 0)
                @php $latest = $payrolls->first(); @endphp
                <div class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-2xl shadow-xl overflow-hidden text-white relative">
                    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white opacity-10 rounded-full blur-2xl"></div>
                    <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-black opacity-10 rounded-full blur-2xl"></div>
                    
                    <div class="p-8 relative z-10">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                            <div>
                                <p class="text-indigo-100 text-sm font-medium uppercase tracking-wider mb-1">Gaji Terakhir</p>
                                <h2 class="text-3xl font-bold">{{ \Carbon\Carbon::createFromDate($latest->period_year, $latest->period_month, 1)->format('F Y') }}</h2>
                                <p class="text-indigo-200 text-sm mt-1">Dibayarkan pada {{ $latest->payment_date ? $latest->payment_date->format('d M Y') : '-' }}</p>
                            </div>
                            <div class="mt-6 md:mt-0 text-right">
                                <p class="text-indigo-100 text-sm font-medium mb-1">Take Home Pay</p>
                                <div class="text-4xl font-bold tracking-tight">Rp {{ number_format($latest->gaji_bersih, 0, ',', '.') }}</div>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-white/20 flex flex-wrap gap-4">
                            <a href="{{ route('employee.payroll.show', $latest->id) }}" class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 backdrop-blur-sm border border-white/30 rounded-lg text-sm font-medium transition-all">
                                <i class="fa-solid fa-eye mr-2"></i> Lihat Detail
                            </a>
                            <a href="{{ route('employee.payroll.print', $latest->id) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-white text-indigo-600 hover:bg-indigo-50 rounded-lg text-sm font-medium transition-all shadow-sm">
                                <i class="fa-solid fa-download mr-2"></i> Unduh Slip Gaji
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Payroll History List -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Riwayat Pembayaran</h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                        <thead class="bg-gray-50 dark:bg-gray-700/50 text-xs uppercase text-gray-700 dark:text-gray-300">
                            <tr>
                                <th scope="col" class="px-6 py-4 font-medium">Periode</th>
                                <th scope="col" class="px-6 py-4 font-medium">Tanggal Bayar</th>
                                <th scope="col" class="px-6 py-4 font-medium text-right">Total Pendapatan</th>
                                <th scope="col" class="px-6 py-4 font-medium text-right">Total Potongan</th>
                                <th scope="col" class="px-6 py-4 font-medium text-right">Gaji Bersih</th>
                                <th scope="col" class="px-6 py-4 font-medium text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($payrolls as $payroll)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-medium text-gray-900 dark:text-white">
                                            {{ \Carbon\Carbon::createFromDate($payroll->period_year, $payroll->period_month, 1)->format('F Y') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $payroll->payment_date ? $payroll->payment_date->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-green-600 dark:text-green-400">
                                        Rp {{ number_format($payroll->total_pendapatan, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-red-600 dark:text-red-400">
                                        Rp {{ number_format($payroll->total_potongan, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-gray-900 dark:text-white">
                                        Rp {{ number_format($payroll->gaji_bersih, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center space-x-3">
                                            <a href="{{ route('employee.payroll.show', $payroll->id) }}" class="text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors" title="Lihat Detail">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            <a href="{{ route('employee.payroll.print', $payroll->id) }}" target="_blank" class="text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors" title="Unduh PDF">
                                                <i class="fa-solid fa-download"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fa-regular fa-folder-open text-4xl mb-3 text-gray-300 dark:text-gray-600"></i>
                                            <p>Belum ada riwayat penggajian.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($payrolls->hasPages())
                    <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $payrolls->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-employee-layout>