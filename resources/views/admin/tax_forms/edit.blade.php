<x-admin-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-bold mb-6">Edit SPT 1721-A1: {{ $taxForm->employee->user->name }}</h2>

                    <form action="{{ route('admin.tax-forms.update', $taxForm->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Header -->
                        <h3 class="font-bold text-lg mb-4 border-b pb-2">Header</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nomor Form</label>
                                <input type="text" name="data[h_01_nomor]" value="{{ $taxForm->data['h_01_nomor'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Masa Perolehan</label>
                                <div class="flex gap-2">
                                    <input type="number" name="period_start" value="{{ $taxForm->period_start }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" min="1" max="12">
                                    <span class="self-center">-</span>
                                    <input type="number" name="period_end" value="{{ $taxForm->period_end }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" min="1" max="12">
                                </div>
                            </div>
                        </div>

                        <!-- Rincian Penghasilan -->
                        <h3 class="font-bold text-lg mb-4 border-b pb-2">B. Rincian Penghasilan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            @foreach([
                                'b_01_gaji' => '1. Gaji/Pensiun/THT/JHT',
                                'b_02_tunjangan_pph' => '2. Tunjangan PPh',
                                'b_03_tunjangan_lain' => '3. Tunjangan Lainnya, Lembur, dsb',
                                'b_04_honorarium' => '4. Honorarium',
                                'b_05_premi_asuransi' => '5. Premi Asuransi (Pemberi Kerja)',
                                'b_06_natura' => '6. Natura',
                                'b_07_bonus' => '7. Bonus/THR',
                            ] as $key => $label)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">{{ $label }}</label>
                                <input type="number" name="data[{{ $key }}]" value="{{ $taxForm->data[$key] ?? 0 }}" class="calc-bruto mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            @endforeach
                            <div class="col-span-2 bg-gray-50 p-4 rounded">
                                <label class="block text-sm font-bold text-gray-700">8. Jumlah Penghasilan Bruto</label>
                                <input type="number" name="data[b_08_bruto]" value="{{ $taxForm->data['b_08_bruto'] ?? 0 }}" id="bruto" readonly class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm font-bold">
                            </div>
                        </div>

                        <!-- Pengurangan -->
                        <h3 class="font-bold text-lg mb-4 border-b pb-2">Pengurangan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">9. Biaya Jabatan</label>
                                <input type="number" name="data[b_09_biaya_jabatan]" value="{{ $taxForm->data['b_09_biaya_jabatan'] ?? 0 }}" class="calc-pengurangan mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">10. Iuran Pensiun/THT/JHT</label>
                                <input type="number" name="data[b_10_iuran_pensiun]" value="{{ $taxForm->data['b_10_iuran_pensiun'] ?? 0 }}" class="calc-pengurangan mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div class="col-span-2 bg-gray-50 p-4 rounded">
                                <label class="block text-sm font-bold text-gray-700">11. Jumlah Pengurangan</label>
                                <input type="number" name="data[b_11_total_pengurangan]" value="{{ $taxForm->data['b_11_total_pengurangan'] ?? 0 }}" id="total_pengurangan" readonly class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm font-bold">
                            </div>
                        </div>

                        <!-- Perhitungan PPh -->
                        <h3 class="font-bold text-lg mb-4 border-b pb-2">Perhitungan PPh Pasal 21</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">12. Jumlah Penghasilan Netto</label>
                                <input type="number" name="data[b_12_netto]" value="{{ $taxForm->data['b_12_netto'] ?? 0 }}" id="netto" readonly class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">15. PTKP</label>
                                <input type="number" name="data[b_15_ptkp]" value="{{ $taxForm->data['b_15_ptkp'] ?? 0 }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">16. PKP</label>
                                <input type="number" name="data[b_16_pkp]" value="{{ $taxForm->data['b_16_pkp'] ?? 0 }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">17. PPh Pasal 21 Terutang</label>
                                <input type="number" name="data[b_17_pph_terutang]" value="{{ $taxForm->data['b_17_pph_terutang'] ?? 0 }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-bold text-gray-700">19. PPh Pasal 21 Terutang (Total)</label>
                                <input type="number" name="data[b_19_pph_terutang_total]" value="{{ $taxForm->data['b_19_pph_terutang_total'] ?? 0 }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm font-bold">
                            </div>
                        </div>

                        <!-- Penandatangan -->
                        <h3 class="font-bold text-lg mb-4 border-b pb-2">C. Identitas Pemotong</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">NPWP Pemotong</label>
                                <input type="text" name="data[c_01_npwp_pemotong]" value="{{ $taxForm->data['c_01_npwp_pemotong'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Pemotong</label>
                                <input type="text" name="data[c_02_nama_pemotong]" value="{{ $taxForm->data['c_02_nama_pemotong'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                                <input type="text" name="data[c_03_tanggal]" value="{{ $taxForm->data['c_03_tanggal'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <a href="{{ route('admin.tax-forms.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">Batal</a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Simple auto-calculation script
        const brutoInputs = document.querySelectorAll('.calc-bruto');
        const penguranganInputs = document.querySelectorAll('.calc-pengurangan');
        const brutoField = document.getElementById('bruto');
        const reductionField = document.getElementById('total_pengurangan');
        const nettoField = document.getElementById('netto');

        function calculate() {
            let bruto = 0;
            brutoInputs.forEach(input => bruto += parseFloat(input.value || 0));
            brutoField.value = bruto;

            let reduction = 0;
            penguranganInputs.forEach(input => reduction += parseFloat(input.value || 0));
            reductionField.value = reduction;

            nettoField.value = bruto - reduction;
        }

        [...brutoInputs, ...penguranganInputs].forEach(input => {
            input.addEventListener('input', calculate);
        });
    </script>
    @endpush
</x-admin-layout>
