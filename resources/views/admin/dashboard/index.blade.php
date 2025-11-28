<x-admin-layout>
    <div class="space-y-6">
        <!-- Welcome Header -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">Welcome back, {{ Auth::user()->name }}!</h1>
                    <p class="text-blue-100 mt-1">Here's what's happening with your organization today</p>
                </div>
                <div class="hidden md:block">
                    <div class="text-right">
                        <p class="text-sm text-blue-100">{{ now()->format('l, F j, Y') }}</p>
                        <p class="text-lg font-semibold">{{ now()->format('H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Key Metrics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
            <!-- Total Employees -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Employees</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalEmployees) }}</p>
                        <p class="text-xs text-green-600 mt-1">{{ $activeEmployees }} active</p>
                    </div>
                    <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                        <i class="fa-solid fa-users text-blue-600 dark:text-blue-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Today's Attendance -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Present Today</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($presentToday + $lateToday) }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $attendanceRateToday }}% attendance rate</p>
                    </div>
                    <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                        <i class="fa-solid fa-calendar-check text-green-600 dark:text-green-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Payroll Status -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pending Payroll</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($pendingPayroll) }}</p>
                        <p class="text-xs text-gray-500 mt-1">Rp {{ number_format($totalSalaryAmount, 0, ',', '.') }} paid</p>
                    </div>
                    <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-full">
                        <i class="fa-solid fa-money-bill-wave text-yellow-600 dark:text-yellow-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Branches -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Active Branches</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($activeBranches) }}</p>
                        <p class="text-xs text-gray-500 mt-1">of {{ $totalBranches }} total</p>
                    </div>
                    <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-full">
                        <i class="fa-solid fa-building text-purple-600 dark:text-purple-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Reimbursements -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border-l-4 border-red-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pending Reimbursements</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($pendingReimbursements) }}</p>
                        <p class="text-xs text-gray-500 mt-1">requests awaiting action</p>
                    </div>
                    <div class="p-3 bg-red-100 dark:bg-red-900 rounded-full">
                        <i class="fa-solid fa-receipt text-red-600 dark:text-red-400 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Attendance Trend Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">30-Day Attendance Trend</h3>
                    <div class="flex items-center text-sm">
                        <span class="text-green-600 dark:text-green-400 font-medium">{{ $attendanceChange >= 0 ? '+' : '' }}{{ $attendanceChange }}%</span>
                        <span class="text-gray-500 dark:text-gray-400 ml-1">vs last month</span>
                    </div>
                </div>
                <div class="h-64">
                    <canvas id="attendanceChart"></canvas>
                </div>
            </div>

            <!-- Attendance Status Today -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Today's Attendance Status</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-green-500 rounded-full mr-3"></div>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Present</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $presentToday }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-yellow-500 rounded-full mr-3"></div>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Late</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $lateToday }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-red-500 rounded-full mr-3"></div>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Absent</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $absentToday }}</span>
                    </div>
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Attendance Rate</span>
                            <span class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ $attendanceRateToday }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Department Distribution & Branch Performance -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Department Distribution -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Employee Distribution by Department</h3>
                <div class="space-y-3">
                    @php
                        $totalDeptEmployees = $departmentStats->sum('count');
                    @endphp
                    @foreach($departmentStats as $dept)
                        @php
                            $percentage = $totalDeptEmployees > 0 ? round(($dept['count'] / $totalDeptEmployees) * 100, 1) : 0;
                        @endphp
                        <div class="flex items-center justify-between">
                            <div class="flex items-center flex-1">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 truncate">{{ $dept['department'] }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="w-20 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                                <span class="text-sm font-medium text-gray-900 dark:text-white w-12 text-right">{{ $dept['count'] }}</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400 w-10 text-right">{{ $percentage }}%</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Branch Performance -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Branch Performance Today</h3>
                <div class="space-y-4">
                    @foreach($branchPerformance as $branch)
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $branch['name'] }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $branch['employees'] }} employees</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $branch['present_today'] }}/{{ $branch['employees'] }}</p>
                                <p class="text-xs {{ $branch['attendance_rate'] >= 80 ? 'text-green-600' : ($branch['attendance_rate'] >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ $branch['attendance_rate'] }}% attendance
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Payroll Overview & Recent Activities -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Payroll Overview -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Payroll Overview</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $totalPayroll }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total Payrolls</p>
                    </div>
                    <div class="text-center p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                        <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $pendingPayroll }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Pending</p>
                    </div>
                    <div class="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $paidPayroll }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Paid</p>
                    </div>
                    <div class="text-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                        <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $processedPayroll }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Processed</p>
                    </div>
                </div>
                <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Salary Paid</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($totalSalaryAmount, 0, ',', '.') }}</p>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Recent Activities</h3>
                <div class="space-y-3 max-h-64 overflow-y-auto">
                    @forelse($recentActivities as $activity)
                        <div class="flex items-start space-x-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center
                                    @if($activity['status'] === 'present') bg-green-100 dark:bg-green-900
                                    @elseif($activity['status'] === 'late') bg-yellow-100 dark:bg-yellow-900
                                    @else bg-gray-100 dark:bg-gray-600 @endif">
                                    <i class="fa-solid
                                        @if($activity['status'] === 'present') fa-check text-green-600 dark:text-green-400
                                        @elseif($activity['status'] === 'late') fa-clock text-yellow-600 dark:text-yellow-400
                                        @else fa-user text-gray-600 dark:text-gray-400 @endif text-xs"></i>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                    {{ $activity['employee'] }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $activity['action'] }} • {{ $activity['time'] }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400 text-center py-4">No recent activities</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('admin.employees.create') }}" class="flex flex-col items-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors">
                    <i class="fa-solid fa-user-plus text-blue-600 dark:text-blue-400 text-2xl mb-2"></i>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">Add Employee</span>
                </a>
                <a href="{{ route('admin.attendance.index') }}" class="flex flex-col items-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors">
                    <i class="fa-solid fa-calendar-check text-green-600 dark:text-green-400 text-2xl mb-2"></i>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">Manage Attendance</span>
                </a>
                <a href="{{ route('admin.payroll.create') }}" class="flex flex-col items-center p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg hover:bg-yellow-100 dark:hover:bg-yellow-900/30 transition-colors">
                    <i class="fa-solid fa-money-bill-wave text-yellow-600 dark:text-yellow-400 text-2xl mb-2"></i>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">Process Payroll</span>
                </a>
                <a href="{{ route('admin.branches.create') }}" class="flex flex-col items-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/30 transition-colors">
                    <i class="fa-solid fa-building text-purple-600 dark:text-purple-400 text-2xl mb-2"></i>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">Add Branch</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Chart.js and Dashboard JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/admin/dashboard.js') }}"></script>

    <!-- Pass data to JavaScript -->
    <script>
        window.dashboardData = @json([
            'monthlyAttendance' => $monthlyAttendance,
            'departmentStats' => $departmentStats,
            'branchPerformance' => $branchPerformance
        ]);
    </script>
</x-admin-layout>
