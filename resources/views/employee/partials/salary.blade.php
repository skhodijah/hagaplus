<div class="bg-gradient-to-r from-green-500 to-emerald-600 dark:from-green-600 dark:to-emerald-700 rounded-xl p-6 text-white">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-lg font-semibold">Informasi Gaji</h3>
            <p class="text-green-100 text-sm">Gaji pokok dan informasi terkait</p>
        </div>
        <div class="bg-white/20 rounded-full p-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
            </svg>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Current Salary -->
        <div class="bg-white/10 rounded-lg p-4">
            <div class="text-sm text-green-100 mb-1">Gaji Pokok</div>
            <div class="text-2xl font-bold">Rp {{ number_format($employee->salary ?? 0, 0, ',', '.') }}</div>
            <div class="text-xs text-green-200 mt-1">per bulan</div>
        </div>

        <!-- Last Payroll -->
        @if($lastPayroll > 0)
        <div class="bg-white/10 rounded-lg p-4">
            <div class="text-sm text-green-100 mb-1">Gaji Terakhir</div>
            <div class="text-2xl font-bold">Rp {{ number_format($lastPayroll, 0, ',', '.') }}</div>
            <div class="text-xs text-green-200 mt-1">sudah diterima</div>
        </div>
        @else
        <div class="bg-white/10 rounded-lg p-4">
            <div class="text-sm text-green-100 mb-1">Gaji Terakhir</div>
            <div class="text-2xl font-bold">-</div>
            <div class="text-xs text-green-200 mt-1">belum ada data</div>
        </div>
        @endif
    </div>

    <!-- Additional Info -->
    <div class="mt-4 pt-4 border-t border-white/20">
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <span class="text-green-100">Status Karyawan:</span>
                <span class="font-medium ml-2">{{ ucfirst($employee->status ?? 'active') }}</span>
            </div>
            <div>
                <span class="text-green-100">Tanggal Bergabung:</span>
                <span class="font-medium ml-2">{{ $employee->hire_date ? $employee->hire_date->format('d/m/Y') : '-' }}</span>
            </div>
        </div>
    </div>
</div>