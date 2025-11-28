<x-employee-layout>
    <div class="py-6 md:py-8 bg-gray-50/50 dark:bg-gray-900/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- Page Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center space-x-5">
                    <div class="h-20 w-20 rounded-full bg-gradient-to-br from-[#049460] to-[#10C874] flex items-center justify-center text-white text-3xl font-bold shadow-lg ring-4 ring-white dark:ring-gray-800">
                        {{ $user->initials() }}
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">{{ $user->name }}</h1>
                        <div class="flex items-center mt-1 text-sm text-gray-500 dark:text-gray-400 space-x-3">
                            <span class="flex items-center"><i class="fa-solid fa-id-badge mr-1.5 opacity-70"></i> {{ $employee->employee_id }}</span>
                            <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-gray-600"></span>
                            <span class="flex items-center"><i class="fa-solid fa-briefcase mr-1.5 opacity-70"></i> {{ $employee->position->name ?? 'No Position' }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('employee.dashboard') }}" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 shadow-sm">
                        <i class="fa-solid fa-arrow-left mr-2"></i>
                        Kembali ke Dashboard
                    </a>
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 px-4 py-3 rounded-xl flex items-center gap-3 animate-fade-in-down">
                    <i class="fa-solid fa-circle-check text-xl"></i>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-xl flex items-center gap-3 animate-fade-in-down">
                    <i class="fa-solid fa-circle-exclamation text-xl"></i>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Organization & Hierarchy -->
                <div class="space-y-8">
                    <!-- Organization Hierarchy -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-6">Struktur Organisasi</h3>
                        <div class="relative pl-4 border-l-2 border-gray-100 dark:border-gray-700 space-y-8">
                            <!-- Instansi -->
                            <div class="relative group">
                                <span class="absolute -left-[21px] top-1 h-3 w-3 rounded-full border-2 border-white dark:border-gray-800 bg-blue-500 ring-4 ring-blue-50 dark:ring-blue-900/20 group-hover:ring-blue-100 transition-all"></span>
                                <p class="text-xs text-gray-500 mb-0.5">Perusahaan</p>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $employee->instansi->nama_instansi ?? '-' }}</p>
                            </div>
                            <!-- Division -->
                            <div class="relative group">
                                <span class="absolute -left-[21px] top-1 h-3 w-3 rounded-full border-2 border-white dark:border-gray-800 bg-indigo-500 ring-4 ring-indigo-50 dark:ring-indigo-900/20 group-hover:ring-indigo-100 transition-all"></span>
                                <p class="text-xs text-gray-500 mb-0.5">Divisi</p>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $employee->division->name ?? '-' }}</p>
                            </div>
                            <!-- Department -->
                            <div class="relative group">
                                <span class="absolute -left-[21px] top-1 h-3 w-3 rounded-full border-2 border-white dark:border-gray-800 bg-purple-500 ring-4 ring-purple-50 dark:ring-purple-900/20 group-hover:ring-purple-100 transition-all"></span>
                                <p class="text-xs text-gray-500 mb-0.5">Departemen</p>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $employee->department->name ?? '-' }}</p>
                            </div>
                            <!-- Manager -->
                            <div class="relative group">
                                <span class="absolute -left-[21px] top-1 h-3 w-3 rounded-full border-2 border-white dark:border-gray-800 bg-pink-500 ring-4 ring-pink-50 dark:ring-pink-900/20 group-hover:ring-pink-100 transition-all"></span>
                                <p class="text-xs text-gray-500 mb-1">Atasan Langsung (Manager)</p>
                                @if($employee->manager)
                                    <div class="flex items-center p-2 -ml-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                        <div class="h-8 w-8 rounded-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center text-xs font-bold text-gray-600 mr-3">
                                            {{ $employee->manager->user->initials() }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-white leading-tight">{{ $employee->manager->user->name }}</p>
                                            <p class="text-xs text-gray-500 leading-tight mt-0.5">{{ $employee->manager->position->name ?? 'Manager' }}</p>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-sm text-gray-400 italic">Tidak ada atasan langsung</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Subordinates (If any) -->
                    @if($employee->subordinates->count() > 0)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Bawahan Saya ({{ $employee->subordinates->count() }})</h3>
                        </div>
                        <div class="space-y-3 max-h-60 overflow-y-auto pr-2 custom-scrollbar">
                            @foreach($employee->subordinates as $subordinate)
                                <div class="flex items-center p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors border border-transparent hover:border-gray-100 dark:hover:border-gray-700">
                                    <div class="h-8 w-8 rounded-full bg-gradient-to-br from-emerald-100 to-teal-100 dark:from-emerald-900/30 dark:to-teal-900/30 flex items-center justify-center text-xs font-bold text-emerald-600 dark:text-emerald-400 mr-3">
                                        {{ $subordinate->user->initials() }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white leading-tight">{{ $subordinate->user->name }}</p>
                                        <p class="text-xs text-gray-500 leading-tight mt-0.5">{{ $subordinate->position->name ?? 'Staff' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Middle & Right Column: Policy & Settings -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- Effective Policy Summary -->
                    <div class="bg-gradient-to-br from-[#049460] to-[#037d51] rounded-2xl shadow-xl p-8 text-white relative overflow-hidden">
                        <!-- Decorative bg elements -->
                        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
                        <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-black/10 rounded-full blur-3xl"></div>

                        <div class="relative z-10">
                            <div class="flex items-center justify-between mb-8">
                                <div>
                                    <h2 class="text-xl font-bold tracking-tight">Kebijakan Kerja Aktif</h2>
                                    <p class="text-green-100 text-sm mt-1">Aturan absensi yang berlaku untuk Anda</p>
                                </div>
                                <div class="px-3 py-1.5 rounded-full bg-white/20 backdrop-blur-md border border-white/10 text-xs font-medium flex items-center">
                                    <span class="w-1.5 h-1.5 rounded-full mr-2 bg-white"></span>
                                    @if($employee->policy)
                                        Kebijakan Khusus
                                    @elseif($employee->division && $employee->division->policy)
                                        Kebijakan Divisi
                                    @else
                                        Kebijakan Default
                                    @endif
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="bg-white/10 rounded-xl p-5 backdrop-blur-sm border border-white/5 hover:bg-white/15 transition-colors">
                                    <div class="flex items-center mb-3">
                                        <div class="p-2 rounded-lg bg-white/20 text-white mr-3">
                                            <i class="fa-regular fa-clock"></i>
                                        </div>
                                        <span class="text-sm text-green-50 font-medium">Jam Kerja</span>
                                    </div>
                                    <p class="text-xl font-bold tracking-tight">{{ $employee->effective_policy->formatted_schedule }}</p>
                                    <p class="text-xs text-green-100 mt-1">{{ $employee->effective_policy->formatted_work_days }}</p>
                                </div>
                                <div class="bg-white/10 rounded-xl p-5 backdrop-blur-sm border border-white/5 hover:bg-white/15 transition-colors">
                                    <div class="flex items-center mb-3">
                                        <div class="p-2 rounded-lg bg-white/20 text-white mr-3">
                                            <i class="fa-solid fa-umbrella-beach"></i>
                                        </div>
                                        <span class="text-sm text-green-50 font-medium">Cuti Tahunan</span>
                                    </div>
                                    <p class="text-xl font-bold tracking-tight">{{ $employee->effective_policy->annual_leave_days }} Hari</p>
                                    <p class="text-xs text-green-100 mt-1">Jatah per tahun</p>
                                </div>
                                <div class="bg-white/10 rounded-xl p-5 backdrop-blur-sm border border-white/5 hover:bg-white/15 transition-colors">
                                    <div class="flex items-center mb-3">
                                        <div class="p-2 rounded-lg bg-white/20 text-white mr-3">
                                            <i class="fa-solid fa-stopwatch"></i>
                                        </div>
                                        <span class="text-sm text-green-50 font-medium">Toleransi</span>
                                    </div>
                                    <p class="text-xl font-bold tracking-tight">{{ $employee->effective_policy->grace_period_minutes }} Menit</p>
                                    <p class="text-xs text-green-100 mt-1">Keterlambatan</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabs for Edit Profile & Password -->
                    <div x-data="{ activeTab: 'profile' }" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="border-b border-gray-200 dark:border-gray-700">
                            <nav class="flex -mb-px">
                                <button @click="activeTab = 'profile'" 
                                    :class="{'border-blue-500 text-blue-600 dark:text-blue-400': activeTab === 'profile', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'profile'}"
                                    class="w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm transition-colors duration-200">
                                    <i class="fa-solid fa-user-pen mr-2"></i> Edit Profil
                                </button>
                                <button @click="activeTab = 'password'" 
                                    :class="{'border-blue-500 text-blue-600 dark:text-blue-400': activeTab === 'password', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'password'}"
                                    class="w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm transition-colors duration-200">
                                    <i class="fa-solid fa-lock mr-2"></i> Ganti Password
                                </button>
                            </nav>
                        </div>

                        <!-- Profile Form -->
                        <div x-show="activeTab === 'profile'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" class="p-6 md:p-8">
                            <form method="POST" action="{{ route('employee.profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                                @csrf
                                @method('PUT')

                                <!-- Profile Photo -->
                                <div class="flex items-center gap-6 mb-6">
                                    <div class="relative w-24 h-24 rounded-full overflow-hidden border-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-800">
                                        @if($user->avatar)
                                            <img id="avatar-preview" src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div id="avatar-placeholder" class="w-full h-full flex items-center justify-center text-3xl font-bold text-gray-400 bg-gray-200 dark:bg-gray-700">
                                                {{ $user->initials() }}
                                            </div>
                                            <img id="avatar-preview" src="#" alt="Preview" class="w-full h-full object-cover hidden">
                                        @endif
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Foto Profil</label>
                                        <div class="flex items-center gap-3">
                                            <label for="foto_karyawan" class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors">
                                                <i class="fa-solid fa-upload mr-2"></i> Pilih Foto
                                            </label>
                                            <input type="file" name="foto_karyawan" id="foto_karyawan" class="hidden" accept="image/*" onchange="previewImage(this)">
                                            <p class="text-xs text-gray-500 dark:text-gray-400">JPG, PNG, GIF (Max. 2MB)</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Identity -->
                                    <div class="md:col-span-2">
                                        <h4 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider border-b border-gray-200 dark:border-gray-700 pb-2 mb-4">Identitas</h4>
                                    </div>

                                    <div class="md:col-span-2">
                                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Lengkap</label>
                                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-all duration-200">
                                        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <label for="nik" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">NIK</label>
                                        <input type="text" name="nik" id="nik" value="{{ old('nik', $employee->nik) }}"
                                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-all duration-200">
                                        @error('nik') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <label for="npwp" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">NPWP</label>
                                        <input type="text" name="npwp" id="npwp" value="{{ old('npwp', $employee->npwp) }}"
                                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-all duration-200">
                                        @error('npwp') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Lahir</label>
                                        <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth', $employee->date_of_birth ? $employee->date_of_birth->format('Y-m-d') : '') }}"
                                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-all duration-200">
                                        @error('date_of_birth') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tempat Lahir</label>
                                        <input type="text" name="tempat_lahir" id="tempat_lahir" value="{{ old('tempat_lahir', $employee->tempat_lahir) }}"
                                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-all duration-200">
                                        @error('tempat_lahir') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jenis Kelamin</label>
                                        <select name="gender" id="gender" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-all duration-200">
                                            <option value="">Pilih Jenis Kelamin</option>
                                            <option value="male" {{ old('gender', $employee->gender) == 'male' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="female" {{ old('gender', $employee->gender) == 'female' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                        @error('gender') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <label for="status_perkawinan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status Perkawinan</label>
                                        <select name="status_perkawinan" id="status_perkawinan" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-all duration-200">
                                            <option value="">Pilih Status</option>
                                            <option value="lajang" {{ old('status_perkawinan', $employee->status_perkawinan) == 'lajang' ? 'selected' : '' }}>Lajang</option>
                                            <option value="menikah" {{ old('status_perkawinan', $employee->status_perkawinan) == 'menikah' ? 'selected' : '' }}>Menikah</option>
                                            <option value="cerai" {{ old('status_perkawinan', $employee->status_perkawinan) == 'cerai' ? 'selected' : '' }}>Cerai</option>
                                        </select>
                                        @error('status_perkawinan') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <label for="jumlah_tanggungan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jumlah Tanggungan</label>
                                        <input type="number" name="jumlah_tanggungan" id="jumlah_tanggungan" value="{{ old('jumlah_tanggungan', $employee->jumlah_tanggungan) }}" min="0"
                                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-all duration-200">
                                        @error('jumlah_tanggungan') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <!-- Contact -->
                                    <div class="md:col-span-2 mt-4">
                                        <h4 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider border-b border-gray-200 dark:border-gray-700 pb-2 mb-4">Kontak</h4>
                                    </div>

                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nomor HP</label>
                                        <input type="tel" name="phone" id="phone" value="{{ old('phone', $employee->phone) }}"
                                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-all duration-200">
                                        @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-all duration-200">
                                        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <div class="md:col-span-2">
                                        <label for="alamat_ktp" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Alamat KTP</label>
                                        <textarea name="alamat_ktp" id="alamat_ktp" rows="3"
                                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-all duration-200">{{ old('alamat_ktp', $employee->alamat_ktp) }}</textarea>
                                        @error('alamat_ktp') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <div class="md:col-span-2">
                                        <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Alamat Domisili (Jika berbeda)</label>
                                        <textarea name="address" id="address" rows="3"
                                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-all duration-200">{{ old('address', $employee->address) }}</textarea>
                                        @error('address') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <!-- Emergency Contact -->
                                    <div class="md:col-span-2 mt-4">
                                        <h4 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider border-b border-gray-200 dark:border-gray-700 pb-2 mb-4">Kontak Darurat</h4>
                                    </div>

                                    <div>
                                        <label for="emergency_contact_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Kontak</label>
                                        <input type="text" name="emergency_contact_name" id="emergency_contact_name" value="{{ old('emergency_contact_name', $employee->emergency_contact_name) }}"
                                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-all duration-200">
                                        @error('emergency_contact_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <label for="emergency_contact_relation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Hubungan</label>
                                        <input type="text" name="emergency_contact_relation" id="emergency_contact_relation" value="{{ old('emergency_contact_relation', $employee->emergency_contact_relation) }}"
                                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-all duration-200">
                                        @error('emergency_contact_relation') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <div class="md:col-span-2">
                                        <label for="emergency_contact_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nomor HP Darurat</label>
                                        <input type="tel" name="emergency_contact_phone" id="emergency_contact_phone" value="{{ old('emergency_contact_phone', $employee->emergency_contact_phone) }}"
                                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-all duration-200">
                                        @error('emergency_contact_phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <!-- Documents -->
                                    <div class="md:col-span-2 mt-4">
                                        <h4 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider border-b border-gray-200 dark:border-gray-700 pb-2 mb-4">Dokumen</h4>
                                    </div>

                                    <div>
                                        <label for="scan_ktp" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Scan KTP</label>
                                        @if($employee->scan_ktp)
                                            <div class="mb-2 text-sm text-green-600 dark:text-green-400">
                                                <i class="fa-solid fa-check mr-1"></i> File terupload
                                            </div>
                                        @endif
                                        <input type="file" name="scan_ktp" id="scan_ktp" accept=".pdf,.jpg,.jpeg,.png"
                                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-all duration-200">
                                        @error('scan_ktp') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <label for="scan_npwp" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Scan NPWP</label>
                                        @if($employee->scan_npwp)
                                            <div class="mb-2 text-sm text-green-600 dark:text-green-400">
                                                <i class="fa-solid fa-check mr-1"></i> File terupload
                                            </div>
                                        @endif
                                        <input type="file" name="scan_npwp" id="scan_npwp" accept=".pdf,.jpg,.jpeg,.png"
                                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-all duration-200">
                                        @error('scan_npwp') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <label for="scan_kk" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Scan KK</label>
                                        @if($employee->scan_kk)
                                            <div class="mb-2 text-sm text-green-600 dark:text-green-400">
                                                <i class="fa-solid fa-check mr-1"></i> File terupload
                                            </div>
                                        @endif
                                        <input type="file" name="scan_kk" id="scan_kk" accept=".pdf,.jpg,.jpeg,.png"
                                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-all duration-200">
                                        @error('scan_kk') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <!-- Payroll -->
                                    <div class="md:col-span-2 mt-4">
                                        <h4 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider border-b border-gray-200 dark:border-gray-700 pb-2 mb-4">Payroll</h4>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Gaji Pokok</label>
                                        <input type="text" value="Rp {{ number_format($employee->salary, 0, ',', '.') }}" disabled
                                            class="w-full px-4 py-2.5 bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-500 dark:text-gray-400 cursor-not-allowed">
                                        <p class="mt-1 text-xs text-gray-500">Hubungi HR untuk perubahan gaji.</p>
                                    </div>

                                    <div>
                                        <label for="bank_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Bank</label>
                                        <input type="text" name="bank_name" id="bank_name" value="{{ old('bank_name', $employee->bank_name) }}"
                                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-all duration-200">
                                        @error('bank_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <label for="bank_account_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nomor Rekening</label>
                                        <input type="text" name="bank_account_number" id="bank_account_number" value="{{ old('bank_account_number', $employee->bank_account_number) }}"
                                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-all duration-200">
                                        @error('bank_account_number') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <label for="bank_account_holder" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Atas Nama Rekening</label>
                                        <input type="text" name="bank_account_holder" id="bank_account_holder" value="{{ old('bank_account_holder', $employee->bank_account_holder) }}"
                                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-all duration-200">
                                        @error('bank_account_holder') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                                <div class="flex justify-end pt-4">
                                    <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                                        <i class="fa-solid fa-save mr-2"></i> Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                            <script>
                                function previewImage(input) {
                                    if (input.files && input.files[0]) {
                                        var reader = new FileReader();
                                        reader.onload = function(e) {
                                            var preview = document.getElementById('avatar-preview');
                                            var placeholder = document.getElementById('avatar-placeholder');
                                            
                                            preview.src = e.target.result;
                                            preview.classList.remove('hidden');
                                            if (placeholder) {
                                                placeholder.classList.add('hidden');
                                            }
                                        }
                                        reader.readAsDataURL(input.files[0]);
                                    }
                                }
                            </script>
                        </div>

                        <!-- Password Form -->
                        <div x-show="activeTab === 'password'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" class="p-6 md:p-8">
                            <form method="POST" action="{{ route('employee.profile.password') }}" class="space-y-6">
                                @csrf
                                @method('PUT')

                                <div>
                                    <label for="current_password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Password Saat Ini</label>
                                    <input type="password" name="current_password" id="current_password" required
                                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-all duration-200">
                                    @error('current_password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Password Baru</label>
                                    <input type="password" name="password" id="password" required
                                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-all duration-200">
                                    @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    <p class="mt-2 text-xs text-gray-500"><i class="fa-solid fa-info-circle mr-1"></i> Minimal 8 karakter</p>
                                </div>

                                <div>
                                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Konfirmasi Password Baru</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" required
                                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-all duration-200">
                                </div>

                                <div class="flex justify-end pt-4">
                                    <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                                        <i class="fa-solid fa-key mr-2"></i> Ubah Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-employee-layout>
