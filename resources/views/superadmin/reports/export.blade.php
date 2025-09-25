<x-superadmin-layout>
    <div class="py-2">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <x-page-header
                title="Export Data"
                subtitle="Ekspor data penting ke CSV, Excel, PDF, atau JSON"
                :show-period-filter="false"
            />

            <x-section-card title="Pilih Data untuk Diekspor">
                <form method="GET" action="#">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm mb-1">Tipe Data</label>
                            <select class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white">
                                <option>Instansi</option>
                                <option>Karyawan</option>
                                <option>Absensi</option>
                                <option>Payroll</option>
                                <option>Subscriptions</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Format</label>
                            <select class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white">
                                <option>CSV</option>
                                <option>Excel</option>
                                <option>PDF</option>
                                <option>JSON</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md" type="button">Eksport</button>
                    </div>
                </form>
            </x-section-card>
        </div>
    </div>
</x-superadmin-layout> 