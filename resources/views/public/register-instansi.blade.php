<x-guest-layout>
    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-2">Pendaftaran Instansi</h1>
            <p class="text-gray-600 dark:text-gray-300 mb-6">Buat akun admin dan daftarkan instansi Anda. Akun akan langsung aktif dengan paket Free (trial 30 hari, fitur terbatas). Anda bisa upgrade paket kapan saja dari halaman instansi.</p>

            @if(session('success'))
                <div class="mb-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <x-section-card title="Akun Admin">
                <form method="POST" action="{{ route('public.register.instansi.store') }}">
                    @csrf
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input name="user_name" value="{{ old('user_name') }}" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white @error('user_name') border-red-500 @enderror" />
                            @error('user_name')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="user_email" value="{{ old('user_email') }}" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white @error('user_email') border-red-500 @enderror" />
                            @error('user_email')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm mb-1">Password <span class="text-red-500">*</span></label>
                                <input type="password" name="password" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white @error('password') border-red-500 @enderror" />
                                @error('password')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm mb-1">Konfirmasi Password <span class="text-red-500">*</span></label>
                                <input type="password" name="password_confirmation" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white" />
                            </div>
                        </div>
                    </div>

                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mt-8 mb-3">Data Instansi</h2>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm mb-1">Nama Instansi <span class="text-red-500">*</span></label>
                            <input name="nama_instansi" value="{{ old('nama_instansi') }}" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white @error('nama_instansi') border-red-500 @enderror" />
                            @error('nama_instansi')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Subdomain <span class="text-red-500">*</span></label>
                            <div class="flex">
                                <input name="subdomain" value="{{ old('subdomain') }}" class="flex-1 px-3 py-2 border rounded-l-md dark:bg-gray-700 dark:text-white @error('subdomain') border-red-500 @enderror" />
                                <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 bg-gray-50 dark:bg-gray-600 dark:text-gray-200">.hagaplus.com</span>
                            </div>
                            @error('subdomain')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Email Instansi</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white @error('email') border-red-500 @enderror" />
                            @error('email')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Telepon</label>
                            <input name="phone" value="{{ old('phone') }}" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white @error('phone') border-red-500 @enderror" />
                            @error('phone')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Alamat</label>
                            <textarea name="address" rows="3" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
                            @error('address')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="mt-6 flex justify-between items-center">
                        <p class="text-sm text-gray-600 dark:text-gray-300">Dengan mendaftar, Anda setuju akun akan aktif dengan paket Free (maks 5 karyawan, 1 cabang, fitur terbatas) selama 30 hari.</p>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">Daftarkan Instansi</button>
                    </div>
                </form>
            </x-section-card>
        </div>
    </div>
</x-guest-layout> 