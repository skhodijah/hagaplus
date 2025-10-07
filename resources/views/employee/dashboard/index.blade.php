<x-employee-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-white">
                    <h1 class="text-2xl font-bold mb-6">Employee Dashboard</h1>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        @include('employee.partials.status')

                        <div class="bg-green-50 dark:bg-green-500 p-6 rounded-xl">
                            <h3 class="text-lg font-semibold text-green-800 dark:text-green-100">This Month Hours</h3>
                            <p class="text-3xl font-bold text-green-600 dark:text-white">{{ $monthlyHours ?? 0 }}h
                            </p>
                        </div>

                        <div class="bg-purple-50 dark:bg-purple-500 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-purple-800 dark:text-purple-100">Last Payroll</h3>
                            <p class="text-2xl font-bold text-purple-600 dark:text-white">Rp
                                {{ number_format($lastPayroll ?? 0) }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h2 class="text-xl font-semibold mb-4">Quick Actions</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="flex flex-col space-y-4">
                                <a href="{{ route('employee.attendance.index') }}"
                                    class="bg-blue-500 dark:bg-blue-800 hover:bg-blue-700 dark:hover:bg-blue-500 text-white font-bold py-2 px-4 rounded text-center">
                                    View Attendance History
                                </a>
                                <a href="{{ route('employee.payroll.index') }}"
                                    class="bg-green-500 dark:bg-green-800 hover:bg-green-700 dark:hover:bg-green-400 text-white font-bold py-2 px-4 rounded text-center">
                                    View Payroll History
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-employee-layout>