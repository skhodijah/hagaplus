<x-admin-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold">SPT 1721-A1</h2>
                        <div class="flex gap-2">
                            <form action="{{ route('admin.tax-forms.bulk-publish') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mempublikasikan semua SPT tahun {{ request('year', date('Y')) }}?');">
                                @csrf
                                <input type="hidden" name="year" value="{{ request('year', date('Y')) }}">
                                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    Publish Semua
                                </button>
                            </form>
                            <a href="{{ route('admin.tax-forms.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Generate SPT Baru
                            </a>
                        </div>
                    </div>

                    <form method="GET" action="{{ route('admin.tax-forms.index') }}" class="mb-6 flex gap-4">
                        <select name="year" class="rounded border-gray-300" onchange="this.form.submit()">
                            @for($y = date('Y'); $y >= 2020; $y--)
                                <option value="{{ $y }}" {{ request('year', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Karyawan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Form</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bruto</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PPh 21 Terutang</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($forms as $form)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $form->employee->user->name ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $form->tax_year }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $form->form_number }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ number_format($form->data['b_08_bruto'] ?? 0, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ number_format($form->data['b_19_pph_terutang_total'] ?? 0, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $form->is_published ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ $form->is_published ? 'Published' : 'Draft' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('admin.tax-forms.print', $form->id) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 mr-3">Print</a>
                                            <a href="{{ route('admin.tax-forms.edit', $form->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                            <form action="{{ route('admin.tax-forms.publish', $form->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-{{ $form->is_published ? 'red' : 'green' }}-600 hover:text-{{ $form->is_published ? 'red' : 'green' }}-900 mr-3">
                                                    {{ $form->is_published ? 'Unpublish' : 'Publish' }}
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">Tidak ada data SPT untuk tahun {{ request('year', date('Y')) }}.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $forms->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
