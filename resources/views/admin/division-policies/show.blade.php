<x-admin-layout>
    <div class="py-2">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <x-page-header
                title="Division Policy Details"
                subtitle="Policy for {{ $divisionPolicy->division->name }}"
                :show-period-filter="false"
            />

            <div class="flex justify-end mb-6 space-x-3">
                <a href="{{ route('admin.division-policies.index') }}" class="bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 text-gray-800 dark:text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                    <i class="fa-solid fa-arrow-left mr-2"></i>Back to Policies
                </a>
                <a href="{{ route('admin.division-policies.edit', $divisionPolicy) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                    <i class="fa-solid fa-edit mr-2"></i>Edit Policy
                </a>
            </div>

            <div class="space-y-6">
                <!-- Basic Information -->
                <x-section-card title="Basic Information">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Policy Name</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $divisionPolicy->name }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Division</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $divisionPolicy->division->name }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Description</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $divisionPolicy->description ?? '-' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $divisionPolicy->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                    {{ $divisionPolicy->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </x-section-card>

                <!-- Work Schedule -->
                <x-section-card title="Work Schedule">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Work Days</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $divisionPolicy->formatted_work_days }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Work Hours</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $divisionPolicy->formatted_schedule }} ({{ $divisionPolicy->work_hours_per_day }} hours/day)</dd>
                        </div>
                    </dl>
                </x-section-card>

                <!-- Attendance Rules -->
                <x-section-card title="Attendance Rules">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-3">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Grace Period</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $divisionPolicy->grace_period_minutes }} minutes</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Max Late</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $divisionPolicy->max_late_minutes }} minutes</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Early Leave Grace</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $divisionPolicy->early_leave_grace_minutes }} minutes</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Location Check</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $divisionPolicy->require_location_check ? 'Required' : 'Not Required' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Allowed Radius</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $divisionPolicy->allowed_radius_meters }} meters</dd>
                        </div>
                    </dl>
                </x-section-card>

                <!-- Leave Policies -->
                <x-section-card title="Leave Policies">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-3">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Annual Leave</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $divisionPolicy->annual_leave_days }} days</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Sick Leave</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $divisionPolicy->sick_leave_days }} days</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Personal Leave</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $divisionPolicy->personal_leave_days }} days</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Negative Balance</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $divisionPolicy->allow_negative_leave_balance ? 'Allowed' : 'Not Allowed' }}</dd>
                        </div>
                    </dl>
                </x-section-card>
            </div>
        </div>
    </div>
</x-admin-layout>
