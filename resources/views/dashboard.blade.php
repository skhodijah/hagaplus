<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Super Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="flex min-h-screen bg-gray-100 dark:bg-gray-900">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 text-white min-h-screen">
            <div class="p-4">
                <h3 class="text-lg font-semibold mb-4">Super Admin Menu</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('dashboard') }}" class="block py-2 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('dashboard') ? 'bg-gray-700' : '' }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.companies') }}" class="block py-2 px-4 rounded hover:bg-gray-700">Companies</a></li>
                    <li><a href="#" class="block py-2 px-4 rounded hover:bg-gray-700">Branches</a></li>
                    <li><a href="#" class="block py-2 px-4 rounded hover:bg-gray-700">Users</a></li>
                    <li><a href="#" class="block py-2 px-4 rounded hover:bg-gray-700">Packages</a></li>
                    <li><a href="#" class="block py-2 px-4 rounded hover:bg-gray-700">Payrolls</a></li>
                    <li><a href="#" class="block py-2 px-4 rounded hover:bg-gray-700">Attendances</a></li>
                    <li><a href="#" class="block py-2 px-4 rounded hover:bg-gray-700">Leaves</a></li>
                    <li><a href="#" class="block py-2 px-4 rounded hover:bg-gray-700">Reports</a></li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-bold mb-4">Welcome to Super Admin Dashboard</h3>
                    <p class="mb-6">Manage your system efficiently from here.</p>

                    <!-- Dashboard Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <div class="bg-blue-500 text-white p-4 rounded-lg shadow">
                            <h4 class="text-lg font-semibold">Total Companies</h4>
                            <p class="text-2xl">{{ $data['totalCompanies'] }}</p>
                        </div>
                        <div class="bg-green-500 text-white p-4 rounded-lg shadow">
                            <h4 class="text-lg font-semibold">Total Branches</h4>
                            <p class="text-2xl">{{ $data['totalBranches'] }}</p>
                        </div>
                        <div class="bg-yellow-500 text-white p-4 rounded-lg shadow">
                            <h4 class="text-lg font-semibold">Total Users</h4>
                            <p class="text-2xl">{{ $data['totalUsers'] }}</p>
                        </div>
                        <div class="bg-red-500 text-white p-4 rounded-lg shadow">
                            <h4 class="text-lg font-semibold">Total Packages</h4>
                            <p class="text-2xl">{{ $data['totalPackages'] }}</p>
                        </div>
                    </div>

                    <!-- Recent Data Tables -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Recent Users -->
                        <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow">
                            <h4 class="text-lg font-semibold mb-4">Recent Users</h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full table-auto">
                                    <thead>
                                        <tr class="bg-gray-50 dark:bg-gray-600">
                                            <th class="px-4 py-2 text-left">Name</th>
                                            <th class="px-4 py-2 text-left">Email</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data['recentUsers'] as $user)
                                        <tr class="border-t">
                                            <td class="px-4 py-2">{{ $user->name }}</td>
                                            <td class="px-4 py-2">{{ $user->email }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Recent Companies -->
                        <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow">
                            <h4 class="text-lg font-semibold mb-4">Recent Companies</h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full table-auto">
                                    <thead>
                                        <tr class="bg-gray-50 dark:bg-gray-600">
                                            <th class="px-4 py-2 text-left">Name</th>
                                            <th class="px-4 py-2 text-left">Email</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data['recentCompanies'] as $company)
                                        <tr class="border-t">
                                            <td class="px-4 py-2">{{ $company->name }}</td>
                                            <td class="px-4 py-2">{{ $company->email ?? 'N/A' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
