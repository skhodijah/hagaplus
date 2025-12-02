<x-admin-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Attendance Details</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Record for <span class="font-medium text-gray-900 dark:text-white">{{ $attendance->user->name }}</span> on {{ $attendance->attendance_date->format('F d, Y') }}
                </p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.attendance.edit', $attendance) }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-lg shadow-indigo-200 dark:shadow-none transition-all hover:-translate-y-0.5">
                    <i class="fa-solid fa-pen-to-square mr-2"></i> Edit Record
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Status & Timing Card -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                        <i class="fa-solid fa-clock text-indigo-500"></i> Timing & Status
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Check In/Out Visual -->
                        <div class="relative flex flex-col justify-between h-full min-h-[160px] pl-8 border-l-2 border-gray-100 dark:border-gray-700 space-y-8">
                            <div class="relative">
                                <div class="absolute -left-[39px] top-0 w-5 h-5 rounded-full border-4 border-white dark:border-gray-800 bg-green-500"></div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Check In</p>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-2xl font-bold text-gray-900 dark:text-white">
                                        {{ $attendance->check_in_time ? $attendance->check_in_time->format('H:i') : '--:--' }}
                                    </span>
                                    @if($attendance->check_in_method)
                                        <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-500">{{ ucfirst($attendance->check_in_method) }}</span>
                                    @endif
                                </div>
                                @if($attendance->late_minutes > 0)
                                    <p class="text-xs text-red-500 mt-1 font-medium">
                                        <i class="fa-solid fa-triangle-exclamation mr-1"></i> Late by {{ $attendance->late_minutes }} mins
                                    </p>
                                @endif
                            </div>

                            <div class="relative">
                                <div class="absolute -left-[39px] top-0 w-5 h-5 rounded-full border-4 border-white dark:border-gray-800 {{ $attendance->check_out_time ? 'bg-red-500' : 'bg-gray-300' }}"></div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Check Out</p>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-2xl font-bold text-gray-900 dark:text-white">
                                        {{ $attendance->check_out_time ? $attendance->check_out_time->format('H:i') : '--:--' }}
                                    </span>
                                    @if($attendance->check_out_method)
                                        <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-500">{{ ucfirst($attendance->check_out_method) }}</span>
                                    @endif
                                </div>
                                @if($attendance->early_checkout_minutes > 0)
                                    <p class="text-xs text-yellow-600 mt-1 font-medium">
                                        <i class="fa-solid fa-person-walking-arrow-right mr-1"></i> Left early by {{ $attendance->early_checkout_minutes }} mins
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Stats Grid -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 dark:bg-gray-700/30 p-4 rounded-xl">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Work Duration</p>
                                <p class="text-lg font-bold text-gray-900 dark:text-white">
                                    {{ $attendance->work_duration ? floor($attendance->work_duration / 60) . 'h ' . ($attendance->work_duration % 60) . 'm' : '-' }}
                                </p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700/30 p-4 rounded-xl">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Overtime</p>
                                <p class="text-lg font-bold text-indigo-600 dark:text-indigo-400">
                                    {{ $attendance->overtime_duration ? floor($attendance->overtime_duration / 60) . 'h ' . ($attendance->overtime_duration % 60) . 'm' : '-' }}
                                </p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700/30 p-4 rounded-xl">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Total Status</p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1
                                    @if($attendance->status === 'present') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                    @elseif($attendance->status === 'late') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                    @elseif($attendance->status === 'absent') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400 @endif">
                                    {{ ucfirst($attendance->status) }}
                                </span>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700/30 p-4 rounded-xl">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Break Time</p>
                                <p class="text-lg font-bold text-gray-900 dark:text-white">
                                    {{ $attendance->break_duration ? $attendance->break_duration . 'm' : '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Photos Section -->
                @if($attendance->check_in_photo || $attendance->check_out_photo)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                            <i class="fa-solid fa-camera text-indigo-500"></i> Activity Photos
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if($attendance->check_in_photo)
                                <div class="space-y-3">
                                    <div class="aspect-video rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-700 relative group shadow-sm border border-gray-200 dark:border-gray-600">
                                        <a href="{{ asset('storage/' . $attendance->check_in_photo) }}" data-fancybox="gallery" data-caption="Check-in Photo">
                                            <img src="{{ asset('storage/' . $attendance->check_in_photo) }}" class="w-full h-full object-cover transition-transform group-hover:scale-105">
                                        </a>
                                        <div class="absolute bottom-0 left-0 right-0 bg-black/60 text-white text-xs py-2 px-3 backdrop-blur-sm flex justify-between items-center">
                                            <span class="font-medium">Check In</span>
                                            <span class="opacity-80">{{ $attendance->check_in_time ? $attendance->check_in_time->format('H:i') : '' }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($attendance->check_out_photo)
                                <div class="space-y-3">
                                    <div class="aspect-video rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-700 relative group shadow-sm border border-gray-200 dark:border-gray-600">
                                        <a href="{{ asset('storage/' . $attendance->check_out_photo) }}" data-fancybox="gallery" data-caption="Check-out Photo">
                                            <img src="{{ asset('storage/' . $attendance->check_out_photo) }}" class="w-full h-full object-cover transition-transform group-hover:scale-105">
                                        </a>
                                        <div class="absolute bottom-0 left-0 right-0 bg-black/60 text-white text-xs py-2 px-3 backdrop-blur-sm flex justify-between items-center">
                                            <span class="font-medium">Check Out</span>
                                            <span class="opacity-80">{{ $attendance->check_out_time ? $attendance->check_out_time->format('H:i') : '' }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar Info -->
            <div class="space-y-6">
                <!-- Employee Card -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-14 h-14 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold text-xl">
                            {{ substr($attendance->user->name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 dark:text-white">{{ $attendance->user->name }}</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $attendance->user->email }}</p>
                            <p class="text-xs text-indigo-600 dark:text-indigo-400 font-medium mt-0.5">{{ $attendance->user->employee->position->name ?? 'No Position' }}</p>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-100 dark:border-gray-700 pt-4 space-y-3">
                        @if($attendance->notes)
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Notes</p>
                                <p class="text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg italic">
                                    "{{ $attendance->notes }}"
                                </p>
                            </div>
                        @endif

                        @if($attendance->approver)
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Approved By</p>
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-xs font-bold text-gray-600 dark:text-gray-300">
                                        {{ substr($attendance->approver->name, 0, 1) }}
                                    </div>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ $attendance->approver->name }}</span>
                                </div>
                                @if($attendance->approved_at)
                                    <p class="text-xs text-gray-400 mt-1 ml-8">{{ $attendance->approved_at->format('d M Y, H:i') }}</p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Danger Zone -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-red-100 dark:border-red-900/30 p-6">
                    <h3 class="text-sm font-bold text-red-600 dark:text-red-400 mb-4">Danger Zone</h3>
                    <form method="POST" action="{{ route('admin.attendance.destroy', $attendance) }}" onsubmit="return confirm('Are you sure you want to delete this attendance record? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-50 text-red-700 hover:bg-red-100 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/40 rounded-xl transition-colors text-sm font-medium">
                            <i class="fa-solid fa-trash mr-2"></i> Delete Record
                        </button>
                    </form>
                </div>

                <div class="text-center">
                    <a href="{{ route('admin.attendance.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                        <i class="fa-solid fa-arrow-left mr-2"></i> Back to Overview
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>