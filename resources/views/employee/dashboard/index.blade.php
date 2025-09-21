<x-employee-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-6">Employee Dashboard</h1>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-blue-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-blue-800">Today's Status</h3>
                            <p class="text-2xl font-bold text-blue-600">{{ $todayStatus ?? 'Not Checked In' }}</p>
                        </div>
                        
                        <div class="bg-green-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-green-800">This Month Hours</h3>
                            <p class="text-3xl font-bold text-green-600">{{ $monthlyHours ?? 0 }}h</p>
                        </div>
                        
                        <div class="bg-purple-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-purple-800">Last Payroll</h3>
                            <p class="text-2xl font-bold text-purple-600">Rp {{ number_format($lastPayroll ?? 0) }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-8">
                        <h2 class="text-xl font-semibold mb-4">Quick Actions</h2>
                        <div class="flex space-x-4">
                            <a href="{{ route('employee.attendance.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                View Attendance
                            </a>
                            <a href="{{ route('employee.payroll.index') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                View Payroll
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-employee-layout>
