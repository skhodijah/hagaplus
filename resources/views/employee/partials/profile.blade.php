<div class="p-6">
    <!-- Desktop Layout -->
    <div class="hidden md:block">
        <div class="grid grid-cols-12 gap-6">
            <!-- Profile Photo Column -->
            <div class="col-span-3 flex justify-center">
                <div class="w-82 h-82 overflow-hidden rounded-xl">
                    <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&color=FFFFFF&background=008159' }}"
                        alt="Profile Photo"
                        class="h-full w-full object-cover">
                </div>
            </div>

            <!-- Left Column - Profile Info -->
            <div class="col-span-5 space-y-6">
                <!-- Name and Position -->
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ auth()->user()->name }}</h1>
                    <p class="text-gray-600 mt-1">
                        {{ $employee->position ?? 'Karyawan' }}
                        @if($employee->department)
                            • {{ $employee->department }}
                        @endif
                    </p>
                    
                    <div class="mt-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $employee->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <span class="w-2 h-2 rounded-full {{ $employee->status === 'active' ? 'bg-green-500' : 'bg-red-500' }} mr-2"></span>
                            {{ ucfirst($employee->status ?? 'inactive') }}
                        </span>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Email</p>
                        <div class="flex items-center gap-2 text-gray-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                            </svg>
                            <span class="text-sm">{{ auth()->user()->email }}</span>
                        </div>
                    </div>
                    
                    @if($user->phone)
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Telepon</p>
                        <div class="flex items-center gap-2 text-gray-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                            </svg>
                            <span class="text-sm">{{ auth()->user()->phone }}</span>
                        </div>
                    </div>
                    @endif
                    
                    @if($employee->employee_id)
                    <div>
                        <p class="text-sm text-gray-500 mb-1">ID Karyawan</p>
                        <p class="text-sm text-gray-800">{{ $employee->employee_id }}</p>
                    </div>
                    @endif
                    
                    @if($employee->hire_date)
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Bergabung</p>
                        <p class="text-sm text-gray-800">{{ $employee->hire_date->format('d M Y') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Right Column - Company Info & Attendance Policy -->
            <div class="col-span-4 flex items-center">
                <div class="border-l border-gray-200 pl-6 h-full space-y-6">
                    @if($employee->instansi)
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Perusahaan</p>
                        <p class="text-sm font-medium text-gray-900">{{ $employee->instansi->nama_instansi }}</p>
                    </div>
                    @endif
                    
                    @if($employee->branch)
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Cabang</p>
                        <p class="text-sm font-medium text-gray-900">{{ $employee->branch->name }}</p>
                    </div>
                    @endif

                    @if($employee->effective_policy && $employee->effective_policy->work_start_time)
                    <div class="pt-4 border-t border-gray-200">
                        <p class="text-sm text-gray-500 mb-2">Kebijakan Absensi</p>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2 text-xs">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-gray-700">{{ \Carbon\Carbon::parse($employee->effective_policy->work_start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($employee->effective_policy->work_end_time)->format('H:i') }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-gray-700">
                                    @php
                                        $dayNames = [
                                            'monday' => 'Sen',
                                            'tuesday' => 'Sel',
                                            'wednesday' => 'Rab',
                                            'thursday' => 'Kam',
                                            'friday' => 'Jum',
                                            'saturday' => 'Sab',
                                            'sunday' => 'Min',
                                        ];
                                        $workDays = collect($employee->effective_policy->work_days)->map(fn($day) => $dayNames[$day] ?? $day)->join(', ');
                                    @endphp
                                    {{ $workDays }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Layout -->
    <div class="md:hidden">
        <div class="flex items-start space-x-4">
            <!-- Profile Photo -->
            <div class="flex-shrink-0">
                <div class="h-16 w-16 rounded-lg overflow-hidden border border-gray-200">
                    <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&color=FFFFFF&background=008159' }}"
                         alt="Profile Photo"
                         class="h-full w-full object-cover">
                </div>
            </div>
            
            <!-- Profile Info -->
            <div class="flex-1">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">{{ auth()->user()->name }}</h2>
                        <p class="text-sm text-gray-600">
                            {{ $employee->position ?? 'Karyawan' }}
                            @if($employee->department)
                                • {{ $employee->department }}
                            @endif
                        </p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $employee->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ ucfirst($employee->status ?? 'inactive') }}
                    </span>
                </div>
                
                <div class="mt-3 space-y-2">
                    <div class="flex items-center text-sm text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                        </svg>
                        <span class="truncate">{{ auth()->user()->email }}</span>
                    </div>
                    
                    @if($user->phone)
                    <div class="flex items-center text-sm text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                        </svg>
                        <span>{{ auth()->user()->phone }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        @if($employee->employee_id || $employee->hire_date || $employee->instansi || $employee->branch)
        <div class="mt-4 pt-4 border-t border-gray-200 grid grid-cols-2 gap-4">
            @if($employee->employee_id)
            <div>
                <p class="text-xs text-gray-500">ID Karyawan</p>
                <p class="text-sm font-medium">{{ $employee->employee_id }}</p>
            </div>
            @endif
            
            @if($employee->hire_date)
            <div>
                <p class="text-xs text-gray-500">Bergabung</p>
                <p class="text-sm font-medium">{{ $employee->hire_date->format('d M Y') }}</p>
            </div>
            @endif
            
            @if($employee->instansi)
            <div class="col-span-2">
                <p class="text-xs text-gray-500">Perusahaan</p>
                <p class="text-sm font-medium">{{ $employee->instansi->nama_instansi }}</p>
            </div>
            @endif
            
            @if($employee->branch)
            <div class="col-span-2">
                <p class="text-xs text-gray-500">Cabang</p>
                <p class="text-sm font-medium">{{ $employee->branch->name }}</p>
            </div>
            @endif
        </div>
        @endif
    </div>
</div>
