<x-admin-layout>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Attendance Management</h1>
            <div class="flex items-center space-x-4">
                <button id="prev-month" class="p-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg" data-month="{{ $month }}">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <span id="current-month" class="text-lg font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}</span>
                <button id="next-month" class="p-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg" data-month="{{ $month }}">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>
        </div>

        <!-- Legend -->
        <div class="mb-4 flex flex-wrap gap-4 text-sm">
            <div class="flex items-center">
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 mr-2">✓</span>
                <span class="text-gray-700 dark:text-gray-300">Present</span>
            </div>
            <div class="flex items-center">
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 mr-2">L</span>
                <span class="text-gray-700 dark:text-gray-300">Late</span>
            </div>
            <div class="flex items-center">
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 mr-2">✗</span>
                <span class="text-gray-700 dark:text-gray-300">Absent</span>
            </div>
            <div class="flex items-center">
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 mr-2">◐</span>
                <span class="text-gray-700 dark:text-gray-300">Partial</span>
            </div>
            <div class="flex items-center">
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 mr-2">C</span>
                <span class="text-gray-700 dark:text-gray-300">Leave</span>
            </div>
            <div class="flex items-center">
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-50 text-gray-400 dark:bg-gray-800 dark:text-gray-600 mr-2">-</span>
                <span class="text-gray-700 dark:text-gray-300">No Record</span>
            </div>
        </div>

        <div class="space-y-8">
            @php
                $branchIds = $branches->pluck('id')->toArray();
            @endphp

            @foreach ($branches as $branch)
                @php
                    $branchEmployees = $employeesByBranch->get($branch->id, collect());
                @endphp
                @if($branchEmployees->count() > 0)
                    <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fa-solid fa-building mr-2 text-blue-600 dark:text-blue-400"></i>
                            {{ $branch->name }}
                            <span class="ml-2 text-sm font-normal text-gray-500 dark:text-gray-400">
                                ({{ $branchEmployees->count() }} {{ $branchEmployees->count() === 1 ? 'employee' : 'employees' }})
                            </span>
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white dark:bg-gray-800 rounded-lg">
                                <thead>
                                    <tr class="bg-gray-100 dark:bg-gray-700">
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Employee</th>
                                        @for ($day = 1; $day <= \Carbon\Carbon::createFromFormat('Y-m', $month)->daysInMonth; $day++)
                                            <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ $day }}</th>
                                        @endfor
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($branchEmployees as $employee)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('admin.attendance.employee', [$employee->id, 'month' => $month]) }}"
                                                   class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                    {{ $employee->name }}
                                                </a>
                                            </td>
                                            @for ($day = 1; $day <= \Carbon\Carbon::createFromFormat('Y-m', $month)->daysInMonth; $day++)
                                                @php
                                                    $date = \Carbon\Carbon::createFromFormat('Y-m', $month)->setDay($day)->format('Y-m-d');
                                                    $dateWithTime = \Carbon\Carbon::createFromFormat('Y-m', $month)->setDay($day)->format('Y-m-d 00:00:00');
                                                    $attendance = $attendances->get($employee->id, collect())->get($dateWithTime);
                                                @endphp
                                                <td class="px-2 py-4 text-center">
                                                    @if($attendance)
                                                        @if($attendance->status === 'present')
                                                            <a href="{{ route('admin.attendance.show', $attendance) }}"
                                                               class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 hover:bg-green-200 dark:hover:bg-green-800 transition-colors" title="Present - Click for details">
                                                                ✓
                                                            </a>
                                                        @elseif($attendance->status === 'late')
                                                            <a href="{{ route('admin.attendance.show', $attendance) }}"
                                                               class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 hover:bg-yellow-200 dark:hover:bg-yellow-800 transition-colors" title="Late - Click for details">
                                                                L
                                                            </a>
                                                        @elseif($attendance->status === 'absent')
                                                            <a href="{{ route('admin.attendance.show', $attendance) }}"
                                                               class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 hover:bg-red-200 dark:hover:bg-red-800 transition-colors" title="Absent - Click for details">
                                                                ✗
                                                            </a>
                                                        @elseif($attendance->status === 'partial')
                                                            <a href="{{ route('admin.attendance.show', $attendance) }}"
                                                               class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 hover:bg-blue-200 dark:hover:bg-blue-800 transition-colors" title="Partial - Click for details">
                                                                ◐
                                                            </a>
                                                        @elseif($attendance->status === 'leave')
                                                            <a href="{{ route('admin.attendance.show', $attendance) }}"
                                                               class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors" title="Leave - Click for details">
                                                                C
                                                            </a>
                                                        @else
                                                            <a href="{{ route('admin.attendance.show', $attendance) }}"
                                                               class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors" title="Unknown - Click for details">
                                                                ?
                                                            </a>
                                                        @endif
                                                    @else
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-50 text-gray-400 dark:bg-gray-800 dark:text-gray-600" title="No Record">
                                                            -
                                                        </span>
                                                    @endif
                                                </td>
                                            @endfor
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            @endforeach

            @php
                $employeesWithoutBranch = $employeesByBranch->get('no-branch', collect());
            @endphp
            @if($employeesWithoutBranch->count() > 0)
                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <i class="fa-solid fa-users mr-2 text-orange-600 dark:text-orange-400"></i>
                        Employees Without Branch
                        <span class="ml-2 text-sm font-normal text-gray-500 dark:text-gray-400">
                            ({{ $employeesWithoutBranch->count() }} {{ $employeesWithoutBranch->count() === 1 ? 'employee' : 'employees' }})
                        </span>
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-800 rounded-lg">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-gray-700">
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Employee</th>
                                    @for ($day = 1; $day <= \Carbon\Carbon::createFromFormat('Y-m', $month)->daysInMonth; $day++)
                                        <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ $day }}</th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($employeesWithoutBranch as $employee)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('admin.attendance.employee', [$employee->id, 'month' => $month]) }}"
                                               class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                {{ $employee->name }}
                                            </a>
                                        </td>
                                        @for ($day = 1; $day <= \Carbon\Carbon::createFromFormat('Y-m', $month)->daysInMonth; $day++)
                                            @php
                                                $date = \Carbon\Carbon::createFromFormat('Y-m', $month)->setDay($day)->format('Y-m-d');
                                                $dateWithTime = \Carbon\Carbon::createFromFormat('Y-m', $month)->setDay($day)->format('Y-m-d 00:00:00');
                                                $attendance = $attendances->get($employee->id, collect())->get($dateWithTime);
                                            @endphp
                                            <td class="px-2 py-4 text-center">
                                                @if($attendance)
                                                    @if($attendance->status === 'present')
                                                        <a href="{{ route('admin.attendance.show', $attendance) }}"
                                                           class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 hover:bg-green-200 dark:hover:bg-green-800 transition-colors" title="Present - Click for details">
                                                            ✓
                                                        </a>
                                                    @elseif($attendance->status === 'late')
                                                        <a href="{{ route('admin.attendance.show', $attendance) }}"
                                                           class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 hover:bg-yellow-200 dark:hover:bg-yellow-800 transition-colors" title="Late - Click for details">
                                                            L
                                                        </a>
                                                    @elseif($attendance->status === 'absent')
                                                        <a href="{{ route('admin.attendance.show', $attendance) }}"
                                                           class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 hover:bg-red-200 dark:hover:bg-red-800 transition-colors" title="Absent - Click for details">
                                                            ✗
                                                        </a>
                                                    @elseif($attendance->status === 'partial')
                                                        <a href="{{ route('admin.attendance.show', $attendance) }}"
                                                           class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 hover:bg-blue-200 dark:hover:bg-blue-800 transition-colors" title="Partial - Click for details">
                                                            ◐
                                                        </a>
                                                    @elseif($attendance->status === 'leave')
                                                        <a href="{{ route('admin.attendance.show', $attendance) }}"
                                                           class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors" title="Leave - Click for details">
                                                            C
                                                        </a>
                                                    @else
                                                        <a href="{{ route('admin.attendance.show', $attendance) }}"
                                                           class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors" title="Unknown - Click for details">
                                                            ?
                                                        </a>
                                                    @endif
                                                @else
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-50 text-gray-400 dark:bg-gray-800 dark:text-gray-600" title="No Record">
                                                        -
                                                    </span>
                                                @endif
                                            </td>
                                        @endfor
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
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Recent Attendance Photos</h2>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach($attendancesWithPhotos as $attendance)
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                            <!-- Employee Info -->
                            <div class="flex items-center mb-3">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                        <span class="text-white text-sm font-medium">
                                            {{ substr($attendance->user->name, 0, 1) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $attendance->user->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $attendance->attendance_date->format('M d, Y') }}</p>
                                </div>
                            </div>

                            <!-- Attendance Summary -->
                            <div class="mb-3">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-gray-600 dark:text-gray-400">Status:</span>
                                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-medium
                                        @if($attendance->status === 'present') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        @elseif($attendance->status === 'late') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                        @elseif($attendance->status === 'absent') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 @endif">
                                        {{ ucfirst($attendance->status) }}
                                    </span>
                                </div>
                                @if($attendance->check_in_time)
                                    <div class="flex items-center justify-between text-xs mt-1">
                                        <span class="text-gray-600 dark:text-gray-400">Check-in:</span>
                                        <span class="text-gray-900 dark:text-white">{{ $attendance->check_in_time->format('H:i') }}</span>
                                    </div>
                                @endif
                                @if($attendance->check_out_time)
                                    <div class="flex items-center justify-between text-xs mt-1">
                                        <span class="text-gray-600 dark:text-gray-400">Check-out:</span>
                                        <span class="text-gray-900 dark:text-white">{{ $attendance->check_out_time->format('H:i') }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Photos -->
                            <div class="grid grid-cols-2 gap-2">
                                @if($attendance->check_in_photo)
                                    <div class="aspect-square">
                                        <a href="{{ asset('storage/' . $attendance->check_in_photo) }}" 
                                           data-fancybox="gallery-{{ $attendance->id }}" 
                                           data-caption="{{ $attendance->user->name }} - Check-in ({{ $attendance->attendance_date->format('M d, Y') }})">
                                            <img src="{{ asset('storage/' . $attendance->check_in_photo) }}"
                                                 alt="Check-in photo"
                                                 class="w-full h-full object-cover rounded cursor-pointer hover:opacity-90 transition-opacity">
                                        </a>
                                        <p class="text-xs text-center text-gray-500 dark:text-gray-400 mt-1">Check-in</p>
                                    </div>
                                @else
                                    <div class="aspect-square bg-gray-200 dark:bg-gray-600 rounded flex items-center justify-center">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">No check-in photo</span>
                                    </div>
                                @endif

                                @if($attendance->check_out_photo)
                                    <div class="aspect-square">
                                        <a href="{{ asset('storage/' . $attendance->check_out_photo) }}" 
                                           data-fancybox="gallery-{{ $attendance->id }}" 
                                           data-caption="{{ $attendance->user->name }} - Check-out ({{ $attendance->attendance_date->format('M d, Y') }})">
                                            <img src="{{ asset('storage/' . $attendance->check_out_photo) }}"
                                                 alt="Check-out photo"
                                                 class="w-full h-full object-cover rounded cursor-pointer hover:opacity-90 transition-opacity">
                                        </a>
                                        <p class="text-xs text-center text-gray-500 dark:text-gray-400 mt-1">Check-out</p>
                                    </div>
                                @else
                                    <div class="aspect-square bg-gray-200 dark:bg-gray-600 rounded flex items-center justify-center">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">No check-out photo</span>
                                    </div>
                                @endif
                            </div>

                            <!-- View Details Link -->
                            <div class="mt-3 text-center">
                                <a href="{{ route('admin.attendance.show', $attendance) }}"
                                   class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-xs font-medium">
                                    View Details →
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <div class="mt-6 flex justify-end">
            <a href="{{ route('admin.attendance.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Add Attendance Record
            </a>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('js/attendance.js') }}"></script>
    @endpush
</x-admin-layout>