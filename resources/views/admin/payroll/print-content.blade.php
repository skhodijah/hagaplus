@php
    // Debug info - hapus setelah selesai debug
    if (config('app.debug')) {
        \Log::info('Payroll Debug', [
            'payroll_id' => $payroll->id,
            'user_id' => $payroll->user_id,
            'user_name' => $payroll->user->name ?? 'null',
            'user_instansi_id' => $payroll->user->instansi_id ?? 'null',
            'instansi_name' => $payroll->user->instansi->nama_instansi ?? 'null',
        ]);
    }
@endphp

@php
    $startOfMonth = \Carbon\Carbon::createFromDate($payroll->period_year, $payroll->period_month, 1)->startOfMonth();
    $endOfMonth = $startOfMonth->copy()->endOfMonth();
    
    $attendances = \App\Models\Admin\Attendance::where('user_id', $payroll->user_id)
        ->whereBetween('attendance_date', [$startOfMonth, $endOfMonth])
        ->get();
    
    $absentCount = $attendances->where('status', 'absent')->count();
    $lateMinutes = $attendances->sum('late_minutes');
    $overtimeMinutes = $attendances->sum('overtime_duration');
    $presentCount = $attendances->where('status', 'present')->count();
    
    $workingDays = $startOfMonth->diffInDaysFiltered(function(\Carbon\Carbon $date) {
        return !$date->isWeekend();
    }, $endOfMonth) + 1;
@endphp

