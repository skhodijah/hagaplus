<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Edit Division') }}
            </h2>
            <a href="{{ route('admin.divisions.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-arrow-left mr-2"></i>Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Whoops!</strong> There were some problems with your input.
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <form method="POST" action="{{ route('admin.divisions.update', $division) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                            Division Name
                        </label>
                        <input id="name" name="name" type="text" value="{{ old('name', $division->name) }}"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                               required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="code">
                            Division Code
                        </label>
                        <input id="code" name="code" type="text" value="{{ old('code', $division->code) }}"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                               required maxlength="10">
                        <p class="text-gray-600 text-xs mt-1">This code will be used for employee ID generation (max 10 characters)</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                            Description
                        </label>
                        <textarea id="description" name="description" rows="3"
                                  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('description', $division->description) }}</textarea>
                    </div>
                    <div class="mb-4 flex items-center">
                        <input id="is_active" name="is_active" type="checkbox" value="1" {{ $division->is_active ? 'checked' : '' }}
                               class="mr-2 leading-tight">
                        <label class="text-gray-700 text-sm" for="is_active">
                            Active
                        </label>
                    </div>
                    <div class="flex items-center justify-between">
                        <button type="submit"
                                class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Update Division
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
