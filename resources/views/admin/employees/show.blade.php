<x-admin-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex items-center space-x-5">
                    <div class="relative">
                        @if($employee->foto_karyawan)
                            <img src="{{ asset('storage/' . $employee->foto_karyawan) }}" 
                                 alt="{{ $employee->user->name }}"
                                 class="h-24 w-24 rounded-full object-cover shadow-lg ring-4 ring-white dark:ring-gray-800">
                        @else
                            <div class="h-24 w-24 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-3xl font-bold shadow-lg ring-4 ring-white dark:ring-gray-800">
                                {{ $employee->user->initials() }}
                            </div>
                        @endif
                        <span class="absolute bottom-0 right-0 block h-6 w-6 rounded-full {{ $employee->status === 'active' ? 'bg-green-400' : 'bg-gray-400' }} ring-2 ring-white"></span>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">{{ $employee->user->name }}</h1>
                        <div class="flex items-center mt-2 text-sm text-gray-500 dark:text-gray-400 space-x-3">
                            <span class="flex items-center"><i class="fa-solid fa-id-badge mr-1.5 opacity-70"></i> {{ $employee->employee_id }}</span>
                            <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-gray-600"></span>
                            <span class="flex items-center"><i class="fa-solid fa-briefcase mr-1.5 opacity-70"></i> {{ $employee->position->name ?? 'No Position' }}</span>
                        </div>
                        <div class="mt-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                {{ $employee->status_karyawan === 'tetap' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' : '' }}
                                {{ $employee->status_karyawan === 'kontrak' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' : '' }}
                                {{ $employee->status_karyawan === 'probation' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400' : '' }}
                                {{ $employee->status_karyawan === 'magang' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' : '' }}">
                                {{ ucfirst($employee->status_karyawan ?? 'probation') }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="mt-6 md:mt-0 flex space-x-3">
                    <a href="{{ route('admin.employees.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 shadow-sm transition-all duration-200">
                        <i class="fa-solid fa-arrow-left mr-2"></i>Back
                    </a>
                    <a href="{{ route('admin.employees.edit', $employee) }}"
                       class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 shadow-sm transition-all duration-200">
                        <i class="fa-solid fa-edit mr-2"></i>Edit Profile
                    </a>
                </div>
            </div>

            <!-- Tabs -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="flex -mb-px overflow-x-auto" aria-label="Tabs">
                        <button onclick="switchTab('overview')" id="tab-overview" class="tab-button active whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                            <i class="fa-solid fa-chart-pie mr-2"></i>Overview
                        </button>
                        <button onclick="switchTab('personal')" id="tab-personal" class="tab-button whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                            <i class="fa-solid fa-user mr-2"></i>Personal Info
                        </button>
                        <button onclick="switchTab('employment')" id="tab-employment" class="tab-button whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                            <i class="fa-solid fa-building mr-2"></i>Employment
                        </button>
                        <button onclick="switchTab('payroll')" id="tab-payroll" class="tab-button whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                            <i class="fa-solid fa-money-bill-wave mr-2"></i>Payroll & Tax
                        </button>
                        <button onclick="switchTab('documents')" id="tab-documents" class="tab-button whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                            <i class="fa-solid fa-file-alt mr-2"></i>Documents
                        </button>
                        <button onclick="switchTab('policy')" id="tab-policy" class="tab-button whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                            <i class="fa-solid fa-clipboard-list mr-2"></i>Policy
                        </button>
                    </nav>
                </div>

                <!-- Tab Contents -->
                <div class="p-6">
                    <!-- Overview Tab -->
                    <div id="content-overview" class="tab-content">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <!-- Quick Stats -->
                            <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl p-6 border border-blue-200 dark:border-blue-800">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="p-3 bg-blue-500 rounded-lg">
                                            <i class="fa-solid fa-calendar-days text-white text-xl"></i>
                                        </div>
                                        <span class="text-xs font-medium text-blue-700 dark:text-blue-400">Masa Kerja</span>
                                    </div>
                                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                        @if($employee->hire_date)
                                            {{ $employee->hire_date->diffForHumans(null, true) }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                        Sejak {{ $employee->hire_date ? $employee->hire_date->format('d M Y') : '-' }}
                                    </p>
                                </div>

                                <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl p-6 border border-green-200 dark:border-green-800">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="p-3 bg-green-500 rounded-lg">
                                            <i class="fa-solid fa-money-bill-wave text-white text-xl"></i>
                                        </div>
                                        <span class="text-xs font-medium text-green-700 dark:text-green-400">Gaji Pokok</span>
                                    </div>
                                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                        Rp {{ number_format($employee->salary, 0, ',', '.') }}
                                    </p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Per bulan</p>
                                </div>

                                <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-xl p-6 border border-purple-200 dark:border-purple-800">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="p-3 bg-purple-500 rounded-lg">
                                            <i class="fa-solid fa-gift text-white text-xl"></i>
                                        </div>
                                        <span class="text-xs font-medium text-purple-700 dark:text-purple-400">Total Tunjangan</span>
                                    </div>
                                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                        Rp {{ number_format(($employee->tunjangan_jabatan ?? 0) + ($employee->tunjangan_transport ?? 0) + ($employee->tunjangan_makan ?? 0) + ($employee->tunjangan_hadir ?? 0), 0, ',', '.') }}
                                    </p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Per bulan</p>
                                </div>

                                <div class="bg-gradient-to-br from-amber-50 to-amber-100 dark:from-amber-900/20 dark:to-amber-800/20 rounded-xl p-6 border border-amber-200 dark:border-amber-800">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="p-3 bg-amber-500 rounded-lg">
                                            <i class="fa-solid fa-shield-halved text-white text-xl"></i>
                                        </div>
                                        <span class="text-xs font-medium text-amber-700 dark:text-amber-400">Status BPJS</span>
                                    </div>
                                    <p class="text-lg font-bold text-gray-900 dark:text-white">
                                        {{ $employee->bpjs_kesehatan_number ? 'Terdaftar' : 'Belum Terdaftar' }}
                                    </p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                        {{ $employee->bpjs_kesehatan_number ? 'Kesehatan & TK' : 'Perlu didaftarkan' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Organization Hierarchy -->
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-6">
                                <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-4">Struktur Organisasi</h3>
                                <div class="space-y-4">
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Instansi</p>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $employee->instansi->nama_instansi ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Cabang</p>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $employee->branch->name ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Divisi</p>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $employee->division->name ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Departemen</p>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $employee->department->name ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Jabatan</p>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $employee->position->name ?? '-' }}</p>
                                    </div>
                                    @if($employee->manager)
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Manager</p>
                                        <div class="flex items-center mt-2">
                                            <div class="h-8 w-8 rounded-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center text-xs font-bold text-gray-600 mr-2">
                                                {{ $employee->manager->user->initials() }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $employee->manager->user->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $employee->manager->position->name ?? 'Manager' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Info Tab -->
                    <div id="content-personal" class="tab-content hidden">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                                        <i class="fa-solid fa-id-card text-blue-500 mr-2"></i>
                                        Identitas
                                    </h3>
                                    <div class="space-y-4">
                                        <div class="flex justify-between items-start py-3 border-b border-gray-100 dark:border-gray-700">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">NIK (KTP)</span>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white text-right">{{ $employee->nik ?? '-' }}</span>
                                        </div>
                                        <div class="flex justify-between items-start py-3 border-b border-gray-100 dark:border-gray-700">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">NPWP</span>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white text-right">{{ $employee->npwp ?? '-' }}</span>
                                        </div>
                                        <div class="flex justify-between items-start py-3 border-b border-gray-100 dark:border-gray-700">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Tempat, Tanggal Lahir</span>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white text-right">
                                                {{ $employee->tempat_lahir ?? '-' }}, {{ $employee->date_of_birth ? $employee->date_of_birth->format('d M Y') : '-' }}
                                            </span>
                                        </div>
                                        <div class="flex justify-between items-start py-3 border-b border-gray-100 dark:border-gray-700">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Jenis Kelamin</span>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white text-right">
                                                {{ $employee->gender === 'male' ? 'Laki-laki' : ($employee->gender === 'female' ? 'Perempuan' : '-') }}
                                            </span>
                                        </div>
                                        <div class="flex justify-between items-start py-3 border-b border-gray-100 dark:border-gray-700">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Status Perkawinan</span>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white text-right">{{ ucfirst($employee->status_perkawinan ?? '-') }}</span>
                                        </div>
                                        <div class="flex justify-between items-start py-3 border-b border-gray-100 dark:border-gray-700">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Jumlah Tanggungan</span>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white text-right">{{ $employee->jumlah_tanggungan ?? 0 }} orang</span>
                                        </div>
                                        <div class="flex justify-between items-start py-3 border-b border-gray-100 dark:border-gray-700">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Kewarganegaraan</span>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white text-right">{{ $employee->kewarganegaraan ?? 'WNI' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                                        <i class="fa-solid fa-location-dot text-red-500 mr-2"></i>
                                        Alamat
                                    </h3>
                                    <div class="space-y-4">
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Alamat KTP</p>
                                            <p class="text-sm text-gray-900 dark:text-white">{{ $employee->alamat_ktp ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Alamat Domisili</p>
                                            <p class="text-sm text-gray-900 dark:text-white">{{ $employee->address ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                                        <i class="fa-solid fa-phone text-green-500 mr-2"></i>
                                        Kontak
                                    </h3>
                                    <div class="space-y-4">
                                        <div class="flex justify-between items-start py-3 border-b border-gray-100 dark:border-gray-700">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Email</span>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white text-right break-all">{{ $employee->user->email }}</span>
                                        </div>
                                        <div class="flex justify-between items-start py-3 border-b border-gray-100 dark:border-gray-700">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">No. HP</span>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white text-right">{{ $employee->phone ?? '-' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                                        <i class="fa-solid fa-user-shield text-orange-500 mr-2"></i>
                                        Kontak Darurat
                                    </h3>
                                    <div class="space-y-4">
                                        <div class="flex justify-between items-start py-3 border-b border-gray-100 dark:border-gray-700">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Nama</span>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white text-right">{{ $employee->emergency_contact_name ?? '-' }}</span>
                                        </div>
                                        <div class="flex justify-between items-start py-3 border-b border-gray-100 dark:border-gray-700">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Hubungan</span>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white text-right">{{ $employee->emergency_contact_relation ?? '-' }}</span>
                                        </div>
                                        <div class="flex justify-between items-start py-3 border-b border-gray-100 dark:border-gray-700">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">No. HP</span>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white text-right">{{ $employee->emergency_contact_phone ?? '-' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                                        <i class="fa-solid fa-building-columns text-purple-500 mr-2"></i>
                                        Rekening Bank
                                    </h3>
                                    <div class="space-y-4">
                                        <div class="flex justify-between items-start py-3 border-b border-gray-100 dark:border-gray-700">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Nama Bank</span>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white text-right">{{ $employee->bank_name ?? '-' }}</span>
                                        </div>
                                        <div class="flex justify-between items-start py-3 border-b border-gray-100 dark:border-gray-700">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">No. Rekening</span>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white text-right">{{ $employee->bank_account_number ?? '-' }}</span>
                                        </div>
                                        <div class="flex justify-between items-start py-3 border-b border-gray-100 dark:border-gray-700">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Atas Nama</span>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white text-right">{{ $employee->bank_account_holder ?? '-' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Employment Tab -->
                    <div id="content-employment" class="tab-content hidden">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                                        <i class="fa-solid fa-briefcase text-blue-500 mr-2"></i>
                                        Status Kepegawaian
                                    </h3>
                                    <div class="space-y-4">
                                        <div class="flex justify-between items-start py-3 border-b border-gray-100 dark:border-gray-700">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Employee ID</span>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white text-right">{{ $employee->employee_id }}</span>
                                        </div>
                                        <div class="flex justify-between items-start py-3 border-b border-gray-100 dark:border-gray-700">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Status Karyawan</span>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white text-right">{{ ucfirst($employee->status_karyawan ?? 'probation') }}</span>
                                        </div>
                                        <div class="flex justify-between items-start py-3 border-b border-gray-100 dark:border-gray-700">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Grade/Level</span>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white text-right">{{ $employee->grade_level ?? '-' }}</span>
                                        </div>
                                        <div class="flex justify-between items-start py-3 border-b border-gray-100 dark:border-gray-700">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Tanggal Mulai Kerja</span>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white text-right">
                                                {{ $employee->hire_date ? $employee->hire_date->format('d M Y') : '-' }}
                                            </span>
                                        </div>
                                        @if($employee->tanggal_berhenti)
                                        <div class="flex justify-between items-start py-3 border-b border-gray-100 dark:border-gray-700">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Tanggal Berhenti</span>
                                            <span class="text-sm font-medium text-red-600 dark:text-red-400 text-right">
                                                {{ $employee->tanggal_berhenti->format('d M Y') }}
                                            </span>
                                        </div>
                                        @endif
                                        <div class="flex justify-between items-start py-3 border-b border-gray-100 dark:border-gray-700">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Status Aktif</span>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ $employee->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
                                                {{ ucfirst($employee->status) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                                        <i class="fa-solid fa-sitemap text-purple-500 mr-2"></i>
                                        Struktur Organisasi
                                    </h3>
                                    <div class="space-y-4">
                                        <div class="flex justify-between items-start py-3 border-b border-gray-100 dark:border-gray-700">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Instansi</span>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white text-right">{{ $employee->instansi->nama_instansi ?? '-' }}</span>
                                        </div>
                                        <div class="flex justify-between items-start py-3 border-b border-gray-100 dark:border-gray-700">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Cabang</span>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white text-right">{{ $employee->branch->name ?? '-' }}</span>
                                        </div>
                                        <div class="flex justify-between items-start py-3 border-b border-gray-100 dark:border-gray-700">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Divisi</span>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white text-right">{{ $employee->division->name ?? '-' }}</span>
                                        </div>
                                        <div class="flex justify-between items-start py-3 border-b border-gray-100 dark:border-gray-700">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Departemen</span>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white text-right">{{ $employee->department->name ?? '-' }}</span>
                                        </div>
                                        <div class="flex justify-between items-start py-3 border-b border-gray-100 dark:border-gray-700">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Jabatan</span>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white text-right">{{ $employee->position->name ?? '-' }}</span>
                                        </div>
                                        <div class="flex justify-between items-start py-3 border-b border-gray-100 dark:border-gray-700">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Role</span>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white text-right">{{ $employee->instansiRole->name ?? '-' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payroll & Tax Tab -->
                    <div id="content-payroll" class="tab-content hidden">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                                        <i class="fa-solid fa-money-bill-wave text-green-500 mr-2"></i>
                                        Komponen Gaji
                                    </h3>
                                    <div class="space-y-4">
                                        <div class="flex justify-between items-start py-3 border-b border-gray-100 dark:border-gray-700">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Gaji Pokok</span>
                                            <span class="text-sm font-bold text-gray-900 dark:text-white text-right">Rp {{ number_format($employee->salary, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex justify-between items-start py-3 border-b border-gray-100 dark:border-gray-700">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Tunjangan Jabatan</span>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white text-right">Rp {{ number_format($employee->tunjangan_jabatan ?? 0, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex justify-between items-start py-3 border-b border-gray-100 dark:border-gray-700">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Tunjangan Transport</span>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white text-right">Rp {{ number_format($employee->tunjangan_transport ?? 0, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex justify-between items-start py-3 border-b border-gray-100 dark:border-gray-700">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Tunjangan Makan</span>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white text-right">Rp {{ number_format($employee->tunjangan_makan ?? 0, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex justify-between items-start py-3 border-b border-gray-100 dark:border-gray-700">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Tunjangan Hadir</span>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white text-right">Rp {{ number_format($employee->tunjangan_hadir ?? 0, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex justify-between items-start py-3 border-t-2 border-gray-300 dark:border-gray-600 pt-3">
                                            <span class="text-sm font-bold text-gray-900 dark:text-white">Total Penghasilan Tetap</span>
                                            <span class="text-sm font-bold text-green-600 dark:text-green-400 text-right">
                                                Rp {{ number_format($employee->salary + ($employee->tunjangan_jabatan ?? 0) + ($employee->tunjangan_transport ?? 0) + ($employee->tunjangan_makan ?? 0) + ($employee->tunjangan_hadir ?? 0), 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                                        <i class="fa-solid fa-file-invoice text-blue-500 mr-2"></i>
                                        Data Perpajakan
                                    </h3>
                                    <div class="space-y-4">
                                        <div class="flex justify-between items-start py-3 border-b border-gray-100 dark:border-gray-700">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">NPWP</span>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white text-right">{{ $employee->npwp ?? '-' }}</span>
                                        </div>
                                        <div class="flex justify-between items-start py-3 border-b border-gray-100 dark:border-gray-700">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Status PTKP</span>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white text-right">{{ $employee->ptkp_status ?? 'TK/0' }}</span>
                                        </div>
                                        <div class="flex justify-between items-start py-3 border-b border-gray-100 dark:border-gray-700">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Metode Pajak</span>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white text-right">{{ ucfirst($employee->metode_pajak ?? 'gross') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                                        <i class="fa-solid fa-shield-halved text-red-500 mr-2"></i>
                                        BPJS Kesehatan
                                    </h3>
                                    <div class="space-y-4">
                                        <div class="flex justify-between items-start py-3 border-b border-gray-100 dark:border-gray-700">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">No. BPJS Kesehatan</span>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white text-right">{{ $employee->bpjs_kesehatan_number ?? '-' }}</span>
                                        </div>
                                        <div class="flex justify-between items-start py-3 border-b border-gray-100 dark:border-gray-700">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Faskes 1</span>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white text-right">{{ $employee->bpjs_kesehatan_faskes ?? '-' }}</span>
                                        </div>
                                        <div class="flex justify-between items-start py-3 border-b border-gray-100 dark:border-gray-700">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Tanggal Mulai</span>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white text-right">
                                                {{ $employee->bpjs_kesehatan_start_date ? $employee->bpjs_kesehatan_start_date->format('d M Y') : '-' }}
                                            </span>
                                        </div>
                                        <div class="flex justify-between items-start py-3 border-b border-gray-100 dark:border-gray-700">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Jumlah Tanggungan</span>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white text-right">{{ $employee->bpjs_kesehatan_tanggungan ?? 0 }} orang</span>
                                        </div>
                                        @if($employee->bpjs_kesehatan_card)
                                        <div class="py-3">
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Kartu BPJS Kesehatan</p>
                                            <a href="{{ asset('storage/' . $employee->bpjs_kesehatan_card) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 rounded-lg text-sm hover:bg-blue-100 dark:hover:bg-blue-900/30">
                                                <i class="fa-solid fa-file-image mr-2"></i>Lihat Kartu
                                            </a>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div>
                                    <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                                        <i class="fa-solid fa-hard-hat text-orange-500 mr-2"></i>
                                        BPJS Ketenagakerjaan
                                    </h3>
                                    <div class="space-y-4">
                                        <div class="flex justify-between items-start py-3 border-b border-gray-100 dark:border-gray-700">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">No. BPJS TK</span>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white text-right">{{ $employee->bpjs_tk_number ?? '-' }}</span>
                                        </div>
                                        <div class="flex justify-between items-start py-3 border-b border-gray-100 dark:border-gray-700">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Status JP</span>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ $employee->bpjs_jp_aktif ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                                {{ $employee->bpjs_jp_aktif ? 'Aktif' : 'Tidak Aktif' }}
                                            </span>
                                        </div>
                                        <div class="flex justify-between items-start py-3 border-b border-gray-100 dark:border-gray-700">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Rate JKK</span>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white text-right">{{ $employee->bpjs_jkk_rate ?? 0.24 }}%</span>
                                        </div>
                                        <div class="flex justify-between items-start py-3 border-b border-gray-100 dark:border-gray-700">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Tanggal Mulai</span>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white text-right">
                                                {{ $employee->bpjs_tk_start_date ? $employee->bpjs_tk_start_date->format('d M Y') : '-' }}
                                            </span>
                                        </div>
                                        @if($employee->bpjs_tk_card)
                                        <div class="py-3">
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Kartu BPJS TK</p>
                                            <a href="{{ asset('storage/' . $employee->bpjs_tk_card) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-orange-50 dark:bg-orange-900/20 text-orange-700 dark:text-orange-400 rounded-lg text-sm hover:bg-orange-100 dark:hover:bg-orange-900/30">
                                                <i class="fa-solid fa-file-image mr-2"></i>Lihat Kartu
                                            </a>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Documents Tab -->
                    <div id="content-documents" class="tab-content hidden">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Foto Karyawan -->
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Foto Karyawan</h4>
                                    @if($employee->foto_karyawan)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                            <i class="fa-solid fa-check mr-1"></i>Tersedia
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                            <i class="fa-solid fa-times mr-1"></i>Belum Upload
                                        </span>
                                    @endif
                                </div>
                                @if($employee->foto_karyawan)
                                    <img src="{{ asset('storage/' . $employee->foto_karyawan) }}" alt="Foto" class="w-full h-48 object-cover rounded-lg mb-3">
                                    <a href="{{ asset('storage/' . $employee->foto_karyawan) }}" target="_blank" class="block w-full text-center px-3 py-2 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 rounded-lg text-sm hover:bg-blue-100 dark:hover:bg-blue-900/30">
                                        <i class="fa-solid fa-eye mr-2"></i>Lihat
                                    </a>
                                @else
                                    <div class="w-full h-48 bg-gray-200 dark:bg-gray-600 rounded-lg flex items-center justify-center mb-3">
                                        <i class="fa-solid fa-image text-gray-400 text-4xl"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Scan KTP -->
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Scan KTP</h4>
                                    @if($employee->scan_ktp)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                            <i class="fa-solid fa-check mr-1"></i>Tersedia
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                            <i class="fa-solid fa-times mr-1"></i>Belum Upload
                                        </span>
                                    @endif
                                </div>
                                @if($employee->scan_ktp)
                                    <div class="w-full h-48 bg-gray-200 dark:bg-gray-600 rounded-lg flex items-center justify-center mb-3">
                                        <i class="fa-solid fa-id-card text-gray-400 text-4xl"></i>
                                    </div>
                                    <a href="{{ asset('storage/' . $employee->scan_ktp) }}" target="_blank" class="block w-full text-center px-3 py-2 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 rounded-lg text-sm hover:bg-blue-100 dark:hover:bg-blue-900/30">
                                        <i class="fa-solid fa-download mr-2"></i>Download
                                    </a>
                                @else
                                    <div class="w-full h-48 bg-gray-200 dark:bg-gray-600 rounded-lg flex items-center justify-center mb-3">
                                        <i class="fa-solid fa-id-card text-gray-400 text-4xl"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Scan NPWP -->
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Scan NPWP</h4>
                                    @if($employee->scan_npwp)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                            <i class="fa-solid fa-check mr-1"></i>Tersedia
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                            <i class="fa-solid fa-times mr-1"></i>Belum Upload
                                        </span>
                                    @endif
                                </div>
                                @if($employee->scan_npwp)
                                    <div class="w-full h-48 bg-gray-200 dark:bg-gray-600 rounded-lg flex items-center justify-center mb-3">
                                        <i class="fa-solid fa-file-invoice text-gray-400 text-4xl"></i>
                                    </div>
                                    <a href="{{ asset('storage/' . $employee->scan_npwp) }}" target="_blank" class="block w-full text-center px-3 py-2 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 rounded-lg text-sm hover:bg-blue-100 dark:hover:bg-blue-900/30">
                                        <i class="fa-solid fa-download mr-2"></i>Download
                                    </a>
                                @else
                                    <div class="w-full h-48 bg-gray-200 dark:bg-gray-600 rounded-lg flex items-center justify-center mb-3">
                                        <i class="fa-solid fa-file-invoice text-gray-400 text-4xl"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Scan KK -->
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Scan Kartu Keluarga</h4>
                                    @if($employee->scan_kk)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                            <i class="fa-solid fa-check mr-1"></i>Tersedia
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                            <i class="fa-solid fa-times mr-1"></i>Belum Upload
                                        </span>
                                    @endif
                                </div>
                                @if($employee->scan_kk)
                                    <div class="w-full h-48 bg-gray-200 dark:bg-gray-600 rounded-lg flex items-center justify-center mb-3">
                                        <i class="fa-solid fa-users text-gray-400 text-4xl"></i>
                                    </div>
                                    <a href="{{ asset('storage/' . $employee->scan_kk) }}" target="_blank" class="block w-full text-center px-3 py-2 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 rounded-lg text-sm hover:bg-blue-100 dark:hover:bg-blue-900/30">
                                        <i class="fa-solid fa-download mr-2"></i>Download
                                    </a>
                                @else
                                    <div class="w-full h-48 bg-gray-200 dark:bg-gray-600 rounded-lg flex items-center justify-center mb-3">
                                        <i class="fa-solid fa-users text-gray-400 text-4xl"></i>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if($employee->catatan_hr)
                        <div class="mt-6 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl p-6">
                            <h4 class="text-sm font-semibold text-yellow-900 dark:text-yellow-200 mb-2 flex items-center">
                                <i class="fa-solid fa-sticky-note mr-2"></i>Catatan HR
                            </h4>
                            <p class="text-sm text-yellow-800 dark:text-yellow-300">{{ $employee->catatan_hr }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- Policy Tab -->
                    <div id="content-policy" class="tab-content hidden">
                        @include('admin.employees.partials.policy-section', ['employee' => $employee])
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function switchTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Remove active class from all buttons
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active', 'border-blue-500', 'text-blue-600', 'dark:text-blue-400');
                button.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'dark:text-gray-400', 'dark:hover:text-gray-300');
            });
            
            // Show selected tab content
            document.getElementById('content-' + tabName).classList.remove('hidden');
            
            // Add active class to selected button
            const activeButton = document.getElementById('tab-' + tabName);
            activeButton.classList.add('active', 'border-blue-500', 'text-blue-600', 'dark:text-blue-400');
            activeButton.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'dark:text-gray-400', 'dark:hover:text-gray-300');
        }

        // Initialize first tab
        document.addEventListener('DOMContentLoaded', function() {
            switchTab('overview');
        });
    </script>

    <style>
        .tab-button {
            transition: all 0.2s ease;
        }
        .tab-button.active {
            border-bottom-color: #3B82F6;
            color: #3B82F6;
        }
    </style>
    @endpush
</x-admin-layout>