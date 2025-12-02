<x-admin-layout>
    <x-page-header title="Policy Management" subtitle="Manage division, employee, and attendance policies" class="mb-6">
    </x-page-header>

    <div x-data="{ 
        activeTab: '{{ $activeTab }}',
        changeTab(tab) {
            this.activeTab = tab;
            const url = new URL(window.location);
            url.searchParams.set('tab', tab);
            window.history.pushState({}, '', url);
        }
    }">
        <!-- Tabs Navigation -->
        <div class="mb-6">
            <div class="sm:hidden">
                <label for="tabs" class="sr-only">Select a tab</label>
                <select id="tabs" name="tabs" class="block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" @change="changeTab($event.target.value)">
                    <option value="division_policies" :selected="activeTab === 'division_policies'">Division Policies</option>
                    <option value="employee_policies" :selected="activeTab === 'employee_policies'">Employee Policies</option>
                    <option value="attendance_policies" :selected="activeTab === 'attendance_policies'">Attendance Policies</option>
                </select>
            </div>
            <div class="hidden sm:block">
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        <button @click="changeTab('division_policies')"
                            :class="activeTab === 'division_policies' 
                                ? 'border-blue-500 text-blue-600 dark:text-blue-400' 
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                            class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm transition-all duration-200">
                            <span :class="activeTab === 'division_policies' 
                                ? 'bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300' 
                                : 'bg-gray-100 text-gray-500 group-hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:group-hover:bg-gray-700'"
                                class="mr-3 py-0.5 px-2.5 rounded-full text-xs font-medium transition-colors duration-200">
                                <i class="fas fa-building"></i>
                            </span>
                            <span>Division Policies</span>
                        </button>

                        <button @click="changeTab('employee_policies')"
                            :class="activeTab === 'employee_policies' 
                                ? 'border-blue-500 text-blue-600 dark:text-blue-400' 
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                            class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm transition-all duration-200">
                            <span :class="activeTab === 'employee_policies' 
                                ? 'bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300' 
                                : 'bg-gray-100 text-gray-500 group-hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:group-hover:bg-gray-700'"
                                class="mr-3 py-0.5 px-2.5 rounded-full text-xs font-medium transition-colors duration-200">
                                <i class="fas fa-users-gear"></i>
                            </span>
                            <span>Employee Policies</span>
                        </button>

                        <button @click="changeTab('attendance_policies')"
                            :class="activeTab === 'attendance_policies' 
                                ? 'border-blue-500 text-blue-600 dark:text-blue-400' 
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                            class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm transition-all duration-200">
                            <span :class="activeTab === 'attendance_policies' 
                                ? 'bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300' 
                                : 'bg-gray-100 text-gray-500 group-hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:group-hover:bg-gray-700'"
                                class="mr-3 py-0.5 px-2.5 rounded-full text-xs font-medium transition-colors duration-200">
                                <i class="fas fa-clock"></i>
                            </span>
                            <span>Attendance Policies</span>
                        </button>
                    </nav>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded relative">
                {{ session('success') }}
            </div>
        @endif

        <!-- Division Policies Tab -->
        <div x-show="activeTab === 'division_policies'" x-cloak>
            <div class="flex justify-end mb-6 space-x-3">
                <a href="{{ route('admin.division-policies.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                    <i class="fa-solid fa-plus mr-2"></i>Create Division Policy
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($divisionPolicies->isEmpty())
                        <div class="text-center py-12">
                            <i class="fa-solid fa-file-lines text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
                            <p class="text-gray-500 dark:text-gray-400 text-lg mb-4">No division policies found</p>
                            <a href="{{ route('admin.division-policies.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors duration-200">
                                <i class="fa-solid fa-plus mr-2"></i>Create Your First Policy
                            </a>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($divisionPolicies as $policy)
                                <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-700 overflow-hidden relative">
                                    <!-- Decorative top border -->
                                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 to-indigo-600 transform origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></div>
                                    
                                    <div class="p-6">
                                        <div class="flex justify-between items-start mb-4">
                                            <div>
                                                <div class="flex items-center gap-2 mb-1">
                                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                                        {{ $policy->name }}
                                                    </h3>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $policy->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                                        {{ $policy->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </div>
                                                <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                                    <i class="fa-solid fa-layer-group mr-1.5 text-indigo-500"></i>
                                                    {{ $policy->division->name ?? 'Unknown Division' }}
                                                </div>
                                            </div>
                                            <div class="flex space-x-1">
                                                <a href="{{ route('admin.division-policies.edit', $policy) }}" class="p-2 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors rounded-full hover:bg-blue-50 dark:hover:bg-blue-900/20">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                                <form action="{{ route('admin.division-policies.destroy', $policy) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this policy?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors rounded-full hover:bg-red-50 dark:hover:bg-red-900/20">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>

                                        @if($policy->description)
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6 line-clamp-2">{{ $policy->description }}</p>
                                        @endif

                                        <div class="grid grid-cols-2 gap-4 mb-6">
                                            <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-3">
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wider font-semibold">Work Days</div>
                                                <div class="flex items-center text-sm font-medium text-gray-900 dark:text-white">
                                                    <i class="fa-solid fa-calendar-week text-blue-500 mr-2"></i>
                                                    <span class="truncate" title="{{ $policy->formatted_work_days }}">{{ $policy->formatted_work_days }}</span>
                                                </div>
                                            </div>
                                            <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-3">
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wider font-semibold">Schedule</div>
                                                <div class="flex items-center text-sm font-medium text-gray-900 dark:text-white">
                                                    <i class="fa-regular fa-clock text-purple-500 mr-2"></i>
                                                    {{ $policy->formatted_schedule }}
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Employees Section -->
                                        <div class="border-t border-gray-100 dark:border-gray-700 pt-4">
                                            <div class="flex items-center justify-between mb-3">
                                                <h4 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                    Assigned Employees
                                                </h4>
                                                <span class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs px-2 py-0.5 rounded-full">
                                                    {{ $policy->division->employees->count() ?? 0 }}
                                                </span>
                                            </div>
                                            
                                            <div class="flex -space-x-2 overflow-hidden py-1">
                                                @if($policy->division && $policy->division->employees->count() > 0)
                                                    @foreach($policy->division->employees->take(5) as $employee)
                                                        @if($employee->user)
                                                            @if($employee->user->avatar)
                                                                <img class="inline-block h-8 w-8 rounded-full ring-2 ring-white dark:ring-gray-800 object-cover" 
                                                                     src="{{ asset('storage/' . $employee->user->avatar) }}" 
                                                                     alt="{{ $employee->user->name }}"
                                                                     title="{{ $employee->user->name }}">
                                                            @else
                                                                <div class="inline-flex items-center justify-center h-8 w-8 rounded-full ring-2 ring-white dark:ring-gray-800 bg-gradient-to-br from-blue-400 to-indigo-500 text-white text-xs font-bold"
                                                                     title="{{ $employee->user->name }}">
                                                                    {{ substr($employee->user->name, 0, 1) }}
                                                                </div>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                    @if($policy->division->employees->count() > 5)
                                                        <div class="inline-flex items-center justify-center h-8 w-8 rounded-full ring-2 ring-white dark:ring-gray-800 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-xs font-medium">
                                                            +{{ $policy->division->employees->count() - 5 }}
                                                        </div>
                                                    @endif
                                                @else
                                                    <span class="text-sm text-gray-400 italic">No employees assigned</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-3 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center">
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            Updated {{ $policy->updated_at->diffForHumans() }}
                                        </div>
                                        <a href="{{ route('admin.division-policies.show', $policy) }}" class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 flex items-center group-hover:translate-x-1 transition-transform">
                                            View Details <i class="fa-solid fa-arrow-right ml-1.5 text-xs"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Employee Policies Tab -->
        <div x-show="activeTab === 'employee_policies'" x-cloak class="space-y-6">
            <div class="flex justify-end mb-4">
                <a href="{{ route('admin.employee-policies.create') }}" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                    <i class="fa-solid fa-plus mr-2"></i>Create Override
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Employee</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Work Schedule</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($employeePolicies as $policy)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($policy->employee && $policy->employee->user)
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    @if($policy->employee->user->avatar)
                                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $policy->employee->user->avatar) }}" alt="">
                                                    @else
                                                        <div class="h-10 w-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $policy->employee->user->initials() }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $policy->employee->user->name }}</div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $policy->employee->user->email }}</div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="text-sm text-red-500 italic">Employee Not Found</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        <div>{{ $policy->formatted_schedule }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $policy->formatted_work_days }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                            {{ $policy->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                            {{ $policy->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('admin.employee-policies.edit', $policy) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                <i class="fa-solid fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('admin.employee-policies.destroy', $policy) }}" class="inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this policy override?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <i class="fa-solid fa-file-contract text-gray-300 dark:text-gray-600 text-4xl mb-4"></i>
                                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Individual Overrides</h3>
                                            <p class="text-gray-500 dark:text-gray-400 mb-4">Most employees should follow the default or division policy.</p>
                                            <a href="{{ route('admin.employee-policies.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                                                <i class="fa-solid fa-plus mr-2"></i>Create Override
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Attendance Policies Tab -->
        <div x-show="activeTab === 'attendance_policies'" x-cloak>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                @cannot('manage-attendance-policy')
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 m-6 mb-0">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fa-solid fa-lock text-yellow-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    You do not have permission to edit attendance policies. This view is read-only.
                                </p>
                            </div>
                        </div>
                    </div>
                @endcannot

                <form action="{{ $attendancePolicy ? route('admin.attendance-policy.update') : route('admin.attendance-policy.store') }}" method="POST">
                    @csrf
                    @if($attendancePolicy)
                        @method('PUT')
                    @endif

                    <fieldset {{ Auth::user()->can('manage-attendance-policy') ? '' : 'disabled' }}>
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
                                        $selectedDays = old('work_days', $attendancePolicy->work_days ?? ['monday', 'tuesday', 'wednesday', 'thursday', 'friday']);
                                    @endphp
                                    @foreach($days as $value => $label)
                                        <label class="flex items-center p-3 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 {{ in_array($value, $selectedDays) ? 'bg-blue-50 dark:bg-blue-900/20 border-blue-500' : '' }}">
                                            <input type="checkbox" name="work_days[]" value="{{ $value }}" 
                                                   {{ in_array($value, $selectedDays) ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 disabled:opacity-50">
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
                                           value="{{ old('start_time', $attendancePolicy ? $attendancePolicy->start_time->format('H:i') : '08:00') }}"
                                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 dark:disabled:bg-gray-800"
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
                                           value="{{ old('end_time', $attendancePolicy ? $attendancePolicy->end_time->format('H:i') : '17:00') }}"
                                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 dark:disabled:bg-gray-800"
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
                                           value="{{ old('late_tolerance', $attendancePolicy->late_tolerance ?? 15) }}"
                                           min="0" max="120"
                                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 dark:disabled:bg-gray-800">
                                    @error('late_tolerance')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="break_duration" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Durasi Istirahat (menit)
                                    </label>
                                    <input type="number" name="break_duration" id="break_duration" 
                                           value="{{ old('break_duration', $attendancePolicy->break_duration ?? 60) }}"
                                           min="0" max="480"
                                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 dark:disabled:bg-gray-800">
                                    @error('break_duration')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="overtime_after_minutes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Lembur Setelah (menit)
                                    </label>
                                    <input type="number" name="overtime_after_minutes" id="overtime_after_minutes" 
                                           value="{{ old('overtime_after_minutes', $attendancePolicy->overtime_after_minutes ?? 0) }}"
                                           min="0"
                                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 dark:disabled:bg-gray-800">
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
                                               {{ old('auto_checkout', $attendancePolicy->auto_checkout ?? false) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 disabled:opacity-50"
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

                                <div id="auto_checkout_time_container" class="{{ old('auto_checkout', $attendancePolicy->auto_checkout ?? false) ? '' : 'hidden' }} mt-4 ml-8">
                                    <label for="auto_checkout_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Waktu Auto Check Out
                                    </label>
                                    <input type="time" name="auto_checkout_time" id="auto_checkout_time" 
                                           value="{{ old('auto_checkout_time', $attendancePolicy && $attendancePolicy->auto_checkout_time ? $attendancePolicy->auto_checkout_time->format('H:i') : '18:00') }}"
                                           class="w-full md:w-64 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 dark:disabled:bg-gray-800">
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <!-- Footer -->
                    @can('manage-attendance-policy')
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-3">
                            <button type="submit" 
                                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center gap-2">
                                <i class="fa-solid fa-save"></i>
                                <span>{{ $attendancePolicy ? 'Perbarui' : 'Simpan' }} Kebijakan</span>
                            </button>
                        </div>
                    @endcan
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
