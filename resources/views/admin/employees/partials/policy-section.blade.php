<div class="space-y-8">
    <!-- Effective Policy Summary -->
    <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl shadow-xl p-8 text-white relative overflow-hidden">
        <!-- Decorative bg elements -->
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-blue-500/10 rounded-full blur-3xl"></div>

        <div class="relative z-10">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-xl font-bold tracking-tight">Effective Policy</h2>
                    <p class="text-gray-400 text-sm mt-1">Currently applied attendance rules</p>
                </div>
                <div class="px-3 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/10 text-xs font-medium flex items-center">
                    <span class="w-1.5 h-1.5 rounded-full mr-2 {{ $employee->policy ? 'bg-blue-400' : ($employee->division && $employee->division->policy ? 'bg-purple-400' : 'bg-gray-400') }}"></span>
                    @if($employee->policy)
                        Custom Override Active
                    @elseif($employee->division && $employee->division->policy)
                        Inherited from Division
                    @else
                        System Default
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white/10 rounded-xl p-5 backdrop-blur-sm border border-white/5 hover:bg-white/15 transition-colors">
                    <div class="flex items-center mb-3">
                        <div class="p-2 rounded-lg bg-blue-500/20 text-blue-300 mr-3">
                            <i class="fa-regular fa-clock"></i>
                        </div>
                        <span class="text-sm text-gray-300 font-medium">Schedule</span>
                    </div>
                    <p class="text-xl font-bold tracking-tight">{{ $employee->effective_policy->formatted_schedule ?? '08:00 - 17:00' }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ $employee->effective_policy->formatted_work_days ?? 'Mon - Fri' }}</p>
                </div>
                <div class="bg-white/10 rounded-xl p-5 backdrop-blur-sm border border-white/5 hover:bg-white/15 transition-colors">
                    <div class="flex items-center mb-3">
                        <div class="p-2 rounded-lg bg-green-500/20 text-green-300 mr-3">
                            <i class="fa-solid fa-umbrella-beach"></i>
                        </div>
                        <span class="text-sm text-gray-300 font-medium">Annual Leave</span>
                    </div>
                    <p class="text-xl font-bold tracking-tight">{{ $employee->effective_policy->annual_leave_days ?? 12 }} Days</p>
                    <p class="text-xs text-gray-400 mt-1">Per year allowance</p>
                </div>
                <div class="bg-white/10 rounded-xl p-5 backdrop-blur-sm border border-white/5 hover:bg-white/15 transition-colors">
                    <div class="flex items-center mb-3">
                        <div class="p-2 rounded-lg bg-pink-500/20 text-pink-300 mr-3">
                            <i class="fa-solid fa-stopwatch"></i>
                        </div>
                        <span class="text-sm text-gray-300 font-medium">Grace Period</span>
                    </div>
                    <p class="text-xl font-bold tracking-tight">{{ $employee->effective_policy->grace_period_minutes ?? 15 }} Mins</p>
                    <p class="text-xs text-gray-400 mt-1">Late allowance</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Policy Configuration -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="border-b border-gray-100 dark:border-gray-700 px-6 py-4 flex items-center justify-between bg-gray-50/50 dark:bg-gray-800">
            <h3 class="font-bold text-gray-900 dark:text-white">Policy Details</h3>
            <div class="flex space-x-3 text-sm">
                @if($employee->policy)
                    <a href="{{ route('admin.employee-policies.edit', $employee->policy) }}" class="font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">Edit Override</a>
                @else
                    <a href="{{ route('admin.employee-policies.create', ['employee_id' => $employee->id]) }}" class="font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">Create Override</a>
                @endif
                <span class="text-gray-300 dark:text-gray-600">|</span>
                @if($employee->division && $employee->division->policy)
                    <a href="{{ route('admin.division-policies.edit', $employee->division->policy) }}" class="font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">View Division Policy</a>
                @else
                    <span class="text-gray-400 italic">No Division Policy</span>
                @endif
            </div>
        </div>
        
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
            <!-- Attendance Rules -->
            <div>
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Attendance Rules</h4>
                <ul class="space-y-4">
                    <li class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Max Late Minutes</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $employee->effective_policy->max_late_minutes ?? 120 }}m</span>
                    </li>
                    <li class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Early Leave Grace</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $employee->effective_policy->early_leave_grace_minutes ?? 15 }}m</span>
                    </li>
                    <li class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Location Check</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ ($employee->effective_policy->require_location_check ?? true) ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                            {{ ($employee->effective_policy->require_location_check ?? true) ? 'Required' : 'Optional' }}
                        </span>
                    </li>
                    @if($employee->effective_policy->require_location_check ?? true)
                    <li class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Allowed Radius</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $employee->effective_policy->allowed_radius_meters ?? 100 }}m</span>
                    </li>
                    @endif
                </ul>
            </div>

            <!-- Leave & Overtime -->
            <div>
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Leave & Overtime</h4>
                <ul class="space-y-4">
                    <li class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Sick Leave</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $employee->effective_policy->sick_leave_days ?? 14 }} Days</span>
                    </li>
                    <li class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Personal Leave</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $employee->effective_policy->personal_leave_days ?? 3 }} Days</span>
                    </li>
                    <li class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Overtime</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ ($employee->effective_policy->allow_overtime ?? false) ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                            {{ ($employee->effective_policy->allow_overtime ?? false) ? 'Allowed' : 'Not Allowed' }}
                        </span>
                    </li>
                    <li class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Negative Balance</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ ($employee->effective_policy->allow_negative_leave_balance ?? false) ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                            {{ ($employee->effective_policy->allow_negative_leave_balance ?? false) ? 'Allowed' : 'Blocked' }}
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
