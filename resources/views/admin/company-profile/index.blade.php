<x-admin-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Profil Perusahaan</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Kelola informasi legalitas dan identitas perusahaan Anda.
                    </p>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg flex items-center gap-3">
                <i class="fa-solid fa-check-circle"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.company-profile.update') }}" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
            @csrf
            @method('PUT')

            <div class="p-6 space-y-8">
                <!-- Identitas Utama -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-building text-blue-500"></i> Identitas Perusahaan
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Logo Upload -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Logo Perusahaan <span class="text-red-500">*</span></label>
                            <div class="flex items-center gap-6">
                                <div class="relative w-24 h-24 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 flex items-center justify-center overflow-hidden bg-gray-50 dark:bg-gray-700 group">
                                    @if($instansi->logo)
                                        <img id="logo-preview" src="{{ asset('storage/' . $instansi->logo) }}" alt="Logo" class="w-full h-full object-contain">
                                    @else
                                        <i id="logo-icon" class="fa-regular fa-image text-3xl text-gray-400"></i>
                                    @endif
                                    <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                        <i class="fa-solid fa-pen text-white"></i>
                                    </div>
                                    <input type="file" name="logo" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*">
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    <p>Format: JPG, PNG, SVG</p>
                                    <p>Max: 2MB</p>
                                    <p>Disarankan rasio 1:1 atau persegi</p>
                                </div>
                            </div>
                        </div>

                        <!-- Nama Perusahaan -->
                        <div>
                            <label for="nama_instansi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Perusahaan <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_instansi" id="nama_instansi" value="{{ old('nama_instansi', $instansi->nama_instansi) }}" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                            <p class="mt-1 text-xs text-gray-500">Nama legal perusahaan (PT/CV/Firma)</p>
                        </div>

                        <!-- Nama Tampilan (Singkatan) -->
                        <div>
                            <label for="display_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Tampilan / Singkatan <span class="text-gray-400 font-normal">(Opsional)</span></label>
                            <input type="text" name="display_name" id="display_name" value="{{ old('display_name', $instansi->display_name) }}" placeholder="Contoh: PT TNJ"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                            <p class="mt-1 text-xs text-gray-500">Akan ditampilkan di header/sidebar. Jika kosong, akan disingkat otomatis.</p>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-200 dark:border-gray-700">

                <!-- Legalitas -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-file-contract text-blue-500"></i> Legalitas
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- NPWP -->
                        <div>
                            <label for="npwp" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">NPWP Perusahaan <span class="text-red-500">*</span></label>
                            <input type="text" name="npwp" id="npwp" value="{{ old('npwp', $instansi->npwp) }}" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                            <p class="mt-1 text-xs text-gray-500">Digunakan untuk keperluan pajak & payroll</p>
                        </div>

                        <!-- NIB -->
                        <div>
                            <label for="nib" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">NIB / SIUP <span class="text-gray-400 font-normal">(Opsional)</span></label>
                            <input type="text" name="nib" id="nib" value="{{ old('nib', $instansi->nib) }}"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>

                <hr class="border-gray-200 dark:border-gray-700">

                <!-- Kontak & Alamat -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-map-location-dot text-blue-500"></i> Kontak & Alamat
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Perusahaan <span class="text-red-500">*</span></label>
                            <input type="email" name="email" id="email" value="{{ old('email', $instansi->email) }}" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Telepon -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nomor Telepon <span class="text-red-500">*</span></label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $instansi->phone) }}" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Website -->
                        <div class="md:col-span-2">
                            <label for="website" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Website <span class="text-gray-400 font-normal">(Opsional)</span></label>
                            <input type="url" name="website" id="website" value="{{ old('website', $instansi->website) }}" placeholder="https://example.com"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Kota -->
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kota / Kabupaten <span class="text-red-500">*</span></label>
                            <input type="text" name="city" id="city" value="{{ old('city', $instansi->city) }}" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Alamat -->
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat Lengkap <span class="text-red-500">*</span></label>
                            <textarea name="address" id="address" rows="3" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">{{ old('address', $instansi->address) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition-colors">
                    <i class="fa-solid fa-save mr-2"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
    <script>
        document.querySelector('input[name="logo"]').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById('logo-preview');
                    const icon = document.getElementById('logo-icon');
                    
                    if (img) {
                        img.src = e.target.result;
                    } else if (icon) {
                        // If there was no image before (only icon), replace icon with image
                        const container = icon.parentElement;
                        const newImg = document.createElement('img');
                        newImg.id = 'logo-preview';
                        newImg.src = e.target.result;
                        newImg.className = 'w-full h-full object-contain';
                        newImg.alt = 'Logo Preview';
                        
                        icon.remove();
                        container.appendChild(newImg);
                    }
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</x-admin-layout>
