<x-admin-layout>
    <div class="py-2">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <x-page-header
                title="Create Employee Policy"
                subtitle="Set up custom attendance policies for individual employees"
                :show-period-filter="false"
            />

            <div class="flex justify-end mb-6">
                <a href="{{ route('admin.employee-policies.index') }}" class="bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 text-gray-800 dark:text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                    <i class="fa-solid fa-arrow-left mr-2"></i>Back to Policies
                </a>
            </div>

            <form method="POST" action="{{ route('admin.employee-policies.store') }}" id="policyForm">
                @csrf

                <!-- Basic Information -->
                <x-section-card title="Basic Information">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="employee_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Employee <span class="text-red-500">*</span></label>
                            <select name="employee_id" id="employee_id" required
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('employee_id') border-red-300 dark:border-red-600 @enderror">
                                <option value="">Select Employee</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" data-email="{{ $employee->email }}">
                                        {{ $employee->name }} ({{ $employee->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('employee_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Policy Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                   class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('name') border-red-300 dark:border-red-600 @enderror"
                                   placeholder="e.g., Remote Work Policy, Flexible Hours">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                            <textarea name="description" id="description" rows="3"
                                      class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                      placeholder="Describe the purpose and scope of this policy...">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </x-section-card>

                <!-- Work Schedule -->
                <x-section-card title="Work Schedule">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="work_start_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Start Time</label>
                            <input type="time" name="work_start_time" id="work_start_time" value="{{ old('work_start_time', '08:00') }}"
                                   class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm">
                        </div>

                        <div>
                            <label for="work_end_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">End Time</label>
                            <input type="time" name="work_end_time" id="work_end_time" value="{{ old('work_end_time', '17:00') }}"
                                   class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm">
                        </div>

                        <div>
                            <label for="work_hours_per_day" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Hours per Day</label>
                            <input type="number" name="work_hours_per_day" id="work_hours_per_day" value="{{ old('work_hours_per_day', 8) }}" min="1" max="24"
                                   class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm">
                        </div>
                    </div>

                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Work Days</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @php
                                $days = ['monday' => 'Monday', 'tuesday' => 'Tuesday', 'wednesday' => 'Wednesday', 'thursday' => 'Thursday', 'friday' => 'Friday', 'saturday' => 'Saturday', 'sunday' => 'Sunday'];
                                $selectedDays = old('work_days', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday']);
                            @endphp
                            @foreach($days as $value => $label)
                                <label class="flex items-center">
                                    <input type="checkbox" name="work_days[]" value="{{ $value }}"
                                           {{ in_array($value, $selectedDays) ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </x-section-card>

                <!-- Attendance Rules -->
                <x-section-card title="Attendance Rules">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="grace_period_minutes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Grace Period (minutes)</label>
                            <input type="number" name="grace_period_minutes" id="grace_period_minutes" value="{{ old('grace_period_minutes', 15) }}" min="0" max="120"
                                   class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Minutes allowed for late arrival without penalty</p>
                        </div>

                        <div>
                            <label for="max_late_minutes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Max Late Minutes</label>
                            <input type="number" name="max_late_minutes" id="max_late_minutes" value="{{ old('max_late_minutes', 120) }}" min="0" max="480"
                                   class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Maximum late minutes before marked as absent</p>
                        </div>

                        <div>
                            <label for="early_leave_grace_minutes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Early Leave Grace (minutes)</label>
                            <input type="number" name="early_leave_grace_minutes" id="early_leave_grace_minutes" value="{{ old('early_leave_grace_minutes', 15) }}" min="0" max="120"
                                   class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Minutes allowed for early departure</p>
                        </div>

                        <div>
                            <label for="allowed_radius_meters" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Location Check Radius (meters)</label>
                            <input type="number" name="allowed_radius_meters" id="allowed_radius_meters" value="{{ old('allowed_radius_meters', 100) }}" min="0" max="10000"
                                   class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">GPS check tolerance radius</p>
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input type="checkbox" name="require_location_check" value="1" {{ old('require_location_check', true) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Require location check</span>
                            </label>

                            <label class="flex items-center">
                                <input type="checkbox" name="allow_overtime" value="1" {{ old('allow_overtime') ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Allow overtime</span>
                            </label>

                            <label class="flex items-center">
                                <input type="checkbox" name="can_work_from_home" value="1" {{ old('can_work_from_home') ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Allow work from home</span>
                            </label>
                        </div>

                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input type="checkbox" name="flexible_hours" value="1" {{ old('flexible_hours') ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Flexible working hours</span>
                            </label>

                            <label class="flex items-center">
                                <input type="checkbox" name="skip_weekends" value="1" {{ old('skip_weekends', true) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Skip weekends</span>
                            </label>

                            <label class="flex items-center">
                                <input type="checkbox" name="skip_holidays" value="1" {{ old('skip_holidays', true) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Skip holidays</span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6" id="overtimeSettings" style="display: none;">
                        <div>
                            <label for="max_overtime_hours_per_day" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Max Overtime Hours per Day</label>
                            <input type="number" name="max_overtime_hours_per_day" id="max_overtime_hours_per_day" value="{{ old('max_overtime_hours_per_day', 2) }}" min="0" max="12"
                                   class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm">
                        </div>

                        <div>
                            <label for="max_overtime_hours_per_week" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Max Overtime Hours per Week</label>
                            <input type="number" name="max_overtime_hours_per_week" id="max_overtime_hours_per_week" value="{{ old('max_overtime_hours_per_week', 10) }}" min="0" max="60"
                                   class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm">
                        </div>
                    </div>
                </x-section-card>

                <!-- Leave Policies -->
                <x-section-card title="Leave Policies">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="annual_leave_days" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Annual Leave Days</label>
                            <input type="number" name="annual_leave_days" id="annual_leave_days" value="{{ old('annual_leave_days', 12) }}" min="0" max="365"
                                   class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm">
                        </div>

                        <div>
                            <label for="sick_leave_days" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sick Leave Days</label>
                            <input type="number" name="sick_leave_days" id="sick_leave_days" value="{{ old('sick_leave_days', 14) }}" min="0" max="365"
                                   class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm">
                        </div>

                        <div>
                            <label for="personal_leave_days" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Personal Leave Days</label>
                            <input type="number" name="personal_leave_days" id="personal_leave_days" value="{{ old('personal_leave_days', 3) }}" min="0" max="365"
                                   class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm">
                        </div>

                        <div class="flex items-center pt-6">
                            <label class="flex items-center">
                                <input type="checkbox" name="allow_negative_leave_balance" value="1" {{ old('allow_negative_leave_balance') ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Allow negative leave balance</span>
                            </label>
                        </div>
                    </div>
                </x-section-card>

                <!-- Effective Period -->
                <x-section-card title="Effective Period">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="effective_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Effective From</label>
                            <input type="date" name="effective_from" id="effective_from" value="{{ old('effective_from') }}"
                                   class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Leave empty for immediate effect</p>
                        </div>

                        <div>
                            <label for="effective_until" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Effective Until</label>
                            <input type="date" name="effective_until" id="effective_until" value="{{ old('effective_until') }}"
                                   class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Leave empty for ongoing policy</p>
                        </div>
                    </div>
                </x-section-card>

                <!-- Submit -->
                <div class="mt-6 flex items-center justify-end space-x-3">
                    <a href="{{ route('admin.employee-policies.index') }}" class="bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 text-gray-800 dark:text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                        <i class="fa-solid fa-times mr-2"></i>Cancel
                    </a>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                        <i class="fa-solid fa-save mr-2"></i>Create Policy
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Toggle overtime settings visibility
        document.querySelector('input[name="allow_overtime"]').addEventListener('change', function() {
            const overtimeSettings = document.getElementById('overtimeSettings');
            overtimeSettings.style.display = this.checked ? 'grid' : 'none';
        });

        // Initialize overtime settings visibility
        document.addEventListener('DOMContentLoaded', function() {
            const allowOvertimeCheckbox = document.querySelector('input[name="allow_overtime"]');
            const overtimeSettings = document.getElementById('overtimeSettings');
            overtimeSettings.style.display = allowOvertimeCheckbox.checked ? 'grid' : 'none';
        });

        // Auto-populate policy name based on selected employee
        document.getElementById('employee_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const employeeName = selectedOption.text.split(' (')[0]; // Get name before email
            const policyNameInput = document.getElementById('name');

            if (employeeName && !policyNameInput.value) {
                policyNameInput.value = `${employeeName} Policy`;
            }
        });
    </script>
</x-admin-layout>