<x-admin-layout>
    <div class="py-6">
        <x-page-header
            title="Kebijakan Absensi"
            subtitle="Atur kebijakan absensi default untuk seluruh karyawan"
        />

        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-check-circle text-green-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <form action="{{ $policy ? route('admin.attendance-policy.update') : route('admin.attendance-policy.store') }}" method="POST">
                @csrf
                @if($policy)
                    @method('PUT')
                @endif

                <div class="p-6 space-y-6">
                    <!-- Work Days -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Hari Kerja <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            @php
                                $days = [
                                    'monday' => 'Senin',
                                    'tuesday' => 'Selasa',
                                    'wednesday' => 'Rabu',
                                    'thursday' => 'Kamis',
                                    'friday' => 'Jumat',
                                    'saturday' => 'Sabtu',
                                    'sunday' => 'Minggu',
                                ];
                                $selectedDays = old('work_days', $policy->work_days ?? ['monday', 'tuesday', 'wednesday', 'thursday', 'friday']);
                            @endphp
                            @foreach($days as $value => $label)
                                <label class="flex items-center p-3 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 {{ in_array($value, $selectedDays) ? 'bg-blue-50 dark:bg-blue-900/20 border-blue-500' : '' }}">
                                    <input type="checkbox" name="work_days[]" value="{{ $value }}" 
                                           {{ in_array($value, $selectedDays) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('work_days')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Work Hours -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="start_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Jam Masuk <span class="text-red-500">*</span>
                            </label>
                            <input type="time" name="start_time" id="start_time" 
                                   value="{{ old('start_time', $policy ? $policy->start_time->format('H:i') : '08:00') }}"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-blue-500 focus:border-blue-500"
                                   required>
                            @error('start_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="end_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Jam Pulang <span class="text-red-500">*</span>
                            </label>
                            <input type="time" name="end_time" id="end_time" 
                                   value="{{ old('end_time', $policy ? $policy->end_time->format('H:i') : '17:00') }}"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-blue-500 focus:border-blue-500"
                                   required>
                            @error('end_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Tolerances -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="late_tolerance" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Toleransi Terlambat (menit)
                            </label>
                            <input type="number" name="late_tolerance" id="late_tolerance" 
                                   value="{{ old('late_tolerance', $policy->late_tolerance ?? 15) }}"
                                   min="0" max="120"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-blue-500 focus:border-blue-500">
                            @error('late_tolerance')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="break_duration" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Durasi Istirahat (menit)
                            </label>
                            <input type="number" name="break_duration" id="break_duration" 
                                   value="{{ old('break_duration', $policy->break_duration ?? 60) }}"
                                   min="0" max="480"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-blue-500 focus:border-blue-500">
                            @error('break_duration')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="overtime_after_minutes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Lembur Setelah (menit)
                            </label>
                            <input type="number" name="overtime_after_minutes" id="overtime_after_minutes" 
                                   value="{{ old('overtime_after_minutes', $policy->overtime_after_minutes ?? 0) }}"
                                   min="0"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-blue-500 focus:border-blue-500">
                            @error('overtime_after_minutes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Auto Checkout -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="auto_checkout" id="auto_checkout" value="1"
                                       {{ old('auto_checkout', $policy->auto_checkout ?? false) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                       onchange="document.getElementById('auto_checkout_time_container').classList.toggle('hidden', !this.checked)">
                            </div>
                            <div class="ml-3">
                                <label for="auto_checkout" class="font-medium text-gray-700 dark:text-gray-300">
                                    Auto Check Out
                                </label>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Otomatis check out karyawan pada waktu tertentu jika belum check out
                                </p>
                            </div>
                        </div>

                        <div id="auto_checkout_time_container" class="{{ old('auto_checkout', $policy->auto_checkout ?? false) ? '' : 'hidden' }} mt-4 ml-8">
                            <label for="auto_checkout_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Waktu Auto Check Out
                            </label>
                            <input type="time" name="auto_checkout_time" id="auto_checkout_time" 
                                   value="{{ old('auto_checkout_time', $policy && $policy->auto_checkout_time ? $policy->auto_checkout_time->format('H:i') : '18:00') }}"
                                   class="w-full md:w-64 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-3">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center gap-2">
                        <i class="fa-solid fa-save"></i>
                        <span>{{ $policy ? 'Perbarui' : 'Simpan' }} Kebijakan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
