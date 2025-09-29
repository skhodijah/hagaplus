<x-superadmin-layout>
    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-page-header
                title="Profile & Account Settings"
                subtitle="Kelola informasi profil dan pengaturan akun Anda"
                :show-period-filter="false"
            />

            @if(session('status') === 'profile-updated')
                <div class="mb-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 px-4 py-3 rounded">
                    Profile information updated successfully.
                </div>
            @endif

            @if(session('status') === 'password-updated')
                <div class="mb-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 px-4 py-3 rounded">
                    Password updated successfully.
                </div>
            @endif

            @if($errors->has('general'))
                <div class="mb-4 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-300 px-4 py-3 rounded">
                    {{ $errors->first('general') }}
                </div>
            @endif

            <!-- Password Error Alert (akan ditampilkan via JavaScript) -->
            <div id="password-error-alert" class="mb-4 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-300 px-4 py-3 rounded hidden">
                <span id="password-error-message"></span>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Profile Information -->
                <x-section-card title="Profile Information" class="lg:col-span-1">
                    <form method="POST" action="{{ route('superadmin.settings.profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <!-- Profile Avatar -->
                        <div class="flex items-center space-x-6 mb-6">
                            <div class="relative">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Profile Picture" class="w-20 h-20 rounded-full object-cover border-4 border-gray-200 dark:border-gray-700">
                                @else
                                    <div class="w-20 h-20 rounded-full bg-blue-500 flex items-center justify-center text-white text-xl font-bold border-4 border-gray-200 dark:border-gray-700">
                                        {{ $user->initials() }}
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $user->name }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500 capitalize">{{ ucfirst($user->role) }}</p>
                            </div>
                        </div>

                        <!-- Avatar Upload -->
                        <div>
                            <x-input-label for="avatar" value="Profile Picture" />
                            <input id="avatar" name="avatar" type="file" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" accept="image/*" onchange="previewAvatar(this)" />
                            <x-input-error :messages="$errors->get('avatar')" class="mt-2" />
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Accepted formats: JPG, PNG, GIF. Max size: 2MB</p>

                            <!-- Avatar Preview -->
                            <div id="avatar-preview" class="mt-3 hidden">
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Preview:</p>
                                <img id="avatar-preview-img" src="" alt="Avatar Preview" class="w-20 h-20 rounded-full object-cover border-4 border-gray-200 dark:border-gray-700">
                            </div>
                        </div>

                        <!-- Name -->
                        <div>
                            <x-input-label for="name" value="Name" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" autocomplete="name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email Address -->
                        <div>
                            <x-input-label for="email" value="Email" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Role (Read-only) -->
                        <div>
                            <x-input-label for="role" value="Role" />
                            <x-text-input id="role" type="text" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700" :value="ucfirst($user->role)" readonly />
                        </div>

                        <!-- Account Created -->
                        <div>
                            <x-input-label value="Account Created" />
                            <x-text-input type="text" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700" :value="$user->created_at->format('d M Y, H:i')" readonly />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>Save Changes</x-primary-button>
                        </div>
                    </form>
                </x-section-card>

                <!-- Password Settings -->
                <x-section-card title="Password Settings" class="lg:col-span-1">
                    <form method="POST" action="{{ route('password.update') }}" class="space-y-6" id="password-form">
                        @csrf
                        @method('PUT')

                        <!-- Current Password -->
                        <div>
                            <x-input-label for="current_password" value="Current Password" />
                            <x-text-input id="current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
                            <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
                            <div id="current-password-status" class="mt-1 text-sm"></div>
                        </div>

                        <!-- Password -->
                        <div>
                            <x-input-label for="password" value="New Password" />
                            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Minimum 8 characters</p>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <x-input-label for="password_confirmation" value="Confirm New Password" />
                            <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            <div id="password-match-status" class="mt-1 text-sm"></div>
                        </div>

                        <div class="items-center gap-4">
                            <x-primary-button id="update-password-btn" disabled>Update Password</x-primary-button>
                            <div id="validation-hint" class="text-sm text-gray-500 dark:text-gray-400"></div>
                        </div>
                    </form>
                </x-section-card>
            </div>

            <!-- Account Deletion -->
            <x-section-card title="Delete Account" class="mt-6 bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800">
                <div class="space-y-4">
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        <p>Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.</p>
                    </div>

                    <x-danger-button
                        x-data=""
                        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                    >Delete Account</x-danger-button>

                    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
                        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
                            @csrf
                            @method('delete')

                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                Are you sure you want to delete your account?
                            </h2>

                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.
                            </p>

                            <div class="mt-6">
                                <x-input-label for="password" value="Password" class="sr-only" />

                                <x-text-input
                                    id="password"
                                    name="password"
                                    type="password"
                                    class="mt-1 block w-3/4"
                                    placeholder="Password"
                                />

                                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                            </div>

                            <div class="mt-6 flex justify-end">
                                <x-secondary-button x-on:click="$dispatch('close')">
                                    Cancel
                                </x-secondary-button>

                                <x-danger-button class="ms-3">
                                    Delete Account
                                </x-danger-button>
                            </div>
                        </form>
                    </x-modal>
                </div>
            </x-section-card>
        </div>
    </div>

    <!-- Include JavaScript file -->
    <script src="{{ asset('js/admin/profile-settings.js') }}"></script>
</x-superadmin-layout>