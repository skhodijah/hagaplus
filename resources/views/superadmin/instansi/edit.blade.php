<x-superadmin-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Edit Instansi</h1>
                <p class="mt-2 text-gray-600">Update instansi information</p>
            </div>

            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <form method="POST" action="{{ route('superadmin.instansi.update', $instansi) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="nama_instansi" class="block text-sm font-medium text-gray-700">Nama Instansi</label>
                                <input type="text" name="nama_instansi" id="nama_instansi" value="{{ old('nama_instansi', $instansi->nama_instansi) }}" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('nama_instansi') border-red-300 @enderror">
                                @error('nama_instansi')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="subdomain" class="block text-sm font-medium text-gray-700">Subdomain</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <input type="text" name="subdomain" id="subdomain" value="{{ old('subdomain', $instansi->subdomain) }}" 
                                           class="flex-1 min-w-0 block w-full px-3 py-2 border border-gray-300 rounded-none rounded-l-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('subdomain') border-red-300 @enderror">
                                    <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                        .hagaplus.com
                                    </span>
                                </div>
                                @error('subdomain')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="status_langganan" class="block text-sm font-medium text-gray-700">Status Langganan</label>
                                <select name="status_langganan" id="status_langganan" 
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('status_langganan') border-red-300 @enderror">
                                    <option value="">Select Status</option>
                                    <option value="active" {{ old('status_langganan', $instansi->status_langganan) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status_langganan', $instansi->status_langganan) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="suspended" {{ old('status_langganan', $instansi->status_langganan) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                </select>
                                @error('status_langganan')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end space-x-3">
                            <a href="{{ route('superadmin.instansi.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Instansi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-superadmin-layout>
