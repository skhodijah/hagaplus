<x-admin-layout>
    <div class="space-y-6">
        <!-- Header & Controls -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Attendance Overview</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Monitor employee attendance and timesheets.</p>
            </div>
            
            <div class="flex items-center gap-3 bg-gray-50 dark:bg-gray-700/50 p-1.5 rounded-xl border border-gray-200 dark:border-gray-600">
                <button id="prev-month" class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-white dark:hover:bg-gray-600 rounded-lg transition-all shadow-sm hover:shadow-md" data-month="{{ $month }}">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <div class="px-4 py-1 font-semibold text-gray-700 dark:text-gray-200 min-w-[140px] text-center">
                    {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}
                </div>
                <button id="next-month" class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-white dark:hover:bg-gray-600 rounded-lg transition-all shadow-sm hover:shadow-md" data-month="{{ $month }}">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('admin.attendance.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-lg shadow-indigo-200 dark:shadow-none transition-all hover:-translate-y-0.5">
                    <i class="fa-solid fa-plus mr-2"></i> Record Attendance
                </a>
            </div>
        </div>

        <!-- Stats Overview -->
        @php
            // Calculate simple stats for the view
            $totalPresent = 0;
            $totalLate = 0;
            $totalAbsent = 0;
            $totalLeave = 0;
            
            // Flatten attendances to count
            $allAttendances = $attendances->flatten();
            foreach($allAttendances as $att) {
                if($att->status === 'present') $totalPresent++;
                elseif($att->status === 'late') $totalLate++;
                elseif($att->status === 'absent') $totalAbsent++;
                elseif($att->status === 'leave') $totalLeave++;
            }
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">On Time</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalPresent }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-green-50 dark:bg-green-900/30 flex items-center justify-center text-green-600 dark:text-green-400">
                    <i class="fa-solid fa-check"></i>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Late</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalLate }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-yellow-50 dark:bg-yellow-900/30 flex items-center justify-center text-yellow-600 dark:text-yellow-400">
                    <i class="fa-solid fa-clock"></i>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Absent</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalAbsent }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-red-50 dark:bg-red-900/30 flex items-center justify-center text-red-600 dark:text-red-400">
                    <i class="fa-solid fa-xmark"></i>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">On Leave</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalLeave }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
                    <i class="fa-solid fa-calendar-minus"></i>
                </div>
            </div>
        </div>

        <!-- Legend -->
        <div class="flex flex-wrap gap-3 text-xs font-medium text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-800 p-3 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm">
            <span class="flex items-center"><span class="w-2.5 h-2.5 rounded-full bg-green-500 mr-1.5"></span> Present</span>
            <span class="flex items-center"><span class="w-2.5 h-2.5 rounded-full bg-yellow-500 mr-1.5"></span> Late</span>
            <span class="flex items-center"><span class="w-2.5 h-2.5 rounded-full bg-red-500 mr-1.5"></span> Absent</span>
            <span class="flex items-center"><span class="w-2.5 h-2.5 rounded-full bg-blue-500 mr-1.5"></span> Partial</span>
            <span class="flex items-center"><span class="w-2.5 h-2.5 rounded-full bg-gray-400 mr-1.5"></span> Leave</span>
            <span class="flex items-center"><span class="w-2.5 h-2.5 rounded-full border border-gray-300 mr-1.5"></span> No Record</span>
        </div>

        <!-- Attendance Table Section -->
        <div class="space-y-6">
            @foreach ($branches as $branch)
                @php
                    $branchEmployees = $employeesByBranch->get($branch->id, collect());
                @endphp
                @if($branchEmployees->count() > 0)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 flex items-center justify-between">
                            <h3 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2">
                                <i class="fa-solid fa-building text-indigo-500"></i>
                                {{ $branch->name }}
                            </h3>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                {{ $branchEmployees->count() }} Staff
                            </span>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="text-xs text-gray-500 uppercase bg-gray-50 dark:bg-gray-700/50 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 sticky left-0 z-10 bg-gray-50 dark:bg-gray-800 min-w-[200px] shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)]">
                                            Employee
                                        </th>
                                        @for ($day = 1; $day <= \Carbon\Carbon::createFromFormat('Y-m', $month)->daysInMonth; $day++)
                                            <th scope="col" class="px-1 py-3 text-center min-w-[36px]">
                                                {{ $day }}
                                            </th>
                                        @endfor
                                        <th scope="col" class="px-4 py-3 text-center min-w-[80px] bg-gray-50 dark:bg-gray-800 sticky right-0 shadow-[-2px_0_5px_-2px_rgba(0,0,0,0.1)]">
                                            Summary
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                    @foreach ($branchEmployees as $employee)
                                        @php
                                            $empPresent = 0;
                                            $empLate = 0;
                                            $empAbsent = 0;
                                            $empLeave = 0;
                                        @endphp
                                        <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
                                            <td class="px-6 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap sticky left-0 z-10 bg-white dark:bg-gray-800 group-hover:bg-gray-50 dark:group-hover:bg-gray-700/50 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)]">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold text-xs">
                                                        {{ substr($employee->name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <a href="{{ route('admin.attendance.employee', [$employee->id, 'month' => $month]) }}" class="hover:text-indigo-600 transition-colors">
                                                            {{ $employee->name }}
                                                        </a>
                                                        <div class="text-[10px] text-gray-500 uppercase tracking-wider">
                                                            {{ $employee->employee->position->name ?? '-' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            @for ($day = 1; $day <= \Carbon\Carbon::createFromFormat('Y-m', $month)->daysInMonth; $day++)
                                                @php
                                                    $dateWithTime = \Carbon\Carbon::createFromFormat('Y-m', $month)->setDay($day)->format('Y-m-d 00:00:00');
                                                    $attendance = $attendances->get($employee->id, collect())->get($dateWithTime);
                                                    
                                                    $statusColor = 'bg-gray-100 dark:bg-gray-700'; // Default/Empty
                                                    $statusIcon = '';
                                                    $tooltip = 'No Record';
                                                    
                                                    if($attendance) {
                                                        if($attendance->status === 'present') {
                                                            $statusColor = 'bg-green-500';
                                                            $tooltip = 'Present: ' . ($attendance->check_in_time ? $attendance->check_in_time->format('H:i') : '-');
                                                            $empPresent++;
                                                        } elseif($attendance->status === 'late') {
                                                            $statusColor = 'bg-yellow-500';
                                                            $tooltip = 'Late: ' . ($attendance->check_in_time ? $attendance->check_in_time->format('H:i') : '-');
                                                            $empLate++;
                                                        } elseif($attendance->status === 'absent') {
                                                            $statusColor = 'bg-red-500';
                                                            $tooltip = 'Absent';
                                                            $empAbsent++;
                                                        } elseif($attendance->status === 'partial') {
                                                            $statusColor = 'bg-blue-500';
                                                            $tooltip = 'Partial';
                                                            $empPresent++; // Count as present-ish
                                                        } elseif($attendance->status === 'leave') {
                                                            $statusColor = 'bg-gray-400';
                                                            $tooltip = 'On Leave';
                                                            $empLeave++;
                                                        }
                                                    }
                                                @endphp
                                                <td class="px-1 py-3 text-center">
                                                    @if($attendance)
                                                        @if($attendance->id)
                                                            <a href="{{ route('admin.attendance.show', $attendance) }}" 
                                                               class="inline-block w-2.5 h-2.5 rounded-full {{ $statusColor }} hover:scale-150 transition-transform cursor-pointer" 
                                                               title="{{ $tooltip }}">
                                                            </a>
                                                        @else
                                                            {{-- Virtual attendance (e.g. from leave) without ID --}}
                                                            <span class="inline-block w-2.5 h-2.5 rounded-full {{ $statusColor }} cursor-default" 
                                                                  title="{{ $tooltip }}">
                                                            </span>
                                                        @endif
                                                    @else
                                                        <span class="inline-block w-1.5 h-1.5 rounded-full bg-gray-200 dark:bg-gray-700" title="No Record"></span>
                                                    @endif
                                                </td>
                                            @endfor
                                            <td class="px-4 py-3 text-center sticky right-0 bg-white dark:bg-gray-800 group-hover:bg-gray-50 dark:group-hover:bg-gray-700/50 shadow-[-2px_0_5px_-2px_rgba(0,0,0,0.1)]">
                                                <div class="flex items-center justify-center gap-2 text-xs">
                                                    <span class="text-green-600 font-bold" title="Present">{{ $empPresent }}</span>
                                                    <span class="text-gray-300">|</span>
                                                    <span class="text-yellow-600 font-bold" title="Late">{{ $empLate }}</span>
                                                    <span class="text-gray-300">|</span>
                                                    <span class="text-red-600 font-bold" title="Absent">{{ $empAbsent }}</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            @endforeach
            
            <!-- Employees Without Branch -->
            @php
                $employeesWithoutBranch = $employeesByBranch->get('no-branch', collect());
            @endphp
            @if($employeesWithoutBranch->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2">
                            <i class="fa-solid fa-users text-orange-500"></i>
                            Unassigned Staff
                        </h3>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                            {{ $employeesWithoutBranch->count() }} Staff
                        </span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-gray-500 uppercase bg-gray-50 dark:bg-gray-700/50 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3 sticky left-0 z-10 bg-gray-50 dark:bg-gray-800 min-w-[200px] shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)]">
                                        Employee
                                    </th>
                                    @for ($day = 1; $day <= \Carbon\Carbon::createFromFormat('Y-m', $month)->daysInMonth; $day++)
                                        <th scope="col" class="px-1 py-3 text-center min-w-[36px]">
                                            {{ $day }}
                                        </th>
                                    @endfor
                                    <th scope="col" class="px-4 py-3 text-center min-w-[80px] bg-gray-50 dark:bg-gray-800 sticky right-0 shadow-[-2px_0_5px_-2px_rgba(0,0,0,0.1)]">
                                        Summary
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach ($employeesWithoutBranch as $employee)
                                    @php
                                        $empPresent = 0;
                                        $empLate = 0;
                                        $empAbsent = 0;
                                        $empLeave = 0;
                                    @endphp
                                    <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
                                        <td class="px-6 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap sticky left-0 z-10 bg-white dark:bg-gray-800 group-hover:bg-gray-50 dark:group-hover:bg-gray-700/50 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)]">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-orange-100 dark:bg-orange-900/50 flex items-center justify-center text-orange-600 dark:text-orange-400 font-bold text-xs">
                                                    {{ substr($employee->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <a href="{{ route('admin.attendance.employee', [$employee->id, 'month' => $month]) }}" class="hover:text-indigo-600 transition-colors">
                                                        {{ $employee->name }}
                                                    </a>
                                                    <div class="text-[10px] text-gray-500 uppercase tracking-wider">
                                                        {{ $employee->employee->position->name ?? '-' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        @for ($day = 1; $day <= \Carbon\Carbon::createFromFormat('Y-m', $month)->daysInMonth; $day++)
                                            @php
                                                $dateWithTime = \Carbon\Carbon::createFromFormat('Y-m', $month)->setDay($day)->format('Y-m-d 00:00:00');
                                                $attendance = $attendances->get($employee->id, collect())->get($dateWithTime);
                                                
                                                $statusColor = 'bg-gray-100 dark:bg-gray-700';
                                                $tooltip = 'No Record';
                                                
                                                if($attendance) {
                                                    if($attendance->status === 'present') {
                                                        $statusColor = 'bg-green-500';
                                                        $tooltip = 'Present';
                                                        $empPresent++;
                                                    } elseif($attendance->status === 'late') {
                                                        $statusColor = 'bg-yellow-500';
                                                        $tooltip = 'Late';
                                                        $empLate++;
                                                    } elseif($attendance->status === 'absent') {
                                                        $statusColor = 'bg-red-500';
                                                        $tooltip = 'Absent';
                                                        $empAbsent++;
                                                    } elseif($attendance->status === 'partial') {
                                                        $statusColor = 'bg-blue-500';
                                                        $tooltip = 'Partial';
                                                        $empPresent++;
                                                    } elseif($attendance->status === 'leave') {
                                                        $statusColor = 'bg-gray-400';
                                                        $tooltip = 'On Leave';
                                                        $empLeave++;
                                                    }
                                                }
                                            @endphp
                                            <td class="px-1 py-3 text-center">
                                                @if($attendance)
                                                    @if($attendance->id)
                                                        <a href="{{ route('admin.attendance.show', $attendance) }}" 
                                                           class="inline-block w-2.5 h-2.5 rounded-full {{ $statusColor }} hover:scale-150 transition-transform cursor-pointer" 
                                                           title="{{ $tooltip }}">
                                                        </a>
                                                    @else
                                                        {{-- Virtual attendance (e.g. from leave) without ID --}}
                                                        <span class="inline-block w-2.5 h-2.5 rounded-full {{ $statusColor }} cursor-default" 
                                                              title="{{ $tooltip }}">
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-gray-200 dark:bg-gray-700" title="No Record"></span>
                                                @endif
                                            </td>
                                        @endfor
                                        <td class="px-4 py-3 text-center sticky right-0 bg-white dark:bg-gray-800 group-hover:bg-gray-50 dark:group-hover:bg-gray-700/50 shadow-[-2px_0_5px_-2px_rgba(0,0,0,0.1)]">
                                            <div class="flex items-center justify-center gap-2 text-xs">
                                                <span class="text-green-600 font-bold" title="Present">{{ $empPresent }}</span>
                                                <span class="text-gray-300">|</span>
                                                <span class="text-yellow-600 font-bold" title="Late">{{ $empLate }}</span>
                                                <span class="text-gray-300">|</span>
                                                <span class="text-red-600 font-bold" title="Absent">{{ $empAbsent }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        <!-- Selfie Gallery Section -->
        @if($attendancesWithPhotos->count() > 0)
        <div class="mt-8">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <i class="fa-solid fa-camera text-indigo-500"></i> Recent Activity Photos
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($attendancesWithPhotos as $attendance)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow">
                        <div class="p-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-600 dark:text-gray-300 font-bold">
                                {{ substr($attendance->user->name, 0, 1) }}
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 dark:text-white text-sm">{{ $attendance->user->name }}</h4>
                                <p class="text-xs text-gray-500">{{ $attendance->attendance_date->format('M d, Y') }}</p>
                            </div>
                        </div>
                        
                        <div class="p-4 grid grid-cols-2 gap-3">
                            <div class="space-y-2">
                                <div class="aspect-square rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-700 relative group">
                                    @if($attendance->check_in_photo)
                                        <a href="{{ asset('storage/' . $attendance->check_in_photo) }}" data-fancybox="gallery-{{ $attendance->id }}" data-caption="Check-in">
                                            <img src="{{ asset('storage/' . $attendance->check_in_photo) }}" class="w-full h-full object-cover transition-transform group-hover:scale-110">
                                        </a>
                                        <div class="absolute bottom-0 left-0 right-0 bg-black/50 text-white text-[10px] py-1 text-center backdrop-blur-sm">
                                            IN: {{ $attendance->check_in_time ? $attendance->check_in_time->format('H:i') : '-' }}
                                        </div>
                                    @else
                                        <div class="flex items-center justify-center h-full text-gray-400 text-xs">No Photo</div>
                                    @endif
                                </div>
                            </div>
                            <div class="space-y-2">
                                <div class="aspect-square rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-700 relative group">
                                    @if($attendance->check_out_photo)
                                        <a href="{{ asset('storage/' . $attendance->check_out_photo) }}" data-fancybox="gallery-{{ $attendance->id }}" data-caption="Check-out">
                                            <img src="{{ asset('storage/' . $attendance->check_out_photo) }}" class="w-full h-full object-cover transition-transform group-hover:scale-110">
                                        </a>
                                        <div class="absolute bottom-0 left-0 right-0 bg-black/50 text-white text-[10px] py-1 text-center backdrop-blur-sm">
                                            OUT: {{ $attendance->check_out_time ? $attendance->check_out_time->format('H:i') : '-' }}
                                        </div>
                                    @else
                                        <div class="flex items-center justify-center h-full text-gray-400 text-xs">No Photo</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="px-4 pb-4 pt-0">
                             <a href="{{ route('admin.attendance.show', $attendance) }}" class="block w-full py-2 text-center text-xs font-medium text-indigo-600 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition-colors">
                                View Details
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    @push('scripts')
    <script src="{{ asset('js/attendance.js') }}"></script>
    @endpush
</x-admin-layout>