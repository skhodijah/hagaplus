<x-employee-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-white">
                    <h1 class="text-2xl font-bold mb-6">Employee Payroll</h1>

                    <div class="bg-purple-50 dark:bg-purple-500 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-purple-800 dark:text-purple-100">Last Payroll</h3>
                        <p class="text-2xl font-bold text-purple-600 dark:text-white">Rp
                            {{ number_format($lastPayroll ?? 0) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-employee-layout>