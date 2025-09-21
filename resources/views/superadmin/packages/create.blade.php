<x-superadmin-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Create New Package</h1>
                <p class="mt-2 text-gray-600">Add a new package to the system</p>
            </div>

            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <form method="POST" action="{{ route('superadmin.packages.store') }}">
                        @csrf
                        
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Package Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('name') border-red-300 @enderror">
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" id="description" rows="3" 
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('description') border-red-300 @enderror">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="price" class="block text-sm font-medium text-gray-700">Price (Rp)</label>
                                    <input type="number" name="price" id="price" value="{{ old('price') }}" min="0" step="0.01"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('price') border-red-300 @enderror">
                                    @error('price')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="max_employees" class="block text-sm font-medium text-gray-700">Max Employees</label>
                                    <input type="number" name="max_employees" id="max_employees" value="{{ old('max_employees') }}" min="1"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('max_employees') border-red-300 @enderror">
                                    @error('max_employees')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="max_branches" class="block text-sm font-medium text-gray-700">Max Branches</label>
                                    <input type="number" name="max_branches" id="max_branches" value="{{ old('max_branches') }}" min="1"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('max_branches') border-red-300 @enderror">
                                    @error('max_branches')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Features</label>
                                    <div class="mt-2 space-y-2">
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="features[]" value="qr" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-700">QR Code Attendance</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="features[]" value="gps" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-700">GPS Location</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="features[]" value="face_id" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-700">Face ID Recognition</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="features[]" value="payroll" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-700">Payroll Management</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }} 
                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">Active Package</span>
                                </label>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end space-x-3">
                            <a href="{{ route('superadmin.packages.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Create Package
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-superadmin-layout>
