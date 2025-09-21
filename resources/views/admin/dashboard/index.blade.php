<x-admin-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-6">Admin Dashboard</h1>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="bg-blue-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-blue-800">Total Employees</h3>
                            <p class="text-3xl font-bold text-blue-600">{{ $totalEmployees ?? 0 }}</p>
                        </div>
                        
                        <div class="bg-green-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-green-800">Present Today</h3>
                            <p class="text-3xl font-bold text-green-600">{{ $presentToday ?? 0 }}</p>
                        </div>
                        
                        <div class="bg-yellow-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-yellow-800">Pending Payroll</h3>
                            <p class="text-3xl font-bold text-yellow-600">{{ $pendingPayroll ?? 0 }}</p>
                        </div>
                        
                        <div class="bg-purple-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-purple-800">Total Branches</h3>
                            <p class="text-3xl font-bold text-purple-600">{{ $totalBranches ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
