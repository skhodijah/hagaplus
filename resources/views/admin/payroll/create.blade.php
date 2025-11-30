<x-admin-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Tambah Slip Gaji</h1>
                <a href="{{ route('admin.payroll.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    <i class="fa-solid fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('admin.payroll.store') }}" class="space-y-6">
            @csrf

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informasi Karyawan & Periode</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Karyawan *</label>
                        <select id="user_id" name="user_id" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="">Pilih Karyawan</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ old('user_id') == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->name }} - {{ $employee->email }}
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="period_month" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bulan *</label>
                        <select id="period_month" name="period_month" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ old('period_month', date('n')) == $m ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label for="period_year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tahun *</label>
                        <select id="period_year" name="period_year" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            @for ($y = date('Y') + 1; $y >= 2020; $y--)
                                <option value="{{ $y }}" {{ old('period_year', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>

            <!-- PENDAPATAN -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <i class="fa-solid fa-plus-circle text-green-600 mr-2"></i>
                    PENDAPATAN
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="gaji_pokok" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gaji Pokok *</label>
                        <input type="number" id="gaji_pokok" name="gaji_pokok" value="{{ old('gaji_pokok', 0) }}" step="0.01" min="0" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>

                    <div>
                        <label for="tunjangan_jabatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tunjangan Jabatan</label>
                        <input type="number" id="tunjangan_jabatan" name="tunjangan_jabatan" value="{{ old('tunjangan_jabatan', 0) }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>

                    <div>
                        <label for="tunjangan_makan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tunjangan Makan</label>
                        <input type="number" id="tunjangan_makan" name="tunjangan_makan" value="{{ old('tunjangan_makan', 0) }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>

                    <div>
                        <label for="tunjangan_transport" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tunjangan Transport</label>
                        <input type="number" id="tunjangan_transport" name="tunjangan_transport" value="{{ old('tunjangan_transport', 0) }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>

                    <div>
                        <label for="lembur" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Lembur</label>
                        <input type="number" id="lembur" name="lembur" value="{{ old('lembur', 0) }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>

                    <div>
                        <label for="bonus" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bonus</label>
                        <input type="number" id="bonus" name="bonus" value="{{ old('bonus', 0) }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>

                    <div>
                        <label for="reimburse" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Reimburse</label>
                        <input type="number" id="reimburse" name="reimburse" value="{{ old('reimburse', 0) }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>

                    <div>
                        <label for="thr" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">THR</label>
                        <input type="number" id="thr" name="thr" value="{{ old('thr', 0) }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>
                </div>

                <div class="mt-4 p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                    <p class="text-sm font-medium text-green-800 dark:text-green-300">
                        Total Pendapatan: <span id="total_pendapatan" class="font-bold">Rp 0</span>
                    </p>
                </div>
            </div>

            <!-- POTONGAN -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <i class="fa-solid fa-minus-circle text-red-600 mr-2"></i>
                    POTONGAN
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="bpjs_kesehatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">BPJS Kesehatan (1%)</label>
                        <input type="number" id="bpjs_kesehatan" name="bpjs_kesehatan" value="{{ old('bpjs_kesehatan', 0) }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>

                    <div>
                        <label for="bpjs_tk" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">BPJS TK (2% + 1% JP)</label>
                        <input type="number" id="bpjs_tk" name="bpjs_tk" value="{{ old('bpjs_tk', 0) }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>

                    <div>
                        <label for="pph21" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">PPh21</label>
                        <input type="number" id="pph21" name="pph21" value="{{ old('pph21', 0) }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>

                    <div>
                        <label for="potongan_absensi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Potongan Absensi (Telat/Alpa)</label>
                        <input type="number" id="potongan_absensi" name="potongan_absensi" value="{{ old('potongan_absensi', 0) }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>

                    <div>
                        <label for="kasbon" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kasbon</label>
                        <input type="number" id="kasbon" name="kasbon" value="{{ old('kasbon', 0) }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>

                    <div>
                        <label for="potongan_lainnya" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Potongan Lain-lain</label>
                        <input type="number" id="potongan_lainnya" name="potongan_lainnya" value="{{ old('potongan_lainnya', 0) }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>
                </div>

                <div class="mt-4 p-4 bg-red-50 dark:bg-red-900/20 rounded-lg">
                    <p class="text-sm font-medium text-red-800 dark:text-red-300">
                        Total Potongan: <span id="total_potongan" class="font-bold">Rp 0</span>
                    </p>
                </div>
            </div>

            <!-- Calculation Details -->
            <div id="calculation_details" class="bg-blue-50 dark:bg-blue-900/20 rounded-lg shadow-sm p-6 hidden">
                <h2 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-4 flex items-center">
                    <i class="fa-solid fa-calculator text-blue-600 mr-2"></i>
                    Rincian Perhitungan
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <h3 class="font-medium text-blue-800 dark:text-blue-200 mb-2">Absensi</h3>
                        <ul class="space-y-1 text-blue-700 dark:text-blue-300">
                            <li class="flex justify-between">
                                <span>Jumlah Alpa:</span>
                                <span id="detail_absent_days">0 hari</span>
                            </li>
                            <li class="flex justify-between">
                                <span>Keterlambatan:</span>
                                <span id="detail_late_minutes">0 menit</span>
                            </li>
                            <li class="flex justify-between border-t border-blue-200 dark:border-blue-700 pt-1 mt-1">
                                <span>Total Potongan Absensi:</span>
                                <span id="detail_total_absent_deduction" class="font-bold">Rp 0</span>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-medium text-blue-800 dark:text-blue-200 mb-2">Lembur</h3>
                        <ul class="space-y-1 text-blue-700 dark:text-blue-300">
                            <li class="flex justify-between">
                                <span>Total Lembur:</span>
                                <span id="detail_overtime_minutes">0 menit</span>
                            </li>
                            <li class="flex justify-between">
                                <span>Rate per Jam:</span>
                                <span id="detail_overtime_rate">Rp 0</span>
                            </li>
                            <li class="flex justify-between border-t border-blue-200 dark:border-blue-700 pt-1 mt-1">
                                <span>Total Uang Lembur:</span>
                                <span id="detail_total_overtime" class="font-bold">Rp 0</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- TAKE HOME PAY -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-lg shadow-sm p-6 border-2 border-blue-200 dark:border-blue-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <i class="fa-solid fa-wallet text-blue-600 mr-2"></i>
                    TAKE HOME PAY
                </h2>
                
                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Total Pendapatan:</span>
                        <span id="thp_pendapatan" class="text-sm font-medium text-gray-900 dark:text-white">Rp 0</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Total Potongan:</span>
                        <span id="thp_potongan" class="text-sm font-medium text-gray-900 dark:text-white">Rp 0</span>
                    </div>
                    <div class="border-t-2 border-blue-300 dark:border-blue-600 pt-2 mt-2">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-gray-900 dark:text-white">Gaji Bersih (THP):</span>
                            <span id="gaji_bersih" class="text-lg font-bold text-blue-600 dark:text-blue-400">Rp 0</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Rekening Karyawan -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <i class="fa-solid fa-building-columns text-blue-600 mr-2"></i>
                    Informasi Rekening Karyawan
                </h2>
                
                <div id="bank-info" class="hidden">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Nama Bank</label>
                            <p id="display_bank_name" class="text-sm font-semibold text-gray-900 dark:text-white">-</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Nomor Rekening</label>
                            <p id="display_bank_account" class="text-sm font-semibold text-gray-900 dark:text-white">-</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Atas Nama</label>
                            <p id="display_account_holder" class="text-sm font-semibold text-gray-900 dark:text-white">-</p>
                        </div>
                    </div>
                </div>

                <div id="bank-info-empty" class="text-center py-4 text-gray-500 dark:text-gray-400">
                    <i class="fa-solid fa-info-circle mr-2"></i>
                    Pilih karyawan untuk melihat informasi rekening
                </div>

                <div class="mt-4">
                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Catatan</label>
                    <textarea id="notes" name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" placeholder="Catatan tambahan (opsional)">{{ old('notes') }}</textarea>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.payroll.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded">
                    Batal
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                    <i class="fa-solid fa-save mr-2"></i>Simpan
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        function formatRupiah(angka) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(angka);
        }

        function calculateTotals() {
            // Pendapatan
            const gajiPokok = parseFloat(document.getElementById('gaji_pokok').value) || 0;
            const tunjanganJabatan = parseFloat(document.getElementById('tunjangan_jabatan').value) || 0;
            const tunjanganMakan = parseFloat(document.getElementById('tunjangan_makan').value) || 0;
            const tunjanganTransport = parseFloat(document.getElementById('tunjangan_transport').value) || 0;
            const lembur = parseFloat(document.getElementById('lembur').value) || 0;
            const bonus = parseFloat(document.getElementById('bonus').value) || 0;
            const reimburse = parseFloat(document.getElementById('reimburse').value) || 0;
            const thr = parseFloat(document.getElementById('thr').value) || 0;

            const totalPendapatan = gajiPokok + tunjanganJabatan + tunjanganMakan + tunjanganTransport + lembur + bonus + reimburse + thr;

            // Potongan
            const bpjsKesehatan = parseFloat(document.getElementById('bpjs_kesehatan').value) || 0;
            const bpjsTk = parseFloat(document.getElementById('bpjs_tk').value) || 0;
            const pph21 = parseFloat(document.getElementById('pph21').value) || 0;
            const potonganAbsensi = parseFloat(document.getElementById('potongan_absensi').value) || 0;
            const kasbon = parseFloat(document.getElementById('kasbon').value) || 0;
            const potonganLainnya = parseFloat(document.getElementById('potongan_lainnya').value) || 0;

            const totalPotongan = bpjsKesehatan + bpjsTk + pph21 + potonganAbsensi + kasbon + potonganLainnya;

            // Gaji Bersih
            const gajiBersih = totalPendapatan - totalPotongan;

            // Update display
            document.getElementById('total_pendapatan').textContent = formatRupiah(totalPendapatan);
            document.getElementById('total_potongan').textContent = formatRupiah(totalPotongan);
            document.getElementById('thp_pendapatan').textContent = formatRupiah(totalPendapatan);
            document.getElementById('thp_potongan').textContent = formatRupiah(totalPotongan);
            document.getElementById('gaji_bersih').textContent = formatRupiah(gajiBersih);
        }

        async function fetchPayrollData() {
            const userId = document.getElementById('user_id').value;
            const month = document.getElementById('period_month').value;
            const year = document.getElementById('period_year').value;

            if (!userId || !month || !year) return;

            // Show loading state if needed
            
            try {
                const response = await fetch('{{ route("admin.payroll.calculate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        user_id: userId,
                        month: month,
                        year: year
                    })
                });

                if (!response.ok) throw new Error('Failed to fetch data');

                const data = await response.json();

                // Update form fields
                document.getElementById('gaji_pokok').value = data.gaji_pokok;
                document.getElementById('tunjangan_jabatan').value = data.tunjangan_jabatan;
                document.getElementById('tunjangan_makan').value = data.tunjangan_makan;
                document.getElementById('tunjangan_transport').value = data.tunjangan_transport;
                document.getElementById('lembur').value = data.lembur;
                document.getElementById('bpjs_kesehatan').value = data.bpjs_kesehatan;
                document.getElementById('bpjs_tk').value = data.bpjs_tk;
                document.getElementById('potongan_absensi').value = data.potongan_absensi;
                document.getElementById('pph21').value = data.pph21;

                // Update Details Section
                if (data.details) {
                    document.getElementById('calculation_details').classList.remove('hidden');
                    document.getElementById('detail_absent_days').textContent = data.details.absent_days + ' hari';
                    document.getElementById('detail_late_minutes').textContent = data.details.late_minutes + ' menit';
                    document.getElementById('detail_total_absent_deduction').textContent = formatRupiah(data.potongan_absensi);
                    document.getElementById('detail_overtime_minutes').textContent = data.details.overtime_minutes + ' menit';
                    document.getElementById('detail_overtime_rate').textContent = formatRupiah(data.details.overtime_rate_per_hour) + '/jam';
                    document.getElementById('detail_total_overtime').textContent = formatRupiah(data.lembur);
                }

                // Update Bank Info
                if (data.bank_name || data.bank_account_number || data.bank_account_holder) {
                    document.getElementById('bank-info').classList.remove('hidden');
                    document.getElementById('bank-info-empty').classList.add('hidden');
                    document.getElementById('display_bank_name').textContent = data.bank_name || '-';
                    document.getElementById('display_bank_account').textContent = data.bank_account_number || '-';
                    document.getElementById('display_account_holder').textContent = data.bank_account_holder || '-';
                } else {
                    document.getElementById('bank-info').classList.add('hidden');
                    document.getElementById('bank-info-empty').classList.remove('hidden');
                }

                // Recalculate totals
                calculateTotals();

            } catch (error) {
                console.error('Error fetching payroll data:', error);
                // Optional: Show error message to user
            }
        }

        // Add event listeners to all input fields
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('input[type="number"]');
            inputs.forEach(input => {
                input.addEventListener('input', calculateTotals);
            });

            // Listen for changes in user, month, year to fetch data
            document.getElementById('user_id').addEventListener('change', fetchPayrollData);
            document.getElementById('period_month').addEventListener('change', fetchPayrollData);
            document.getElementById('period_year').addEventListener('change', fetchPayrollData);

            // Initial calculation
            calculateTotals();
        });
    </script>
    @endpush
</x-admin-layout>