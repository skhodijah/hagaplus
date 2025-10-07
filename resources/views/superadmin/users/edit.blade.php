<x-superadmin-layout>
    <div class="py-6 md:py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 md:mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100">Edit User</h1>
                        <p class="mt-1 text-gray-600 dark:text-gray-400">Update user information and settings</p>
                    </div>
                    <a href="{{ route('superadmin.users.index') }}" class="bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 text-gray-800 dark:text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fa-solid fa-arrow-left mr-2"></i>Back to Users
                    </a>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white dark:bg-gray-900 rounded-lg shadow">
                <form method="POST" action="{{ route('superadmin.users.update', $user) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">User Information</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Update the user's basic information</p>
                    </div>

                    <div class="px-6 py-6 space-y-6">
                        <!-- Avatar -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Profile Picture</label>
                            <div class="flex items-center space-x-4">
                                <div class="w-20 h-20 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center overflow-hidden">
                                    @if($user->avatar)
                                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                    @else
                                        <i class="fa-solid fa-user text-gray-400 text-2xl"></i>
                                    @endif
                                </div>
                                <div>
                                    <input type="file" name="avatar" id="avatar" accept="image/*" class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">PNG, JPG, GIF up to 2MB</p>
                                    @if($user->avatar)
                                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Leave empty to keep current avatar</p>
                                    @endif
                                </div>
                            </div>
                            @error('avatar')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Name and Email -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full Name <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                       class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('name') border-red-300 dark:border-red-600 @enderror">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email Address <span class="text-red-500">*</span></label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                       class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('email') border-red-300 dark:border-red-600 @enderror">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Phone and Role -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone Number</label>
                                <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                                       class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('phone') border-red-300 dark:border-red-600 @enderror">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Role <span class="text-red-500">*</span></label>
                                <select name="role" id="role" required
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('role') border-red-300 dark:border-red-600 @enderror">
                                    <option value="">Select Role</option>
                                    <option value="superadmin" {{ old('role', $user->role) == 'superadmin' ? 'selected' : '' }} {{ $user->role === 'superadmin' ? 'disabled' : '' }}>Super Admin</option>
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="employee" {{ old('role', $user->role) == 'employee' ? 'selected' : '' }}>Employee</option>
                                </select>
                                @error('role')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                @if($user->role === 'superadmin')
                                    <p class="mt-1 text-sm text-amber-600 dark:text-amber-400">Super admin role cannot be changed</p>
                                @endif
                            </div>
                        </div>

                        <!-- Instansi -->
                        <div id="instansiField" class="{{ in_array($user->role, ['admin', 'employee']) ? '' : 'hidden' }}">
                            <label for="instansi_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Instansi <span class="text-red-500">*</span></label>
                            <select name="instansi_id" id="instansi_id"
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('instansi_id') border-red-300 dark:border-red-600 @enderror">
                                <option value="">Select Instansi</option>
                                @foreach($instansis as $instansi)
                                    <option value="{{ $instansi->id }}" {{ old('instansi_id', $user->instansi_id) == $instansi->id ? 'selected' : '' }}>{{ $instansi->nama_instansi }}</option>
                                @endforeach
                            </select>
                            @error('instansi_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-4">Change Password (Optional)</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">New Password</label>
                                    <input type="password" name="password" id="password"
                                           class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('password') border-red-300 dark:border-red-600 @enderror">
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Leave empty to keep current password</p>
                                    @error('password')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Confirm New Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                           class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm">
                                </div>
                            </div>
                        </div>

                        <!-- Active Status -->
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm text-gray-900 dark:text-gray-100">
                                Active user account
                            </label>
                        </div>
                    </div>

                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
                        <a href="{{ route('superadmin.users.index') }}" class="bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 text-gray-800 dark:text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            <i class="fa-solid fa-times mr-2"></i>Cancel
                        </a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            <i class="fa-solid fa-save mr-2"></i>Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('role');
            const instansiField = document.getElementById('instansiField');
            const instansiSelect = document.getElementById('instansi_id');

            function toggleInstansiField() {
                const selectedRole = roleSelect.value;
                if (selectedRole === 'admin' || selectedRole === 'employee') {
                    instansiField.classList.remove('hidden');
                    instansiSelect.required = true;
                } else {
                    instansiField.classList.add('hidden');
                    instansiSelect.required = false;
                    instansiSelect.value = '';
                }
            }

            roleSelect.addEventListener('change', toggleInstansiField);
            toggleInstansiField(); // Initial check
        });
    </script>
</x-superadmin-layout>