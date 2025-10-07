<x-superadmin-layout>
    <div class="py-6 md:py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 md:mb-8">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100">Notification Settings</h1>
                <p class="mt-1 text-gray-600 dark:text-gray-400">Configure how you receive notifications</p>
            </div>

            <x-section-card title="Notification Preferences">
                <form method="POST" action="{{ route('superadmin.settings.notifications.update') }}">
                    @csrf
                    @method('PATCH')

                    <div class="space-y-6">
                        <!-- Email Notifications -->
                        <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fa-solid fa-envelope text-blue-600 dark:text-blue-400"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">Email Notifications</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Receive notifications via email</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="email_notifications" value="1"
                                       class="sr-only peer" {{ old('email_notifications', true) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <!-- Push Notifications -->
                        <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fa-solid fa-bell text-green-600 dark:text-green-400"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">Push Notifications</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Receive push notifications in your browser</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="push_notifications" value="1"
                                       class="sr-only peer" {{ old('push_notifications', true) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 dark:peer-focus:ring-green-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-600"></div>
                            </label>
                        </div>

                        <!-- SMS Notifications -->
                        <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fa-solid fa-sms text-purple-600 dark:text-purple-400"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">SMS Notifications</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Receive important notifications via SMS</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="sms_notifications" value="1"
                                       class="sr-only peer" {{ old('sms_notifications', false) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 dark:peer-focus:ring-purple-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-purple-600"></div>
                            </label>
                        </div>
                    </div>

                    <!-- Notification Types -->
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Notification Types</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">New Subscription Requests</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">When instansi submit subscription requests</p>
                                </div>
                                <input type="checkbox" name="notify_subscription_requests" value="1" checked
                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            </div>

                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">Payment Confirmations</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">When payments are processed</p>
                                </div>
                                <input type="checkbox" name="notify_payment_confirmations" value="1" checked
                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            </div>

                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">System Alerts</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Important system notifications</p>
                                </div>
                                <input type="checkbox" name="notify_system_alerts" value="1" checked
                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            </div>

                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">Support Requests</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">When new support tickets are created</p>
                                </div>
                                <input type="checkbox" name="notify_support_requests" value="1" checked
                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                            <i class="fa-solid fa-save mr-2"></i>
                            Save Preferences
                        </button>
                    </div>
                </form>
            </x-section-card>
        </div>
    </div>
</x-superadmin-layout>