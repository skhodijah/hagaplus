<x-superadmin-layout :recentNotifications="$recentNotifications">
    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-page-header
                title="Super Admin Dashboard"
                subtitle="Insight dan kontrol untuk seluruh sistem HagaPlus"
                :show-period-filter="true"
            />

            @include('superadmin.dashboard.partials.overview')

            @include('superadmin.dashboard.partials.quick-actions')

            @include('superadmin.dashboard.partials.notifications')
        </div>
    </div>
</x-superadmin-layout>