<div class="bg-gray-100 dark:bg-gray-900 p-8 rounded-xl shadow-inner overflow-auto relative">
    <!-- Watermark -->
    @if($payroll->user->instansi && $payroll->user->instansi->logo)
    <div class="absolute inset-0 flex items-center justify-center opacity-5 pointer-events-none">
        <img src="{{ asset('storage/' . $payroll->user->instansi->logo) }}" class="w-1/2" />
    </div>
    @endif

    <div class="bg-white mx-auto shadow-lg relative z-10" style="width: 210mm; min-height: 297mm; padding: 12mm;">
        <!-- HEADER -->
        <div class="flex justify-between items-start">
            <div class="flex items-start space-x-2">
                @if($payroll->user->instansi && $payroll->user->instansi->logo)
                <img src="{{ asset('storage/' . $payroll->user->instansi->logo) }}" class="w-12" />
                @endif
                <div>
                    <h1 class="text-lg font-bold text-black">
                        @if($payroll->user && $payroll->user->instansi)
                            {{ $payroll->user->instansi->nama_instansi }}
                        @else
                            Company Name
                        @endif
                    </h1>
                    <p class="leading-tight text-[10px] text-black">
                        @if($payroll->user && $payroll->user->instansi)
                            {{ $payroll->user->instansi->address ?? '' }}<br>
                            Telp: {{ $payroll->user->instansi->phone ?? '' }}
                        @else
                            Company Address<br>
                            Telp: -
                        @endif
                    </p>
                </div>
            </div>

            <div class="text-right">
                <h2 class="text-base font-bold text-black">SLIP GAJI</h2>
                <p class="text-[10px] text-black">Periode: {{ \Carbon\Carbon::createFromDate($payroll->period_year, $payroll->period_month, 1)->format('M Y') }}</p>
            </div>
        </div>

        <!-- IDENTITAS KARYAWAN -->
        <div class="grid grid-cols-3 gap-8 mt-8 text-black text-[11px]">
            <div>
                <p><b>ID Karyawan:</b> {{ $payroll->user->employee->employee_id ?? '-' }}</p>
                <p><b>Nama:</b> {{ $payroll->user->name }}</p>
                <p><b>Organization:</b> {{ $payroll->user->employee->division->name ?? '-' }}</p>
            </div>
            <div>
                <p><b>Jabatan:</b> {{ $payroll->user->employee->position->name ?? '-' }}</p>
                <p><b>PTKP:</b> {{ $payroll->user->employee->ptkp_status ?? '-' }}</p>
                <p><b>NPWP:</b> {{ $payroll->user->employee->npwp ?? '-' }}</p>
            </div>
            <div>
                <p><b>Grade / Level:</b> {{ $payroll->user->employee->grade_level ?? '-' }}</p>
                <p><b>Tanggal Masuk:</b> {{ $payroll->user->employee->hire_date ? $payroll->user->employee->hire_date->format('d M Y') : '-' }}</p>
            </div>
        </div>

        <!-- TABEL PENDAPATAN & POTONGAN -->
        <div class="grid grid-cols-2 gap-4 mt-3 text-black text-[11px]">
            <!-- PENDAPATAN -->
            <div>
                <h2 class="border-b border-black font-semibold pb-1">Pendapatan</h2>
                <table class="w-full text-xs border border-black">
                    <tbody>
                        <tr><td class="p-1 border border-black">Gaji Pokok</td><td class="p-1 border border-black text-right">{{ number_format($payroll->gaji_pokok, 0, ',', '.') }}</td></tr>
                        <tr><td class="p-1 border border-black">Tunjangan Jabatan</td><td class="p-1 border border-black text-right">{{ number_format($payroll->tunjangan_jabatan, 0, ',', '.') }}</td></tr>
                        <tr><td class="p-1 border border-black">Tunjangan Makan</td><td class="p-1 border border-black text-right">{{ number_format($payroll->tunjangan_makan, 0, ',', '.') }}</td></tr>
                        <tr><td class="p-1 border border-black">Tunjangan Transport</td><td class="p-1 border border-black text-right">{{ number_format($payroll->tunjangan_transport, 0, ',', '.') }}</td></tr>
                        <tr><td class="p-1 border border-black">Lembur</td><td class="p-1 border border-black text-right">{{ number_format($payroll->lembur, 0, ',', '.') }}</td></tr>
                        <tr><td class="p-1 border border-black">Bonus</td><td class="p-1 border border-black text-right">{{ number_format($payroll->bonus, 0, ',', '.') }}</td></tr>
                        <tr><td class="p-1 border border-black">Reimburse</td><td class="p-1 border border-black text-right">{{ number_format($payroll->reimburse, 0, ',', '.') }}</td></tr>
                        <tr><td class="p-1 border border-black">THR</td><td class="p-1 border border-black text-right">{{ number_format($payroll->thr, 0, ',', '.') }}</td></tr>
                        <tr class="font-semibold bg-gray-100"><td class="p-1 border border-black">Total Pendapatan</td><td class="p-1 border border-black text-right">{{ number_format($payroll->total_pendapatan, 0, ',', '.') }}</td></tr>
                    </tbody>
                </table>
            </div>

            <!-- POTONGAN -->
            <div>
                <h2 class="border-b border-black font-semibold pb-1">Potongan</h2>
                <table class="w-full text-xs border border-black">
                    <tbody>
                        <tr><td class="p-1 border border-black">BPJS Kesehatan</td><td class="p-1 border border-black text-right">{{ number_format($payroll->bpjs_kesehatan, 0, ',', '.') }}</td></tr>
                        <tr><td class="p-1 border border-black">BPJS Ketenagakerjaan</td><td class="p-1 border border-black text-right">{{ number_format($payroll->bpjs_tk, 0, ',', '.') }}</td></tr>
                        <tr><td class="p-1 border border-black">PPh21</td><td class="p-1 border border-black text-right">{{ number_format($payroll->pph21, 0, ',', '.') }}</td></tr>
                        <tr><td class="p-1 border border-black">Potongan Absensi</td><td class="p-1 border border-black text-right">{{ number_format($payroll->potongan_absensi, 0, ',', '.') }}</td></tr>
                        <tr><td class="p-1 border border-black">Kasbon</td><td class="p-1 border border-black text-right">{{ number_format($payroll->kasbon, 0, ',', '.') }}</td></tr>
                        <tr><td class="p-1 border border-black">Potongan Lainnya</td><td class="p-1 border border-black text-right">{{ number_format($payroll->potongan_lainnya, 0, ',', '.') }}</td></tr>
                        <tr class="font-semibold bg-gray-100"><td class="p-1 border border-black">Total Potongan</td><td class="p-1 border border-black text-right">{{ number_format($payroll->total_potongan, 0, ',', '.') }}</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TAKE HOME PAY -->
        <table class="w-full text-xs border border-black mt-3 text-black">
            <tbody>
                <tr class="font-semibold bg-gray-100">
                    <td class="p-1 border border-black">Take Home Pay</td>
                    <td class="p-1 border border-black text-right">{{ number_format($payroll->gaji_bersih, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <!-- RINCIAN PERHITUNGAN -->
        <div class="mt-3 border border-black p-2 text-black">
            <h3 class="font-semibold text-xs mb-2">Rincian Perhitungan</h3>
            <div class="grid grid-cols-2 gap-4 text-[10px]">
                <div>
                    <p><b>Absensi:</b></p>
                    <ul class="ml-4 mt-1">
                        <li>• Jumlah Alpa: {{ $absentCount }} hari</li>
                        <li>• Total Keterlambatan: {{ $lateMinutes }} menit</li>
                        <li>• Potongan Absensi: Rp {{ number_format($payroll->potongan_absensi, 0, ',', '.') }}</li>
                    </ul>
                </div>
                <div>
                    <p><b>Lembur:</b></p>
                    <ul class="ml-4 mt-1">
                        <li>• Total Lembur: {{ $overtimeMinutes }} menit ({{ number_format($overtimeMinutes / 60, 1) }} jam)</li>
                        <li>• Uang Lembur: Rp {{ number_format($payroll->lembur, 0, ',', '.') }}</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- ATTENDANCE & SIGNATURE -->
        <div class="grid grid-cols-2 mt-4 w-full text-black text-[11px]">
            <!-- Attendance -->
            <div class="pr-4">
                <h2 class="border-b border-black font-semibold pb-1">Attendance Summary</h2>
                <table class="w-full text-xs border border-black">
                    <tbody>
                        <tr><td class="p-1 border border-black">Actual Working Day</td><td class="p-1 border border-black text-right">{{ $presentCount }}</td></tr>
                        <tr><td class="p-1 border border-black">Schedule Working Day</td><td class="p-1 border border-black text-right">{{ $workingDays }}</td></tr>
                        <tr><td class="p-1 border border-black">Absent</td><td class="p-1 border border-black text-right">{{ $absentCount }}</td></tr>
                        <tr><td class="p-1 border border-black">Late (minutes)</td><td class="p-1 border border-black text-right">{{ $lateMinutes }}</td></tr>
                        <tr><td class="p-1 border border-black">Overtime (minutes)</td><td class="p-1 border border-black text-right">{{ $overtimeMinutes }}</td></tr>
                    </tbody>
                </table>

                <p class="text-[10px] mt-2 leading-tight">Catatan: Data kehadiran dicatat berdasarkan laporan sistem dan validasi atasan.</p>
                <p class="text-[10px] leading-tight">Harap hubungi HR jika terdapat ketidaksesuaian.</p>
            </div>

            <!-- Signature -->
            <div class="flex flex-col justify-end text-right">
                <p>Mengetahui,</p>
                @if($payroll->approver)
                <p class="mt-1 text-[10px]">{{ $payroll->approver->employee->position->name ?? 'Manager' }}</p>
                <div class="mt-12 mb-1">
                    <p class="font-bold">{{ $payroll->approver->name }}</p>
                    <p class="text-[10px]">{{ $payroll->approver->employee->employee_id ?? '-' }}</p>
                </div>
                @else
                <p class="mt-1 text-[10px]">HR Manager</p>
                <div class="mt-12 mb-1">
                    <p class="font-bold">_________________</p>
                    <p class="text-[10px]">ID: -</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
