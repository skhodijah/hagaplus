<x-admin-layout>
    <div class="py-2">
        <x-page-header
            title="Edit Employee"
            subtitle="Update employee information for {{ $employee->user->name }}"
        />

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <form method="POST" action="{{ route('admin.employees.update', $employee) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Account & Personal Information -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 border-b pb-2">Account & Personal Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Full Name *</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $employee->user->name) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('name') border-red-500 @enderror"
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Address *</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $employee->user->email) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('email') border-red-500 @enderror"
                                   required>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="nik" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">NIK (KTP)</label>
                            <input type="text" id="nik" name="nik" value="{{ old('nik', $employee->nik) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('nik') border-red-500 @enderror">
                            @error('nik')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="npwp" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">NPWP</label>
                            <input type="text" id="npwp" name="npwp" value="{{ old('npwp', $employee->npwp) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('npwp') border-red-500 @enderror">
                            @error('npwp')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Place of Birth</label>
                            <input type="text" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir', $employee->tempat_lahir) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('tempat_lahir') border-red-500 @enderror">
                            @error('tempat_lahir')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="date_of_birth" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date of Birth</label>
                            <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $employee->date_of_birth ? $employee->date_of_birth->format('Y-m-d') : '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('date_of_birth') border-red-500 @enderror">
                            @error('date_of_birth')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gender</label>
                            <select id="gender" name="gender" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender', $employee->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $employee->gender) == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>

                        <div>
                            <label for="status_perkawinan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Marital Status</label>
                            <select id="status_perkawinan" name="status_perkawinan" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">Select Status</option>
                                <option value="lajang" {{ old('status_perkawinan', $employee->status_perkawinan) == 'lajang' ? 'selected' : '' }}>Lajang</option>
                                <option value="menikah" {{ old('status_perkawinan', $employee->status_perkawinan) == 'menikah' ? 'selected' : '' }}>Menikah</option>
                                <option value="cerai" {{ old('status_perkawinan', $employee->status_perkawinan) == 'cerai' ? 'selected' : '' }}>Cerai</option>
                            </select>
                        </div>

                        <div>
                            <label for="jumlah_tanggungan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Number of Dependents</label>
                            <input type="number" id="jumlah_tanggungan" name="jumlah_tanggungan" value="{{ old('jumlah_tanggungan', $employee->jumlah_tanggungan) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   min="0">
                        </div>

                         <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone Number</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone', $employee->phone) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('phone') border-red-500 @enderror">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="alamat_ktp" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">KTP Address</label>
                            <textarea id="alamat_ktp" name="alamat_ktp" rows="2"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">{{ old('alamat_ktp', $employee->alamat_ktp) }}</textarea>
                        </div>

                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Domicile Address (if different)</label>
                            <textarea id="address" name="address" rows="2"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">{{ old('address', $employee->address) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Emergency Contact -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 border-b pb-2">Emergency Contact</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="emergency_contact_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Contact Name</label>
                            <input type="text" id="emergency_contact_name" name="emergency_contact_name" value="{{ old('emergency_contact_name', $employee->emergency_contact_name) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label for="emergency_contact_relation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Relation</label>
                            <input type="text" id="emergency_contact_relation" name="emergency_contact_relation" value="{{ old('emergency_contact_relation', $employee->emergency_contact_relation) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label for="emergency_contact_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone Number</label>
                            <input type="text" id="emergency_contact_phone" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $employee->emergency_contact_phone) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>
                </div>

                <!-- Employment Information -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 border-b pb-2">Employment Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="division_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Division</label>
                            <select id="division_id" name="division_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">Select Division</option>
                                @foreach($divisions as $division)
                                    <option value="{{ $division->id }}" {{ old('division_id', $employee->division_id) == $division->id ? 'selected' : '' }} data-code="{{ $division->code }}">
                                        {{ $division->name }} ({{ $division->code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="department_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Department *</label>
                            <select id="department_id" name="department_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" required>
                                <option value="">Select Department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" data-division-id="{{ $department->division_id }}" {{ old('department_id', $employee->department_id) == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="position_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Position *</label>
                            <select id="position_id" name="position_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" required>
                                <option value="">Select Position</option>
                                @foreach($positions as $position)
                                    <option value="{{ $position->id }}" data-department-id="{{ $position->department_id }}" {{ old('position_id', $employee->position_id) == $position->id ? 'selected' : '' }}>
                                        {{ $position->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="branch_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Branch</label>
                            <select id="branch_id" name="branch_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">Select Branch</option>
                                @foreach(\App\Models\Admin\Branch::where('company_id', Auth::user()->instansi_id)->get() as $branch)
                                    <option value="{{ $branch->id }}" {{ old('branch_id', $employee->branch_id) == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <h4 class="text-md font-semibold text-gray-800 dark:text-gray-200 mb-3 border-l-4 border-blue-500 pl-3">
                                Approval Hierarchy
                            </h4>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mb-4">
                                Tentukan hierarki approval untuk karyawan ini. Approval flows: <strong>Supervisor → Manager → HR/Finance</strong>
                            </p>
                        </div>

                        <div>
                            <label for="supervisor_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Direct Supervisor <span class="text-blue-500">(Level 1 Approval)</span>
                            </label>
                            <select id="supervisor_id" name="supervisor_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">Select Supervisor (Optional)</option>
                                @foreach($potentialSupervisors as $supervisor)
                                    <option value="{{ $supervisor->id }}" 
                                        data-department-id="{{ $supervisor->department_id }}"
                                        {{ old('supervisor_id', $employee->supervisor_id) == $supervisor->id ? 'selected' : '' }}>
                                        {{ $supervisor->user->name }} 
                                        - {{ $supervisor->division ? $supervisor->division->name : 'No Div' }}
                                        - {{ $supervisor->department ? $supervisor->department->name : 'No Dept' }}
                                        - {{ $supervisor->instansiRole ? $supervisor->instansiRole->name : 'No Role' }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Supervisor langsung (atasan tim) - akan menerima approval pertama
                            </p>
                        </div>

                        <div>
                            <label for="manager_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Department/Division Manager <span class="text-purple-500">(Level 2 Approval)</span>
                            </label>
                            <select id="manager_id" name="manager_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">Select Manager (Optional)</option>
                                @foreach($potentialManagers as $manager)
                                    <option value="{{ $manager->id }}" 
                                        data-department-id="{{ $manager->department_id }}"
                                        {{ old('manager_id', $employee->manager_id) == $manager->id ? 'selected' : '' }}>
                                        {{ $manager->user->name }} 
                                        - {{ $manager->division ? $manager->division->name : 'No Div' }}
                                        - {{ $manager->department ? $manager->department->name : 'No Dept' }}
                                        - {{ $manager->instansiRole ? $manager->instansiRole->name : 'No Role' }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Manager departemen/divisi - akan menerima approval kedua (setelah supervisor)
                            </p>
                        </div>

                        <div class="md:col-span-2 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                            <div class="flex items-start">
                                <i class="fa-solid fa-circle-info text-blue-500 mt-1 mr-3"></i>
                                <div class="text-sm text-gray-700 dark:text-gray-300">
                                    <p class="font-semibold mb-2">Approval Flow berdasarkan jenis request:</p>
                                    <ul class="space-y-1 ml-4">
                                        <li>• <strong>Izin/Cuti/Sakit/Edit Absen:</strong> Employee → Supervisor → Manager</li>
                                        <li>• <strong>Reimburse & Penggantian Biaya:</strong> Employee → Supervisor → Manager → Finance</li>
                                    </ul>
                                    <p class="mt-2 text-xs text-gray-600 dark:text-gray-400">
                                        Jika supervisor tidak ditentukan, approval langsung ke Manager. Jika manager tidak ditentukan, langsung ke HR.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="employee_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Employee ID</label>
                            <input type="text" id="employee_id" name="employee_id" value="{{ old('employee_id', $employee->employee_id) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" required />
                        </div>

                        <div>
                            <label for="status_karyawan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Employment Status *</label>
                            <select id="status_karyawan" name="status_karyawan" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" required>
                                <option value="probation" {{ old('status_karyawan', $employee->status_karyawan) == 'probation' ? 'selected' : '' }}>Probation</option>
                                <option value="tetap" {{ old('status_karyawan', $employee->status_karyawan) == 'tetap' ? 'selected' : '' }}>Tetap (Permanent)</option>
                                <option value="kontrak" {{ old('status_karyawan', $employee->status_karyawan) == 'kontrak' ? 'selected' : '' }}>Kontrak (Contract)</option>
                                <option value="magang" {{ old('status_karyawan', $employee->status_karyawan) == 'magang' ? 'selected' : '' }}>Magang (Internship)</option>
                            </select>
                        </div>

                        <div>
                            <label for="hire_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hire Date *</label>
                            <input type="date" id="hire_date" name="hire_date" value="{{ old('hire_date', $employee->hire_date ? $employee->hire_date->format('Y-m-d') : '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   required>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Account Status *</label>
                            <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" required>
                                <option value="active" {{ old('status', $employee->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $employee->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="terminated" {{ old('status', $employee->status) === 'terminated' ? 'selected' : '' }}>Terminated</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Payroll & Bank Information -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 border-b pb-2">Payroll & Bank Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="salary" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Basic Salary (Gaji Pokok) *</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 dark:text-gray-400 text-sm font-medium">Rp</span>
                                </div>
                                <input type="number" id="salary" name="salary" value="{{ old('salary', $employee->salary) }}"
                                       class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                       min="0" required placeholder="0">
                            </div>
                        </div>

                        <div>
                            <label for="tunjangan_jabatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tunjangan Jabatan</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 dark:text-gray-400 text-sm font-medium">Rp</span>
                                </div>
                                <input type="number" id="tunjangan_jabatan" name="tunjangan_jabatan" value="{{ old('tunjangan_jabatan', $employee->tunjangan_jabatan) }}"
                                       class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                       min="0" placeholder="0">
                            </div>
                        </div>

                        <div>
                            <label for="tunjangan_transport" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tunjangan Transport</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 dark:text-gray-400 text-sm font-medium">Rp</span>
                                </div>
                                <input type="number" id="tunjangan_transport" name="tunjangan_transport" value="{{ old('tunjangan_transport', $employee->tunjangan_transport) }}"
                                       class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                       min="0" placeholder="0">
                            </div>
                        </div>

                        <div>
                            <label for="tunjangan_makan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tunjangan Makan</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 dark:text-gray-400 text-sm font-medium">Rp</span>
                                </div>
                                <input type="number" id="tunjangan_makan" name="tunjangan_makan" value="{{ old('tunjangan_makan', $employee->tunjangan_makan) }}"
                                       class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                       min="0" placeholder="0">
                            </div>
                        </div>

                        <div>
                            <label for="bank_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bank Name</label>
                            <input type="text" id="bank_name" name="bank_name" value="{{ old('bank_name', $employee->bank_name) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>

                        <div>
                            <label for="bank_account_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Account Number</label>
                            <input type="text" id="bank_account_number" name="bank_account_number" value="{{ old('bank_account_number', $employee->bank_account_number) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>

                        <div>
                            <label for="bank_account_holder" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Account Holder Name</label>
                            <input type="text" id="bank_account_holder" name="bank_account_holder" value="{{ old('bank_account_holder', $employee->bank_account_holder) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>

                        <div>
                            <label for="metode_pajak" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tax Method *</label>
                            <select id="metode_pajak" name="metode_pajak" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" required>
                                <option value="gross" {{ old('metode_pajak', $employee->metode_pajak) == 'gross' ? 'selected' : '' }}>Gross (Potong Gaji)</option>
                                <option value="nett" {{ old('metode_pajak', $employee->metode_pajak) == 'nett' ? 'selected' : '' }}>Nett (Ditanggung Perusahaan)</option>
                            </select>
                        </div>

                        <div>
                            <label for="ptkp_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">PTKP Status *</label>
                            <select id="ptkp_status" name="ptkp_status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" required>
                                <option value="TK/0" {{ old('ptkp_status', $employee->ptkp_status) == 'TK/0' ? 'selected' : '' }}>TK/0 (Tidak Kawin, 0 Tanggungan)</option>
                                <option value="TK/1" {{ old('ptkp_status', $employee->ptkp_status) == 'TK/1' ? 'selected' : '' }}>TK/1 (Tidak Kawin, 1 Tanggungan)</option>
                                <option value="TK/2" {{ old('ptkp_status', $employee->ptkp_status) == 'TK/2' ? 'selected' : '' }}>TK/2 (Tidak Kawin, 2 Tanggungan)</option>
                                <option value="TK/3" {{ old('ptkp_status', $employee->ptkp_status) == 'TK/3' ? 'selected' : '' }}>TK/3 (Tidak Kawin, 3 Tanggungan)</option>
                                <option value="K/0" {{ old('ptkp_status', $employee->ptkp_status) == 'K/0' ? 'selected' : '' }}>K/0 (Kawin, 0 Tanggungan)</option>
                                <option value="K/1" {{ old('ptkp_status', $employee->ptkp_status) == 'K/1' ? 'selected' : '' }}>K/1 (Kawin, 1 Tanggungan)</option>
                                <option value="K/2" {{ old('ptkp_status', $employee->ptkp_status) == 'K/2' ? 'selected' : '' }}>K/2 (Kawin, 2 Tanggungan)</option>
                                <option value="K/3" {{ old('ptkp_status', $employee->ptkp_status) == 'K/3' ? 'selected' : '' }}>K/3 (Kawin, 3 Tanggungan)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- BPJS Information -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 border-b pb-2">BPJS Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="bpjs_kesehatan_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">BPJS Kesehatan Number</label>
                            <input type="text" id="bpjs_kesehatan_number" name="bpjs_kesehatan_number" value="{{ old('bpjs_kesehatan_number', $employee->bpjs_kesehatan_number) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>

                        <div>
                            <label for="bpjs_kesehatan_faskes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Faskes 1</label>
                            <input type="text" id="bpjs_kesehatan_faskes" name="bpjs_kesehatan_faskes" value="{{ old('bpjs_kesehatan_faskes', $employee->bpjs_kesehatan_faskes) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        
                        <div>
                            <label for="bpjs_tk_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">BPJS Ketenagakerjaan Number</label>
                            <input type="text" id="bpjs_tk_number" name="bpjs_tk_number" value="{{ old('bpjs_tk_number', $employee->bpjs_tk_number) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>

                        <div class="flex items-center mt-6">
                            <input type="checkbox" id="bpjs_jp_aktif" name="bpjs_jp_aktif" value="1" {{ old('bpjs_jp_aktif', $employee->bpjs_jp_aktif) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="bpjs_jp_aktif" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                                Include Jaminan Pensiun (JP)
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Documents -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 border-b pb-2">Documents</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="foto_karyawan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Employee Photo</label>
                            @if($employee->foto_karyawan)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $employee->foto_karyawan) }}" alt="Current Photo" class="h-20 w-20 object-cover rounded-full">
                                </div>
                            @endif
                            <input type="file" id="foto_karyawan" name="foto_karyawan" accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label for="scan_ktp" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Scan KTP</label>
                            @if($employee->scan_ktp)
                                <div class="mb-2 text-sm text-green-600 dark:text-green-400">
                                    <i class="fa-solid fa-check mr-1"></i> File uploaded
                                </div>
                            @endif
                            <input type="file" id="scan_ktp" name="scan_ktp" accept=".pdf,.jpg,.jpeg,.png"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label for="scan_npwp" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Scan NPWP</label>
                            @if($employee->scan_npwp)
                                <div class="mb-2 text-sm text-green-600 dark:text-green-400">
                                    <i class="fa-solid fa-check mr-1"></i> File uploaded
                                </div>
                            @endif
                            <input type="file" id="scan_npwp" name="scan_npwp" accept=".pdf,.jpg,.jpeg,.png"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label for="scan_kk" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Scan KK</label>
                            @if($employee->scan_kk)
                                <div class="mb-2 text-sm text-green-600 dark:text-green-400">
                                    <i class="fa-solid fa-check mr-1"></i> File uploaded
                                </div>
                            @endif
                            <input type="file" id="scan_kk" name="scan_kk" accept=".pdf,.jpg,.jpeg,.png"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label for="bpjs_kesehatan_card" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">BPJS Kesehatan Card</label>
                            @if($employee->bpjs_kesehatan_card)
                                <div class="mb-2 text-sm text-green-600 dark:text-green-400">
                                    <i class="fa-solid fa-check mr-1"></i> File uploaded
                                </div>
                            @endif
                            <input type="file" id="bpjs_kesehatan_card" name="bpjs_kesehatan_card" accept=".pdf,.jpg,.jpeg,.png"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label for="bpjs_tk_card" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">BPJS Ketenagakerjaan Card</label>
                            @if($employee->bpjs_tk_card)
                                <div class="mb-2 text-sm text-green-600 dark:text-green-400">
                                    <i class="fa-solid fa-check mr-1"></i> File uploaded
                                </div>
                            @endif
                            <input type="file" id="bpjs_tk_card" name="bpjs_tk_card" accept=".pdf,.jpg,.jpeg,.png"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex items-center justify-end space-x-4">
                    <a href="{{ route('admin.employees.index') }}"
                       class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                        <i class="fa-solid fa-save mr-2"></i>Update Employee
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const divisionSelect = document.getElementById('division_id');
            const departmentSelect = document.getElementById('department_id');
            const positionSelect = document.getElementById('position_id');

            // Store original options data
            const departmentsData = Array.from(departmentSelect.options).slice(1).map(opt => ({
                value: opt.value,
                text: opt.text,
                divisionId: opt.getAttribute('data-division-id'),
                selected: opt.selected
            }));

            const positionsData = Array.from(positionSelect.options).slice(1).map(opt => ({
                value: opt.value,
                text: opt.text,
                departmentId: opt.getAttribute('data-department-id'),
                selected: opt.selected
            }));

            function updateDepartments(divisionId) {
                // Clear current options
                departmentSelect.length = 1; // Keep placeholder
                
                // If no division selected, show all departments
                const filtered = divisionId 
                    ? departmentsData.filter(dept => dept.divisionId == divisionId)
                    : departmentsData;
                
                filtered.forEach(dept => {
                    const option = new Option(dept.text, dept.value);
                    if (dept.selected) option.selected = true;
                    departmentSelect.add(option);
                });

                // Trigger change to update positions
                updatePositions(departmentSelect.value);
            }

            function updatePositions(departmentId) {
                positionSelect.length = 1; // Keep placeholder
                
                if (!departmentId) return;

                const filtered = positionsData.filter(pos => pos.departmentId == departmentId);
                
                filtered.forEach(pos => {
                    const option = new Option(pos.text, pos.value);
                    if (pos.selected) option.selected = true;
                    positionSelect.add(option);
                });
            }

            divisionSelect.addEventListener('change', function () {
                const divisionId = this.value;
                updateDepartments(divisionId);
            });

            departmentSelect.addEventListener('change', function() {
                // Auto-select division if department is selected
                const deptData = departmentsData.find(d => d.value == this.value);
                if (deptData && deptData.divisionId) {
                    if (divisionSelect.value != deptData.divisionId) {
                        divisionSelect.value = deptData.divisionId;
                    }
                }
                updatePositions(this.value);
            });

            // Initialize on load
            departmentSelect.length = 1;
            positionSelect.length = 1;

            if (divisionSelect.value) {
                updateDepartments(divisionSelect.value);
            }
        });
    </script>
</x-admin-layout>