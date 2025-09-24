<x-superadmin-layout>
    <div class="py-6 md:py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 md:mb-8">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100">System Health</h1>
                <p class="mt-1 text-gray-600 dark:text-gray-400">Monitor operational status and system performance</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6 mb-6 md:mb-8">
                <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-4">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">Server Uptime & Performance</h3>
                    <div class="h-28 rounded-md bg-gray-50 dark:bg-gray-800"></div>
                </div>
                <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-4">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">Database Size & Growth</h3>
                    <div class="h-28 rounded-md bg-gray-50 dark:bg-gray-800"></div>
                </div>
                <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-4">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">API Usage & Error Summary (24h)</h3>
                    <div class="h-28 rounded-md bg-gray-50 dark:bg-gray-800"></div>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-6 md:mb-8">
                <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-4">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">System Health Indicators</h3>
                    <div class="h-40 rounded-md bg-gray-50 dark:bg-gray-800"></div>
                </div>
                <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-4">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">Backup Status Terakhir</h3>
                    <div class="h-40 rounded-md bg-gray-50 dark:bg-gray-800"></div>
                </div>
            </div>
        </div>
    </div>
</x-superadmin-layout>