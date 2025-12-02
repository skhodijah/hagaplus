<x-employee-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-bold mb-6">SPT Tahunan (1721-A1)</h2>

                    @if($forms->isEmpty())
                        <div class="text-center py-10 text-gray-500">
                            <i class="fa-solid fa-file-invoice-dollar text-4xl mb-3"></i>
                            <p>Belum ada bukti potong pajak yang tersedia.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($forms as $form)
                                <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h3 class="font-bold text-lg">Tahun Pajak {{ $form->tax_year }}</h3>
                                            <p class="text-sm text-gray-500">Nomor: {{ $form->form_number }}</p>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-xs font-semibold bg-green-100 text-green-800 px-2 py-1 rounded-full">Tersedia</span>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <div class="flex justify-between text-sm mb-1">
                                            <span class="text-gray-600">Penghasilan Bruto:</span>
                                            <span class="font-medium">{{ number_format($form->data['b_08_bruto'] ?? 0, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600">PPh 21 Terutang:</span>
                                            <span class="font-medium">{{ number_format($form->data['b_19_pph_terutang_total'] ?? 0, 0, ',', '.') }}</span>
                                        </div>
                                    </div>

                                    <a href="{{ route('employee.tax-forms.show', $form->id) }}" target="_blank" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded transition-colors">
                                        <i class="fa-solid fa-download mr-2"></i> Unduh / Cetak
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-employee-layout>
