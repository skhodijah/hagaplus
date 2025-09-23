<x-superadmin-layout>
    <div class="py-6 md:py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header with period filter -->
            <div class="mb-6 md:mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100">Super Admin Dashboard</h1>
                    <p class="mt-1 text-gray-600 dark:text-gray-400">Insight dan kontrol untuk seluruh sistem HagaPlus</p>
                </div>
                <form class="flex items-center gap-2">
                    <label for="period" class="text-sm text-gray-600 dark:text-gray-400">Periode</label>
                    <select id="period" name="period" class="text-sm rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                        <option value="7d" {{ request('period','30d')==='7d' ? 'selected' : '' }}>7 hari</option>
                        <option value="30d" {{ request('period','30d')==='30d' ? 'selected' : '' }}>30 hari</option>
                        <option value="90d" {{ request('period')==='90d' ? 'selected' : '' }}>90 hari</option>
                        <option value="1y" {{ request('period')==='1y' ? 'selected' : '' }}>1 tahun</option>
                    </select>
                </form>
            </div>

            @include('superadmin.dashboard.partials.overview')

            @include('superadmin.dashboard.partials.analytics')

            @include('superadmin.dashboard.partials.operational')

            @include('superadmin.dashboard.partials.customer-management')

            @include('superadmin.dashboard.partials.financial')

            @include('superadmin.dashboard.partials.quick-actions')

            @include('superadmin.dashboard.partials.recent-activities')

            @include('superadmin.dashboard.partials.notifications')
        </div>
    </div>
</x-superadmin-layout>
