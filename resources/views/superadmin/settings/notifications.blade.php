<x-superadmin-layout>
    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-page-header
                title="Notification Settings"
                subtitle="Kelola preferensi notifikasi Anda"
                :show-period-filter="false"
            />

            @if(session('status') === 'notifications-updated')
                <div class="mb-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 px-4 py-3 rounded">
                    Notification settings updated successfully.
                </div>
            @endif

            <x-section-card title="Email Notifications" class="mb-6">
                <form method="POST" action="{{ route('superadmin.settings.notifications.update') }}" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <div class="space-y-4">
                        <!-- System Notifications -->
                        <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">System Notifications</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Receive notifications about system updates and maintenance</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="system_notifications" value="1" class="sr-only peer" checked>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <!-- Subscription Notifications -->
                        <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">Subscription Notifications</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Get notified about subscription changes and renewals</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="subscription_notifications" value="1" class="sr-only peer" checked>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <!-- Security Notifications -->
                        <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">Security Notifications</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Receive alerts about security-related events</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="security_notifications" value="1" class="sr-only peer" checked>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <!-- Marketing Notifications -->
                        <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">Marketing & Updates</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Receive news about new features and platform updates</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="marketing_notifications" value="1" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <x-button>Save Preferences</x-button>
                    </div>
                </form>
            </x-section-card>

            <x-section-card title="Notification Methods" class="mb-6">
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">Email Notifications</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Receive notifications via email</p>
                        </div>
                        <div class="text-sm text-green-600 dark:text-green-400 font-medium">
                            <i class="fas fa-check-circle mr-2"></i>Enabled
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">In-App Notifications</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Receive notifications within the application</p>
                        </div>
                        <div class="text-sm text-green-600 dark:text-green-400 font-medium">
                            <i class="fas fa-check-circle mr-2"></i>Enabled
                        </div>
                    </div>
                </div>
            </x-section-card>
        </div>
    </div>
</x-superadmin-layout>