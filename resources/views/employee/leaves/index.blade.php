<x-employee-layout>
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Pengajuan Cuti</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Kelola pengajuan cuti Anda</p>
            </div>
            <button onclick="openLeaveModal()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold rounded-lg shadow-sm transition-all duration-200 transform hover:scale-105">
                <i class="fa-solid fa-plus mr-2"></i>
                Ajukan Cuti Baru
            </button>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 px-4 py-3 rounded-lg flex items-center">
                <i class="fa-solid fa-check-circle mr-3"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 px-4 py-3 rounded-lg flex items-center">
                <i class="fa-solid fa-exclamation-circle mr-3"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Leave Quota Card -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-sm p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Kuota Cuti Tahunan</p>
                        <h3 class="text-3xl font-bold mt-2">{{ $annualQuota }}</h3>
                        <p class="text-blue-100 text-xs mt-1">Hari per tahun</p>
                    </div>
                    <div class="bg-white/20 rounded-full p-3">
                        <i class="fa-solid fa-calendar-check text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Remaining Quota Card -->
            <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl shadow-sm p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Sisa Kuota</p>
                        <h3 class="text-3xl font-bold mt-2">{{ $remainingQuota }}</h3>
                        <p class="text-green-100 text-xs mt-1">Hari tersisa</p>
                    </div>
                    <div class="bg-white/20 rounded-full p-3">
                        <i class="fa-solid fa-hourglass-half text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Approved Leaves -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Disetujui</p>
                        <h3 class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $approvedLeaves }}</h3>
                    </div>
                    <div class="bg-green-100 dark:bg-green-900/30 rounded-full p-3">
                        <i class="fa-solid fa-circle-check text-2xl text-green-600 dark:text-green-400"></i>
                    </div>
                </div>
            </div>

            <!-- Pending Leaves -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Menunggu</p>
                        <h3 class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $pendingLeaves }}</h3>
                    </div>
                    <div class="bg-yellow-100 dark:bg-yellow-900/30 rounded-full p-3">
                        <i class="fa-solid fa-clock text-2xl text-yellow-600 dark:text-yellow-400"></i>
                    </div>
                </div>
            </div>

            <!-- Rejected Leaves -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Ditolak</p>
                        <h3 class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $rejectedLeaves }}</h3>
                    </div>
                    <div class="bg-red-100 dark:bg-red-900/30 rounded-full p-3">
                        <i class="fa-solid fa-circle-xmark text-2xl text-red-600 dark:text-red-400"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Leaves Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Riwayat Pengajuan Cuti</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jenis Cuti</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal Mulai</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal Selesai</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Durasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Alasan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Catatan Approval</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($leaves as $leave)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @php
                                            $leaveTypes = [
                                                'annual' => ['icon' => 'fa-umbrella-beach', 'color' => 'text-blue-600', 'name' => 'Cuti Tahunan'],
                                                'sick' => ['icon' => 'fa-notes-medical', 'color' => 'text-red-600', 'name' => 'Sakit'],
                                                'maternity' => ['icon' => 'fa-baby-carriage', 'color' => 'text-pink-600', 'name' => 'Melahirkan'],
                                                'emergency' => ['icon' => 'fa-circle-exclamation', 'color' => 'text-orange-600', 'name' => 'Darurat'],
                                                'other' => ['icon' => 'fa-ellipsis', 'color' => 'text-gray-600', 'name' => 'Lainnya'],
                                            ];
                                            $type = $leaveTypes[$leave->leave_type] ?? $leaveTypes['other'];
                                        @endphp
                                        <i class="fa-solid {{ $type['icon'] }} {{ $type['color'] }} mr-3"></i>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $type['name'] }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                    {{ $leave->start_date->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                    {{ $leave->end_date->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                    {{ $leave->days_count }} hari
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400 max-w-xs truncate">
                                    {{ $leave->reason }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($leave->status === 'approved')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            <i class="fa-solid fa-circle-check mr-1"></i>
                                            Disetujui
                                        </span>
                                    @elseif($leave->status === 'approved_supervisor')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            <i class="fa-solid fa-check mr-1"></i>
                                            Disetujui User
                                        </span>
                                    @elseif($leave->status === 'pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                            <i class="fa-solid fa-clock mr-1"></i>
                                            Menunggu
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                            <i class="fa-solid fa-circle-xmark mr-1"></i>
                                            Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400 max-w-xs">
                                    @if($leave->supervisor_note)
                                        <div class="mb-1">
                                            <span class="font-semibold text-xs text-blue-600 dark:text-blue-400">User:</span>
                                            <span class="italic">"{{ $leave->supervisor_note }}"</span>
                                        </div>
                                    @endif
                                    @if($leave->hrd_note)
                                        <div>
                                            <span class="font-semibold text-xs text-purple-600 dark:text-purple-400">HRD:</span>
                                            <span class="italic">"{{ $leave->hrd_note }}"</span>
                                        </div>
                                    @endif
                                    @if(!$leave->supervisor_note && !$leave->hrd_note)
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    @if($leave->status === 'pending')
                                        <form action="{{ route('employee.leaves.destroy', $leave) }}" method="POST" class="inline delete-leave-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete(this)" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                                                <i class="fa-solid fa-trash mr-1"></i>
                                                Batal
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-400 dark:text-gray-600">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fa-solid fa-inbox text-5xl text-gray-300 dark:text-gray-600 mb-4"></i>
                                        <p class="text-gray-500 dark:text-gray-400 text-lg font-medium">Belum ada pengajuan cuti</p>
                                        <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Klik tombol "Ajukan Cuti Baru" untuk membuat pengajuan</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Leave Request Modal -->
    <div id="leaveModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Ajukan Cuti Baru</h3>
                <button onclick="closeLeaveModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <i class="fa-solid fa-xmark text-2xl"></i>
                </button>
            </div>

            <form action="{{ route('employee.leaves.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf

                <!-- Leave Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Jenis Cuti <span class="text-red-500">*</span>
                    </label>
                    <select name="leave_type" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Pilih Jenis Cuti</option>
                        <option value="annual">Cuti Tahunan</option>
                        <option value="sick">Sakit</option>
                        <option value="maternity">Melahirkan</option>
                        <option value="emergency">Darurat</option>
                        <option value="other">Lainnya</option>
                    </select>
                    @error('leave_type')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Start Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Tanggal Mulai <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="start_date" id="start_date" required min="{{ date('Y-m-d') }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('start_date')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- End Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Tanggal Selesai <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="end_date" id="end_date" required min="{{ date('Y-m-d') }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('end_date')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Days Count Display -->
                <div id="daysCountDisplay" class="hidden">
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                        <p class="text-sm text-blue-800 dark:text-blue-200">
                            <i class="fa-solid fa-info-circle mr-2"></i>
                            Durasi cuti: <span id="daysCountText" class="font-semibold">0 hari kerja</span>
                        </p>
                    </div>
                </div>

                <!-- Reason -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Alasan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="reason" rows="4" required maxlength="1000" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Jelaskan alasan pengajuan cuti Anda..."></textarea>
                    @error('reason')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Attachment -->
                <div id="attachmentField">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Lampiran/Bukti <span id="attachmentRequired" class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center justify-center w-full">
                        <label for="attachment" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-400 dark:text-gray-500 mb-3"></i>
                                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                    <span class="font-semibold">Klik untuk upload</span> atau drag & drop
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">PDF, JPG, JPEG, PNG (Max. 2MB)</p>
                                <p id="fileName" class="mt-2 text-sm text-blue-600 dark:text-blue-400 font-medium hidden"></p>
                            </div>
                            <input id="attachment" name="attachment" type="file" class="hidden" accept=".pdf,.jpg,.jpeg,.png" />
                        </label>
                    </div>
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                        <i class="fa-solid fa-info-circle mr-1"></i>
                        <span id="attachmentNote">Wajib untuk cuti sakit, melahirkan, darurat, dan lainnya</span>
                    </p>
                    @error('attachment')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Quota Warning -->
                @if($remainingQuota <= 0)
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                        <p class="text-sm text-red-800 dark:text-red-200">
                            <i class="fa-solid fa-exclamation-triangle mr-2"></i>
                            Peringatan: Kuota cuti tahunan Anda sudah habis. Pengajuan cuti masih bisa dilakukan tetapi memerlukan persetujuan khusus.
                        </p>
                    </div>
                @elseif($remainingQuota <= 3)
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                        <p class="text-sm text-yellow-800 dark:text-yellow-200">
                            <i class="fa-solid fa-exclamation-circle mr-2"></i>
                            Sisa kuota cuti Anda: <span class="font-semibold">{{ $remainingQuota }} hari</span>
                        </p>
                    </div>
                @endif

                <!-- Form Actions -->
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" onclick="closeLeaveModal()" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white rounded-lg font-semibold transition-all duration-200">
                        <i class="fa-solid fa-paper-plane mr-2"></i>
                        Ajukan Cuti
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function confirmDelete(button) {
            if (confirm('Apakah Anda yakin ingin membatalkan pengajuan cuti ini?')) {
                // Find the parent form and submit it
                button.closest('form').submit();
            }
        }

        function openLeaveModal() {
            document.getElementById('leaveModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeLeaveModal() {
            document.getElementById('leaveModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            // Reset form
            document.querySelector('#leaveModal form').reset();
            document.getElementById('fileName').classList.add('hidden');
            document.getElementById('daysCountDisplay').classList.add('hidden');
        }

        // Calculate working days when dates change
        document.addEventListener('DOMContentLoaded', function() {
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');
            const daysCountDisplay = document.getElementById('daysCountDisplay');
            const daysCountText = document.getElementById('daysCountText');
            const leaveTypeSelect = document.querySelector('select[name="leave_type"]');
            const attachmentInput = document.getElementById('attachment');
            const attachmentRequired = document.getElementById('attachmentRequired');
            const attachmentNote = document.getElementById('attachmentNote');
            const fileName = document.getElementById('fileName');

            // Handle leave type change for attachment requirement
            if (leaveTypeSelect) {
                leaveTypeSelect.addEventListener('change', function() {
                    const isAnnual = this.value === 'annual';
                    
                    if (isAnnual) {
                        // For annual leave, attachment is optional
                        attachmentInput.removeAttribute('required');
                        attachmentRequired.classList.add('hidden');
                        attachmentNote.textContent = 'Opsional untuk cuti tahunan';
                    } else {
                        // For other leave types, attachment is required
                        attachmentInput.setAttribute('required', 'required');
                        attachmentRequired.classList.remove('hidden');
                        attachmentNote.textContent = 'Wajib untuk cuti ' + this.options[this.selectedIndex].text;
                    }
                });
            }

            // Show filename when file is selected
            if (attachmentInput) {
                attachmentInput.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        const file = this.files[0];
                        const fileSizeMB = (file.size / 1024 / 1024).toFixed(2);
                        fileName.textContent = `📎 ${file.name} (${fileSizeMB} MB)`;
                        fileName.classList.remove('hidden');
                    } else {
                        fileName.classList.add('hidden');
                    }
                });
            }

            function calculateWorkingDays() {
                const startDate = new Date(startDateInput.value);
                const endDate = new Date(endDateInput.value);

                if (startDateInput.value && endDateInput.value && startDate <= endDate) {
                    let workingDays = 0;
                    const currentDate = new Date(startDate);

                    while (currentDate <= endDate) {
                        const dayOfWeek = currentDate.getDay();
                        // Count weekdays (Monday = 1 to Friday = 5)
                        if (dayOfWeek !== 0 && dayOfWeek !== 6) {
                            workingDays++;
                        }
                        currentDate.setDate(currentDate.getDate() + 1);
                    }

                    daysCountText.textContent = workingDays + ' hari kerja';
                    daysCountDisplay.classList.remove('hidden');
                } else {
                    daysCountDisplay.classList.add('hidden');
                }
            }

            startDateInput.addEventListener('change', calculateWorkingDays);
            endDateInput.addEventListener('change', function() {
                // Update end date minimum to match start date
                if (startDateInput.value) {
                    endDateInput.min = startDateInput.value;
                }
                calculateWorkingDays();
            });

            // Close modal on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeLeaveModal();
                }
            });

            // Close modal when clicking outside
            document.getElementById('leaveModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeLeaveModal();
                }
            });
        });
    </script>
    @endpush
</x-employee-layout>
