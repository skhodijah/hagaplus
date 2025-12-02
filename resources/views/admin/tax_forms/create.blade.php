<x-admin-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-bold mb-6">Generate SPT 1721-A1</h2>

                    <form action="{{ route('admin.tax-forms.store') }}" method="POST">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Karyawan</label>
                                <select name="employee_ids[]" id="employee_id" class="w-full rounded border-gray-300" required>
                                    <option value="">-- Pilih Karyawan --</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->user->name }} ({{ $employee->nik }})</option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Pilih satu karyawan untuk input manual.</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Pajak</label>
                                <select name="tax_year" id="tax_year" class="w-full rounded border-gray-300">
                                    @for($y = date('Y'); $y >= 2020; $y--)
                                        <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Masa Awal</label>
                                    <select name="period_start" id="period_start" class="w-full rounded border-gray-300">
                                        @foreach(range(1, 12) as $m)
                                            <option value="{{ $m }}" {{ $m == 1 ? 'selected' : '' }}>{{ $m }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Masa Akhir</label>
                                    <select name="period_end" id="period_end" class="w-full rounded border-gray-300">
                                        @foreach(range(1, 12) as $m)
                                            <option value="{{ $m }}" {{ $m == 12 ? 'selected' : '' }}>{{ $m }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6 flex justify-end">
                            <button type="button" id="btn-calculate" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                <i class="fas fa-calculator mr-2"></i> Hitung & Isi Otomatis
                            </button>
                        </div>

                        <div id="form-container" class="hidden border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">A. Identitas Penerima Penghasilan</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">NPWP</label>
                                    <input type="text" name="data[a_01_npwp]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">NIK</label>
                                    <input type="text" name="data[a_02_nik]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nama</label>
                                    <input type="text" name="data[a_03_nama]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Alamat</label>
                                    <input type="text" name="data[a_04_alamat]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Jabatan</label>
                                    <input type="text" name="data[a_07_jabatan]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Status PTKP (K/TK/HB)</label>
                                    <input type="text" name="data[a_06_status_ptkp]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                            </div>

                            <h3 class="text-lg font-medium text-gray-900 mb-4">B. Rincian Penghasilan</h3>
                            <div class="space-y-4 mb-6">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                                    <label class="md:col-span-2 text-sm font-medium text-gray-700">1. Gaji/Pensiun atau THT/JHT</label>
                                    <input type="number" name="data[b_01_gaji]" class="calc-trigger mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                                    <label class="md:col-span-2 text-sm font-medium text-gray-700">2. Tunjangan PPh</label>
                                    <input type="number" name="data[b_02_tunjangan_pph]" class="calc-trigger mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                                    <label class="md:col-span-2 text-sm font-medium text-gray-700">3. Tunjangan Lainnya, Uang Lembur, dsb</label>
                                    <input type="number" name="data[b_03_tunjangan_lain]" class="calc-trigger mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                                    <label class="md:col-span-2 text-sm font-medium text-gray-700">4. Honorarium dan Imbalan Lain</label>
                                    <input type="number" name="data[b_04_honorarium]" class="calc-trigger mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                                    <label class="md:col-span-2 text-sm font-medium text-gray-700">5. Premi Asuransi yang dibayar Pemberi Kerja</label>
                                    <input type="number" name="data[b_05_premi_asuransi]" class="calc-trigger mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                                    <label class="md:col-span-2 text-sm font-medium text-gray-700">6. Penerimaan dalam bentuk Natura</label>
                                    <input type="number" name="data[b_06_natura]" class="calc-trigger mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                                    <label class="md:col-span-2 text-sm font-medium text-gray-700">7. Tantiem, Bonus, Gratifikasi, THR</label>
                                    <input type="number" name="data[b_07_bonus]" class="calc-trigger mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center bg-gray-50 p-2 rounded">
                                    <label class="md:col-span-2 text-sm font-bold text-gray-900">8. Jumlah Penghasilan Bruto (1 s.d 7)</label>
                                    <input type="number" name="data[b_08_bruto]" readonly class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm">
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                                    <label class="md:col-span-2 text-sm font-medium text-gray-700">9. Biaya Jabatan</label>
                                    <input type="number" name="data[b_09_biaya_jabatan]" class="calc-trigger mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                                    <label class="md:col-span-2 text-sm font-medium text-gray-700">10. Iuran Pensiun atau THT/JHT</label>
                                    <input type="number" name="data[b_10_iuran_pensiun]" class="calc-trigger mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center bg-gray-50 p-2 rounded">
                                    <label class="md:col-span-2 text-sm font-bold text-gray-900">11. Jumlah Pengurangan (9 s.d 10)</label>
                                    <input type="number" name="data[b_11_total_pengurangan]" readonly class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm">
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center bg-gray-50 p-2 rounded">
                                    <label class="md:col-span-2 text-sm font-bold text-gray-900">12. Jumlah Penghasilan Neto (8 - 11)</label>
                                    <input type="number" name="data[b_12_netto]" readonly class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm">
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                                    <label class="md:col-span-2 text-sm font-medium text-gray-700">17. PPh Pasal 21 Terutang</label>
                                    <input type="number" name="data[b_17_pph_terutang]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                                    <label class="md:col-span-2 text-sm font-medium text-gray-700">20. PPh Pasal 21 yang Telah Dipotong dan Dilunasi</label>
                                    <input type="number" name="data[b_20_pph_dilunasi]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                
                                <!-- Hidden fields for other calculations to ensure data integrity on save -->
                                <input type="hidden" name="data[h_01_nomor]">
                                <input type="hidden" name="data[h_03_npwp_pemotong]">
                                <input type="hidden" name="data[h_04_nama_pemotong]">
                                <input type="hidden" name="data[a_05_jenis_kelamin]">
                                <input type="hidden" name="data[a_09_negara]">
                                <input type="hidden" name="data[b_13_netto_lalu]">
                                <input type="hidden" name="data[b_14_netto_setahun]">
                                <input type="hidden" name="data[b_15_ptkp]">
                                <input type="hidden" name="data[b_16_pkp]">
                                <input type="hidden" name="data[b_18_pph_lalu]">
                                <input type="hidden" name="data[b_19_pph_terutang_total]">
                                <input type="hidden" name="data[c_03_tanggal]">
                            </div>

                            <div class="flex justify-end">
                                <a href="{{ route('admin.tax-forms.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">Batal</a>
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Simpan SPT
                                </button>
                            </div>
                        </div>

                        @push('scripts')
                        <script>
                            document.getElementById('btn-calculate').addEventListener('click', function() {
                                const employeeId = document.getElementById('employee_id').value;
                                const taxYear = document.getElementById('tax_year').value;
                                const periodStart = document.getElementById('period_start').value;
                                const periodEnd = document.getElementById('period_end').value;

                                if (!employeeId) {
                                    alert('Pilih karyawan terlebih dahulu.');
                                    return;
                                }

                                fetch('{{ route("admin.tax-forms.calculate") }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({
                                        employee_id: employeeId,
                                        tax_year: taxYear,
                                        period_start: periodStart,
                                        period_end: periodEnd
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    // Populate fields
                                    for (const [key, value] of Object.entries(data)) {
                                        const input = document.querySelector(`[name="data[${key}]"]`);
                                        if (input) {
                                            input.value = value;
                                        }
                                    }
                                    // Show form
                                    document.getElementById('form-container').classList.remove('hidden');
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    alert('Gagal menghitung data. Silakan coba lagi.');
                                });
                            });
                            
                            // Basic client-side calculation for totals
                            const inputs = document.querySelectorAll('.calc-trigger');
                            inputs.forEach(input => {
                                input.addEventListener('input', calculateTotals);
                            });
                            
                            function calculateTotals() {
                                const getVal = (name) => parseFloat(document.querySelector(`[name="data[${name}]"]`).value) || 0;
                                
                                const bruto = getVal('b_01_gaji') + getVal('b_02_tunjangan_pph') + getVal('b_03_tunjangan_lain') + 
                                              getVal('b_04_honorarium') + getVal('b_05_premi_asuransi') + getVal('b_06_natura') + 
                                              getVal('b_07_bonus');
                                              
                                document.querySelector(`[name="data[b_08_bruto]"]`).value = bruto;
                                
                                const pengurangan = getVal('b_09_biaya_jabatan') + getVal('b_10_iuran_pensiun');
                                document.querySelector(`[name="data[b_11_total_pengurangan]"]`).value = pengurangan;
                                
                                const netto = bruto - pengurangan;
                                document.querySelector(`[name="data[b_12_netto]"]`).value = netto;
                            }
                        </script>
                        @endpush
</x-admin-layout>
