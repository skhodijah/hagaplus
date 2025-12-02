<x-admin-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $divisionPolicy->name }}</h1>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $divisionPolicy->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                            {{ $divisionPolicy->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center">
                        <i class="fa-solid fa-layer-group mr-2 text-indigo-500"></i>
                        Policy for <span class="font-semibold text-gray-700 dark:text-gray-300 ml-1">{{ $divisionPolicy->division->name }}</span>
                    </p>
                </div>
                <div class="mt-4 md:mt-0 flex space-x-3">
                    <a href="{{ route('admin.policies.index', ['tab' => 'division_policies']) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        <i class="fa-solid fa-arrow-left mr-2"></i> Back
                    </a>
                    <a href="{{ route('admin.division-policies.edit', $divisionPolicy) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <i class="fa-solid fa-pen-to-square mr-2"></i> Edit Policy
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Policy Details -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- General Info & Description -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <i class="fa-solid fa-circle-info text-blue-500 mr-2"></i> General Information
                            </h3>
                            @if($divisionPolicy->description)
                                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-4 mb-6">
                                    <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                                        {{ $divisionPolicy->description }}
                                    </p>
                                </div>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-semibold mb-1">Division</div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white flex items-center">
                                        {{ $divisionPolicy->division->name }}
                                        <span class="ml-2 text-xs text-gray-500">({{ $divisionPolicy->division->code ?? 'No Code' }})</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-semibold mb-1">Last Updated</div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $divisionPolicy->updated_at->format('d M Y, H:i') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Work Schedule -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                                <i class="fa-solid fa-clock text-purple-500 mr-2"></i> Work Schedule
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <!-- Days & Hours -->
                                <div class="space-y-6">
                                    <div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-semibold mb-2">Working Days</div>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                                                @php
                                                    $isWorkDay = in_array($day, $divisionPolicy->work_days ?? []);
                                                @endphp
                                                <span class="px-3 py-1 rounded-lg text-xs font-medium border {{ $isWorkDay ? 'bg-blue-50 border-blue-200 text-blue-700 dark:bg-blue-900/30 dark:border-blue-800 dark:text-blue-300' : 'bg-gray-50 border-gray-200 text-gray-400 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-500' }}">
                                                    {{ ucfirst(substr($day, 0, 3)) }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-semibold mb-2">Standard Hours</div>
                                        <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700/30 rounded-xl border border-gray-100 dark:border-gray-700">
                                            <div class="mr-4 p-2 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                                                <i class="fa-regular fa-clock text-purple-500 text-lg"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-900 dark:text-white">
                                                    {{ $divisionPolicy->formatted_schedule }}
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $divisionPolicy->work_hours_per_day }} hours / day
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Additional Settings -->
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between p-3 rounded-xl {{ $divisionPolicy->flexible_hours ? 'bg-green-50 dark:bg-green-900/10' : 'bg-gray-50 dark:bg-gray-700/30' }}">
                                        <div class="flex items-center">
                                            <i class="fa-solid fa-shuffle {{ $divisionPolicy->flexible_hours ? 'text-green-500' : 'text-gray-400' }} mr-3"></i>
                                            <span class="text-sm font-medium {{ $divisionPolicy->flexible_hours ? 'text-green-900 dark:text-green-300' : 'text-gray-600 dark:text-gray-400' }}">Flexible Hours</span>
                                        </div>
                                        @if($divisionPolicy->flexible_hours)
                                            <i class="fa-solid fa-check text-green-500"></i>
                                        @else
                                            <i class="fa-solid fa-xmark text-gray-400"></i>
                                        @endif
                                    </div>

                                    <div class="flex items-center justify-between p-3 rounded-xl {{ $divisionPolicy->can_work_from_home ? 'bg-green-50 dark:bg-green-900/10' : 'bg-gray-50 dark:bg-gray-700/30' }}">
                                        <div class="flex items-center">
                                            <i class="fa-solid fa-house-laptop {{ $divisionPolicy->can_work_from_home ? 'text-green-500' : 'text-gray-400' }} mr-3"></i>
                                            <span class="text-sm font-medium {{ $divisionPolicy->can_work_from_home ? 'text-green-900 dark:text-green-300' : 'text-gray-600 dark:text-gray-400' }}">Work From Home</span>
                                        </div>
                                        @if($divisionPolicy->can_work_from_home)
                                            <i class="fa-solid fa-check text-green-500"></i>
                                        @else
                                            <i class="fa-solid fa-xmark text-gray-400"></i>
                                        @endif
                                    </div>

                                    <div class="flex items-center justify-between p-3 rounded-xl {{ $divisionPolicy->has_shifts ? 'bg-green-50 dark:bg-green-900/10' : 'bg-gray-50 dark:bg-gray-700/30' }}">
                                        <div class="flex items-center">
                                            <i class="fa-solid fa-users-viewfinder {{ $divisionPolicy->has_shifts ? 'text-green-500' : 'text-gray-400' }} mr-3"></i>
                                            <span class="text-sm font-medium {{ $divisionPolicy->has_shifts ? 'text-green-900 dark:text-green-300' : 'text-gray-600 dark:text-gray-400' }}">Shift System</span>
                                        </div>
                                        @if($divisionPolicy->has_shifts)
                                            <i class="fa-solid fa-check text-green-500"></i>
                                        @else
                                            <i class="fa-solid fa-xmark text-gray-400"></i>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Rules Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Attendance Rules -->
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                    <i class="fa-solid fa-user-clock text-orange-500 mr-2"></i> Attendance Rules
                                </h3>
                                <div class="space-y-4">
                                    <div class="flex justify-between items-center border-b border-gray-100 dark:border-gray-700 pb-3">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Grace Period</span>
                                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $divisionPolicy->grace_period_minutes }} mins</span>
                                    </div>
                                    <div class="flex justify-between items-center border-b border-gray-100 dark:border-gray-700 pb-3">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Max Late</span>
                                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $divisionPolicy->max_late_minutes }} mins</span>
                                    </div>
                                    <div class="flex justify-between items-center border-b border-gray-100 dark:border-gray-700 pb-3">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Early Leave Grace</span>
                                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $divisionPolicy->early_leave_grace_minutes }} mins</span>
                                    </div>
                                    <div class="flex justify-between items-center pt-1">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Location Check</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $divisionPolicy->require_location_check ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                            {{ $divisionPolicy->require_location_check ? 'Required' : 'Optional' }}
                                        </span>
                                    </div>
                                    @if($divisionPolicy->require_location_check)
                                    <div class="flex justify-between items-center pt-1">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Allowed Radius</span>
                                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $divisionPolicy->allowed_radius_meters }}m</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Leave & Overtime -->
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                    <i class="fa-solid fa-umbrella-beach text-teal-500 mr-2"></i> Leave & Overtime
                                </h3>
                                <div class="space-y-4">
                                    <div class="flex justify-between items-center border-b border-gray-100 dark:border-gray-700 pb-3">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Annual Leave</span>
                                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $divisionPolicy->annual_leave_days }} days</span>
                                    </div>
                                    <div class="flex justify-between items-center border-b border-gray-100 dark:border-gray-700 pb-3">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Sick Leave</span>
                                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $divisionPolicy->sick_leave_days }} days</span>
                                    </div>
                                    <div class="flex justify-between items-center border-b border-gray-100 dark:border-gray-700 pb-3">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Personal Leave</span>
                                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $divisionPolicy->personal_leave_days }} days</span>
                                    </div>
                                    <div class="flex justify-between items-center pt-1">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Overtime</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $divisionPolicy->allow_overtime ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                            {{ $divisionPolicy->allow_overtime ? 'Allowed' : 'Not Allowed' }}
                                        </span>
                                    </div>
                                    @if($divisionPolicy->allow_overtime)
                                    <div class="flex justify-between items-center pt-1">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Max OT/Day</span>
                                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $divisionPolicy->max_overtime_hours_per_day }} hrs</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Employees List -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden sticky top-6">
                        <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                    <i class="fa-solid fa-users text-indigo-500 mr-2"></i> Employees
                                </h3>
                                <span class="bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200 text-xs font-bold px-2.5 py-0.5 rounded-full">
                                    {{ $divisionPolicy->division->employees->count() }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Employees under {{ $divisionPolicy->division->name }}
                            </p>
                        </div>
                        
                        <div class="max-h-[600px] overflow-y-auto">
                            @if($divisionPolicy->division->employees->count() > 0)
                                <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                                    @foreach($divisionPolicy->division->employees as $employee)
                                        <li class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                            <div class="flex items-center space-x-3">
                                                <div class="flex-shrink-0">
                                                    @if($employee->user && $employee->user->avatar)
                                                        <img class="h-10 w-10 rounded-full object-cover ring-2 ring-white dark:ring-gray-800" src="{{ asset('storage/' . $employee->user->avatar) }}" alt="{{ $employee->user->name }}">
                                                    @else
                                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white font-bold text-sm ring-2 ring-white dark:ring-gray-800">
                                                            {{ $employee->user ? substr($employee->user->name, 0, 1) : '?' }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                                        {{ $employee->user->name ?? 'Unknown User' }}
                                                    </p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                                        {{ $employee->position->name ?? 'No Position' }}
                                                    </p>
                                                </div>
                                                <div>
                                                    <a href="{{ route('admin.employees.show', $employee) }}" class="text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                                        <i class="fa-solid fa-chevron-right text-xs"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="p-8 text-center">
                                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-700 mb-3">
                                        <i class="fa-solid fa-users-slash text-gray-400"></i>
                                    </div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">No employees assigned to this division yet.</p>
                                </div>
                            @endif
                        </div>
                        
                        @if($divisionPolicy->division->employees->count() > 0)
                        <div class="p-4 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-700 text-center">
                            <a href="{{ route('admin.employees.index', ['division_id' => $divisionPolicy->division_id]) }}" class="text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                                Manage Employees <i class="fa-solid fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
