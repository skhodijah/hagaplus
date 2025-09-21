<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Edit Company') }}
            </h2>
            <a href="{{ route('admin.companies') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to Companies
            </a>
        </div>
    </x-slot>

    <div class="flex min-h-screen bg-gray-100 dark:bg-gray-900">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 text-white min-h-screen">
            <div class="p-4">
                <h3 class="text-lg font-semibold mb-4">Super Admin Menu</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('dashboard') }}" class="block py-2 px-4 rounded hover:bg-gray-700">Dashboard</a></li>
                    <li><a href="{{ route('admin.companies') }}" class="block py-2 px-4 rounded hover:bg-gray-700 bg-gray-700">Companies</a></li>
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
                    <form method="POST" action="{{ route('admin.companies.update', $company) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <x-input-label for="name" :value="__('Company Name')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $company->name)" required autofocus autocomplete="name" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Email -->
                            <div>
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $company->email)" required autocomplete="email" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <!-- Phone -->
                            <div>
                                <x-input-label for="phone" :value="__('Phone')" />
                                <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone', $company->phone)" autocomplete="phone" />
                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                            </div>

                            <!-- Package -->
                            <div>
                                <x-input-label for="package_id" :value="__('Package')" />
                                <select id="package_id" name="package_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="">Select Package</option>
                                    @foreach($packages as $package)
                                        <option value="{{ $package->id }}" {{ old('package_id', $company->package_id) == $package->id ? 'selected' : '' }}>{{ $package->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('package_id')" class="mt-2" />
                            </div>

                            <!-- Max Employees -->
                            <div>
                                <x-input-label for="max_employees" :value="__('Max Employees')" />
                                <x-text-input id="max_employees" class="block mt-1 w-full" type="number" name="max_employees" :value="old('max_employees', $company->max_employees)" min="1" required />
                                <x-input-error :messages="$errors->get('max_employees')" class="mt-2" />
                            </div>

                            <!-- Max Branches -->
                            <div>
                                <x-input-label for="max_branches" :value="__('Max Branches')" />
                                <x-text-input id="max_branches" class="block mt-1 w-full" type="number" name="max_branches" :value="old('max_branches', $company->max_branches)" min="1" required />
                                <x-input-error :messages="$errors->get('max_branches')" class="mt-2" />
                            </div>

                            <!-- Subscription Start -->
                            <div>
                                <x-input-label for="subscription_start" :value="__('Subscription Start')" />
                                <x-text-input id="subscription_start" class="block mt-1 w-full" type="datetime-local" name="subscription_start" :value="old('subscription_start', $company->subscription_start?->format('Y-m-d\TH:i'))" />
                                <x-input-error :messages="$errors->get('subscription_start')" class="mt-2" />
                            </div>

                            <!-- Subscription End -->
                            <div>
                                <x-input-label for="subscription_end" :value="__('Subscription End')" />
                                <x-text-input id="subscription_end" class="block mt-1 w-full" type="datetime-local" name="subscription_end" :value="old('subscription_end', $company->subscription_end?->format('Y-m-d\TH:i'))" />
                                <x-input-error :messages="$errors->get('subscription_end')" class="mt-2" />
                            </div>

                            <!-- Is Active -->
                            <div class="md:col-span-2">
                                <label class="flex items-center">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $company->is_active) ? 'checked' : '' }} class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" />
                                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Active') }}</span>
                                </label>
                                <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                            </div>

                            <!-- Address -->
                            <div class="md:col-span-2">
                                <x-input-label for="address" :value="__('Address')" />
                                <textarea id="address" name="address" rows="4" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" placeholder="Enter company address">{{ old('address', $company->address) }}</textarea>
                                <x-input-error :messages="$errors->get('address')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.companies') }}" class="mr-4 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">Cancel</a>
                            <x-primary-button>
                                {{ __('Update Company') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>