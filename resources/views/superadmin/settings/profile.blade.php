<x-superadmin-layout>
    <div class="py-6 md:py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 md:mb-8">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100">Profile & Account Settings</h1>
                <p class="mt-1 text-gray-600 dark:text-gray-400">Manage your profile information and account settings</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Profile Information -->
                <div class="lg:col-span-2">
                    <x-section-card title="Profile Information">
                        <form method="POST" action="{{ route('superadmin.settings.profile.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')

                            <!-- Avatar Upload -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Profile Picture</label>
                                <div class="flex items-center space-x-4">
                                    <div class="w-20 h-20 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center overflow-hidden">
                                        @if(Auth::user()->avatar)
                                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Profile Picture" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-blue-500 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                                                {{ Auth::user()->initials() }}
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <input type="file" name="avatar" id="avatar" accept="image/*" class="hidden">
                                        <label for="avatar" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 cursor-pointer transition-colors">
                                            <i class="fa-solid fa-camera mr-2"></i>
                                            Change Picture
                                        </label>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">JPG, PNG or GIF. Max size 2MB.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Name -->
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                                       class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('name') border-red-300 dark:border-red-600 @enderror">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="mb-4">
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email Address</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                                       class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('email') border-red-300 dark:border-red-600 @enderror">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                @if($user->email_verified_at)
                                    <p class="mt-1 text-sm text-green-600 dark:text-green-400">
                                        <i class="fa-solid fa-check-circle mr-1"></i>
                                        Email verified
                                    </p>
                                @else
                                    <p class="mt-1 text-sm text-yellow-600 dark:text-yellow-400">
                                        <i class="fa-solid fa-exclamation-triangle mr-1"></i>
                                        Email not verified
                                    </p>
                                @endif
                            </div>

                            <!-- Submit Button -->
                            <div class="flex justify-end">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                    <i class="fa-solid fa-save mr-2"></i>
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </x-section-card>
                </div>

                <!-- Account Settings Sidebar -->
                <div class="space-y-6">
                    <!-- Email Verification -->
                    <x-section-card title="Email Verification">
                        @if($user->email_verified_at)
                            <div class="text-center py-4">
                                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fa-solid fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Your email is verified</p>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fa-solid fa-exclamation-triangle text-yellow-600 dark:text-yellow-400 text-xl"></i>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Your email is not verified</p>
                                <form method="POST" action="{{ route('verification.send') }}">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                        <i class="fa-solid fa-envelope mr-2"></i>
                                        Resend Verification Email
                                    </button>
                                </form>
                            </div>
                        @endif
                    </x-section-card>

                    <!-- Account Information -->
                    <x-section-card title="Account Information">
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Role</label>
                                <p class="text-sm text-gray-900 dark:text-white capitalize">{{ $user->role }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Member Since</label>
                                <p class="text-sm text-gray-900 dark:text-white">{{ $user->created_at->format('M j, Y') }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Last Updated</label>
                                <p class="text-sm text-gray-900 dark:text-white">{{ $user->updated_at->format('M j, Y') }}</p>
                            </div>
                        </div>
                    </x-section-card>

                    <!-- Security Notice -->
                    <x-section-card title="Security">
                        <div class="text-center">
                            <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-2">
                                <i class="fa-solid fa-shield-alt text-red-600 dark:text-red-400"></i>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Keep your account secure</p>
                            <a href="#" class="inline-flex items-center px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                <i class="fa-solid fa-key mr-2"></i>
                                Change Password
                            </a>
                        </div>
                    </x-section-card>
                </div>
            </div>
        </div>
    </div>
</x-superadmin-layout>