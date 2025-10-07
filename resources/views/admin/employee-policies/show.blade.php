<x-admin-layout>
    <div class="py-2">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <x-page-header
                title="Employee Policy Details"
                subtitle="View detailed policy information for {{ $employeePolicy->employee->name }}"
                :show-period-filter="false"
            />

            <div class="flex justify-end mb-6">
                <div class="flex space-x-3">
                    <a href="{{ route('admin.employee-policies.edit', $employeePolicy) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fa-solid fa-edit mr-2"></i>Edit Policy
                    </a>
                    <a href="{{ route('admin.employee-policies.index') }}" class="bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 text-gray-800 dark:text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                        <i class="fa-solid fa-arrow-left mr-2"></i>Back to Policies
                    </a>
                </div>
            </div>

            <!-- Employee Information -->
            <x-section-card title="Employee Information">
                <div class="flex items-center space-x-4">
                    @if($employeePolicy->employee->avatar)
                        <img class="h-16 w-16 rounded-full object-cover" src="{{ asset('storage/' . $employeePolicy->employee->avatar) }}" alt="">
                    @else
                        <div class="h-16 w-16 rounded-full bg-blue-500 flex items-center justify-center text-white text-xl font-bold">
                            {{ $employeePolicy->employee->initials() }}
                        </div>
                    @endif
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $employeePolicy->employee->name }}</h3>
                        <p class="text-gray-600 dark:text-gray-400">{{ $employeePolicy->employee->email }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-500">Employee ID: {{ $employeePolicy->employee->id }}</p>
                    </div>
                </div>
            </x-section-card>

            <!-- Policy Overview -->
            <x-section-card title="Policy Overview">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Basic Information</h4>
                        <dl class="space-y-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Policy Name</dt>
                                <dd class="text-sm text-gray-900 dark:text-white">{{ $employeePolicy->name }}</dd>
                            </div>
                            @if($employeePolicy->description)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Description</dt>
                                <dd class="text-sm text-gray-900 dark:text-white">{{ $employeePolicy->description }}</dd>
                            </div>
                            @endif
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                                <dd>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        @if($employeePolicy->is_active && $employeePolicy->isCurrentlyEffective()) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        @elseif($employeePolicy->is_active) bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                        @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                        @if($employeePolicy->is_active && $employeePolicy->isCurrentlyEffective())
                                            Active
                                        @elseif($employeePolicy->is_active)
                                            Scheduled
                                        @else
                                            Inactive
                                        @endif
                                    </span>
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Effective Period</h4>
                        <dl class="space-y-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Effective From</dt>
                                <dd class="text-sm text-gray-900 dark:text-white">
                                    {{ $employeePolicy->effective_from ? $employeePolicy->effective_from->format('M d, Y') : 'Immediate' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Effective Until</dt>
                                <dd class="text-sm text-gray-900 dark:text-white">
                                    {{ $employeePolicy->effective_until ? $employeePolicy->effective_until->format('M d, Y') : 'Ongoing' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Created</dt>
                                <dd class="text-sm text-gray-900 dark:text-white">{{ $employeePolicy->created_at->format('M d, Y H:i') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </x-section-card>

            <!-- Work Schedule -->
            <x-section-card title="Work Schedule">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Schedule Details</h4>
                        <dl class="space-y-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Work Hours</dt>
                                <dd class="text-sm text-gray-900 dark:text-white">{{ $employeePolicy->formatted_schedule }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Hours per Day</dt>
                                <dd class="text-sm text-gray-900 dark:text-white">{{ $employeePolicy->work_hours_per_day }} hours</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Work Days</dt>
                                <dd class="text-sm text-gray-900 dark:text-white">{{ $employeePolicy->formatted_work_days }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Schedule Options</h4>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <i class="fa-solid fa-{{ $employeePolicy->flexible_hours ? 'check text-green-500' : 'times text-red-500' }} mr-2"></i>
                                <span class="text-sm text-gray-900 dark:text-white">Flexible Hours</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fa-solid fa-{{ $employeePolicy->skip_weekends ? 'check text-green-500' : 'times text-red-500' }} mr-2"></i>
                                <span class="text-sm text-gray-900 dark:text-white">Skip Weekends</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fa-solid fa-{{ $employeePolicy->skip_holidays ? 'check text-green-500' : 'times text-red-500' }} mr-2"></i>
                                <span class="text-sm text-gray-900 dark:text-white">Skip Holidays</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fa-solid fa-{{ $employeePolicy->can_work_from_home ? 'check text-green-500' : 'times text-red-500' }} mr-2"></i>
                                <span class="text-sm text-gray-900 dark:text-white">Work from Home Allowed</span>
                            </div>
                        </div>
                    </div>
                </div>
            </x-section-card>

            <!-- Attendance Rules -->
            <x-section-card title="Attendance Rules">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Time Rules</h4>
                        <dl class="space-y-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Grace Period</dt>
                                <dd class="text-sm text-gray-900 dark:text-white">{{ $employeePolicy->grace_period_minutes }} minutes</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Max Late Minutes</dt>
                                <dd class="text-sm text-gray-900 dark:text-white">{{ $employeePolicy->max_late_minutes }} minutes</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Early Leave Grace</dt>
                                <dd class="text-sm text-gray-900 dark:text-white">{{ $employeePolicy->early_leave_grace_minutes }} minutes</dd>
                            </div>
                        </dl>
                    </div>

                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Location & Overtime</h4>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <i class="fa-solid fa-{{ $employeePolicy->require_location_check ? 'check text-green-500' : 'times text-red-500' }} mr-2"></i>
                                <span class="text-sm text-gray-900 dark:text-white">Location Check Required</span>
                            </div>
                            @if($employeePolicy->require_location_check)
                            <div class="ml-6">
                                <span class="text-xs text-gray-500 dark:text-gray-400">Radius: {{ $employeePolicy->allowed_radius_meters }} meters</span>
                            </div>
                            @endif
                            <div class="flex items-center">
                                <i class="fa-solid fa-{{ $employeePolicy->allow_overtime ? 'check text-green-500' : 'times text-red-500' }} mr-2"></i>
                                <span class="text-sm text-gray-900 dark:text-white">Overtime Allowed</span>
                            </div>
                            @if($employeePolicy->allow_overtime)
                            <div class="ml-6 space-y-1">
                                <span class="text-xs text-gray-500 dark:text-gray-400">Max per day: {{ $employeePolicy->max_overtime_hours_per_day }} hours</span>
                                <br>
                                <span class="text-xs text-gray-500 dark:text-gray-400">Max per week: {{ $employeePolicy->max_overtime_hours_per_week }} hours</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </x-section-card>

            <!-- Leave Policies -->
            <x-section-card title="Leave Policies">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Leave Entitlements</h4>
                        <dl class="space-y-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Annual Leave</dt>
                                <dd class="text-sm text-gray-900 dark:text-white">{{ $employeePolicy->annual_leave_days }} days</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Sick Leave</dt>
                                <dd class="text-sm text-gray-900 dark:text-white">{{ $employeePolicy->sick_leave_days }} days</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Personal Leave</dt>
                                <dd class="text-sm text-gray-900 dark:text-white">{{ $employeePolicy->personal_leave_days }} days</dd>
                            </div>
                        </dl>
                    </div>

                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Leave Options</h4>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <i class="fa-solid fa-{{ $employeePolicy->allow_negative_leave_balance ? 'check text-green-500' : 'times text-red-500' }} mr-2"></i>
                                <span class="text-sm text-gray-900 dark:text-white">Negative Leave Balance Allowed</span>
                            </div>
                        </div>
                    </div>
                </div>
            </x-section-card>

            <!-- Actions -->
            <div class="mt-6 flex items-center justify-between">
                <div class="flex space-x-3">
                    <form method="POST" action="{{ route('admin.employee-policies.toggle-status', $employeePolicy) }}" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <i class="fa-solid fa-{{ $employeePolicy->is_active ? 'pause' : 'play' }} mr-2"></i>
                            {{ $employeePolicy->is_active ? 'Deactivate' : 'Activate' }} Policy
                        </button>
                    </form>
                </div>

                <div class="flex space-x-3">
                    <a href="{{ route('admin.employee-policies.edit', $employeePolicy) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fa-solid fa-edit mr-2"></i>Edit Policy
                    </a>
                    <form method="POST" action="{{ route('admin.employee-policies.destroy', $employeePolicy) }}" class="inline"
                          onsubmit="return confirm('Are you sure you want to delete this policy? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            <i class="fa-solid fa-trash mr-2"></i>Delete Policy
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>