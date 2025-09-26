<x-admin-layout>
    <div class="py-2">
        <x-page-header
            title="Edit Attendance Record"
            subtitle="Update attendance record details"
        />

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <form method="POST" action="{{ route('admin.attendance.update', $attendance) }}">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Employee *</label>
                        <select id="user_id" name="user_id"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('user_id') border-red-500 @enderror"
                                required>
                            <option value="">Select Employee</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ old('user_id', $attendance->user_id) == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="attendance_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Attendance Date *</label>
                        <input type="date" id="attendance_date" name="attendance_date" value="{{ old('attendance_date', $attendance->attendance_date->format('Y-m-d')) }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('attendance_date') border-red-500 @enderror"
                               required>
                        @error('attendance_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="check_in_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Check In Time</label>
                        <input type="time" id="check_in_time" name="check_in_time" value="{{ old('check_in_time', $attendance->check_in_time ? $attendance->check_in_time->format('H:i') : '') }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('check_in_time') border-red-500 @enderror">
                        @error('check_in_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="check_out_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Check Out Time</label>
                        <input type="time" id="check_out_time" name="check_out_time" value="{{ old('check_out_time', $attendance->check_out_time ? $attendance->check_out_time->format('H:i') : '') }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('check_out_time') border-red-500 @enderror">
                        @error('check_out_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status *</label>
                        <select id="status" name="status"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('status') border-red-500 @enderror"
                                required>
                            <option value="present" {{ old('status', $attendance->status) === 'present' ? 'selected' : '' }}>Present</option>
                            <option value="late" {{ old('status', $attendance->status) === 'late' ? 'selected' : '' }}>Late</option>
                            <option value="absent" {{ old('status', $attendance->status) === 'absent' ? 'selected' : '' }}>Absent</option>
                            <option value="partial" {{ old('status', $attendance->status) === 'partial' ? 'selected' : '' }}>Partial</option>
                            <option value="leave" {{ old('status', $attendance->status) === 'leave' ? 'selected' : '' }}>Leave</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes</label>
                        <textarea id="notes" name="notes" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('notes') border-red-500 @enderror">{{ old('notes', $attendance->notes) }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-8 flex items-center justify-end space-x-4">
                    <a href="{{ route('admin.attendance.show', $attendance) }}"
                       class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                        <i class="fa-solid fa-save mr-2"></i>Update Attendance Record
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>