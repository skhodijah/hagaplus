<x-admin-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Slip Gaji</h1>
                <a href="{{ route('admin.payroll.show', $payroll) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    <i class="fa-solid fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('admin.payroll.update', $payroll) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informasi Karyawan & Periode</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Karyawan *</label>
                        <select id="user_id" name="user_id" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="">Pilih Karyawan</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ old('user_id', $payroll->user_id) == $employee->id ? 'selected' : '' }}>
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
                                <option value="{{ $m }}" {{ old('period_month', $payroll->period_month) == $m ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label for="period_year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tahun *</label>
                        <select id="period_year" name="period_year" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            @for ($y = date('Y') + 1; $y >= 2020; $y--)
                                <option value="{{ $y }}" {{ old('period_year', $payroll->period_year) == $y ? 'selected' : '' }}>{{ $y }}</option>
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
                        <input type="number" id="gaji_pokok" name="gaji_pokok" value="{{ old('gaji_pokok', $payroll->gaji_pokok) }}" step="0.01" min="0" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>

                    <div>
                        <label for="tunjangan_jabatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tunjangan Jabatan</label>
                        <input type="number" id="tunjangan_jabatan" name="tunjangan_jabatan" value="{{ old('tunjangan_jabatan', $payroll->tunjangan_jabatan) }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>

                    <div>
                        <label for="tunjangan_makan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tunjangan Makan</label>
                        <input type="number" id="tunjangan_makan" name="tunjangan_makan" value="{{ old('tunjangan_makan', $payroll->tunjangan_makan) }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>

                    <div>
                        <label for="tunjangan_transport" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tunjangan Transport</label>
                        <input type="number" id="tunjangan_transport" name="tunjangan_transport" value="{{ old('tunjangan_transport', $payroll->tunjangan_transport) }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>

                    <div>
                        <label for="lembur" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Lembur</label>
                        <input type="number" id="lembur" name="lembur" value="{{ old('lembur', $payroll->lembur) }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>

                    <div>
                        <label for="bonus" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bonus</label>
                        <input type="number" id="bonus" name="bonus" value="{{ old('bonus', $payroll->bonus) }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>

                    <div>
                        <label for="reimburse" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Reimburse</label>
                        <input type="number" id="reimburse" name="reimburse" value="{{ old('reimburse', $payroll->reimburse) }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>

                    <div>
                        <label for="thr" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">THR</label>
                        <input type="number" id="thr" name="thr" value="{{ old('thr', $payroll->thr) }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>
                </div>

                <div class="mt-4 p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Total Pendapatan:</span>
                        <span id="total-pendapatan" class="text-lg font-bold text-green-600 dark:text-green-400">Rp 0</span>
                    </div>
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
                        <label for="bpjs_kesehatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">BPJS Kesehatan</label>
                        <input type="number" id="bpjs_kesehatan" name="bpjs_kesehatan" value="{{ old('bpjs_kesehatan', $payroll->bpjs_kesehatan) }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>

                    <div>
                        <label for="bpjs_tk" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">BPJS Ketenagakerjaan</label>
                        <input type="number" id="bpjs_tk" name="bpjs_tk" value="{{ old('bpjs_tk', $payroll->bpjs_tk) }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>

                    <div>
                        <label for="pph21" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">PPh21</label>
                        <input type="number" id="pph21" name="pph21" value="{{ old('pph21', $payroll->pph21) }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>

                    <div>
                        <label for="potongan_absensi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Potongan Absensi</label>
                        <input type="number" id="potongan_absensi" name="potongan_absensi" value="{{ old('potongan_absensi', $payroll->potongan_absensi) }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>

                    <div>
                        <label for="kasbon" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kasbon</label>
                        <input type="number" id="kasbon" name="kasbon" value="{{ old('kasbon', $payroll->kasbon) }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>

                    <div>
                        <label for="potongan_lainnya" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Potongan Lainnya</label>
                        <input type="number" id="potongan_lainnya" name="potongan_lainnya" value="{{ old('potongan_lainnya', $payroll->potongan_lainnya) }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>
                </div>

                <div class="mt-4 p-4 bg-red-50 dark:bg-red-900/20 rounded-lg">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Total Potongan:</span>
                        <span id="total-potongan" class="text-lg font-bold text-red-600 dark:text-red-400">Rp 0</span>
                    </div>
                </div>
            </div>

            <!-- INFORMASI REKENING KARYAWAN -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <i class="fa-solid fa-building-columns text-blue-600 mr-2"></i>
                    Informasi Rekening Karyawan
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg mb-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Nama Bank</label>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $payroll->bank_name ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Nomor Rekening</label>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $payroll->bank_account_number ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Atas Nama</label>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $payroll->bank_account_holder ?? '-' }}</p>
                    </div>
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Catatan</label>
                    <textarea id="notes" name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">{{ old('notes', $payroll->notes) }}</textarea>
                </div>
            </div>

            <!-- RINGKASAN -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-700 rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Ringkasan Gaji</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Total Pendapatan</div>
                        <div id="summary-pendapatan" class="text-2xl font-bold text-green-600 dark:text-green-400">Rp 0</div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Total Potongan</div>
                        <div id="summary-potongan" class="text-2xl font-bold text-red-600 dark:text-red-400">Rp 0</div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Gaji Bersih (THP)</div>
                        <div id="gaji-bersih" class="text-2xl font-bold text-blue-600 dark:text-blue-400">Rp 0</div>
                    </div>
                </div>
            </div>

            <!-- TOMBOL AKSI -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.payroll.show', $payroll) }}" class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded-lg transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition-colors">
                    <i class="fa-solid fa-save mr-2"></i>Update Slip Gaji
                </button>
            </div>
        </form>
    </div>

    <script>
        // Auto-calculate totals
        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(number);
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
            document.getElementById('total-pendapatan').textContent = formatRupiah(totalPendapatan);
            document.getElementById('total-potongan').textContent = formatRupiah(totalPotongan);
            document.getElementById('summary-pendapatan').textContent = formatRupiah(totalPendapatan);
            document.getElementById('summary-potongan').textContent = formatRupiah(totalPotongan);
            document.getElementById('gaji-bersih').textContent = formatRupiah(gajiBersih);
        }

        // Add event listeners to all input fields
        document.querySelectorAll('input[type="number"]').forEach(input => {
            input.addEventListener('input', calculateTotals);
        });

        // Calculate on page load
        calculateTotals();
    </script>
</x-admin-layout>