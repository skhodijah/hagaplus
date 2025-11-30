<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'HagaPlus') }} - Register</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            --sea-green: #008159;
            --emerald: #0EC774;
            --light-green: #76E47E;
            --lime-cream: #D2FE8C;
            --black: #000000;
        }
        body { font-family: 'Figtree', sans-serif; }
    </style>
</head>
<body class="h-screen w-full flex overflow-hidden bg-gray-50">
    <!-- Left Side - Image (Desktop Only) -->
    <div class="hidden lg:flex w-1/2 relative bg-gray-900">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('images/login-bg.png') }}');"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-black/70 to-black/30"></div>
        <div class="relative z-10 flex flex-col justify-between p-16 text-white w-full h-full">
            <h2 class="text-5xl font-bold mb-6 leading-tight">Bergabung Bersama<br>HagaPlus</h2>
            <p class="text-xl text-gray-200 max-w-lg leading-relaxed">Mulai perjalanan efisiensi bisnis Anda dengan solusi manajemen SDM yang terintegrasi.</p>
            <div class="text-sm text-gray-400">Photo by AI Generated</div>
        </div>
    </div>
    <!-- Right Side - Register Form -->
    <div class="w-full lg:w-1/2 flex flex-col p-8 bg-white relative overflow-y-auto">
        <a href="{{ route('home') }}" class="absolute top-0 left-0 p-8 text-gray-500 hover:text-[#008159] transition-colors flex items-center gap-2 group z-20">
            <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            <span class="font-medium">Kembali</span>
        </a>
        <div class="w-full max-w-md space-y-8 py-12 m-auto relative z-10">
            <div class="text-center">
                <img src="{{ asset('images/Haga.png') }}" alt="Haga Logo" class="h-24 w-auto object-contain drop-shadow-md mx-auto mb-4">
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Buat Akun Baru</h1>
                <p class="mt-3 text-gray-600 text-lg">Daftarkan Perusahaan Anda</p>
            </div>
            
            <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-6">
                @csrf
                <!-- Hidden Package Input -->
                <input type="hidden" name="package" value="{{ request('package') }}">

                <!-- Google Register Button -->
                <div class="mb-6">
                    <a href="{{ route('auth.google') }}" class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 rounded-xl shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                        <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="h-5 w-5 mr-2" alt="Google Logo">
                        Daftar dengan Google
                    </a>
                    
                    <div class="relative mt-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">Atau daftar dengan email</span>
                        </div>
                    </div>
                </div>
                
                @php
                    $googleUser = session('google_user');
                @endphp
                
                @if($googleUser)
                    <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4 flex items-center">
                        <img src="{{ $googleUser->avatar }}" alt="{{ $googleUser->name }}" class="w-10 h-10 rounded-full mr-3">
                        <div>
                            <p class="text-sm font-medium text-blue-900">Mendaftar sebagai {{ $googleUser->name }}</p>
                            <p class="text-xs text-blue-700">{{ $googleUser->email }}</p>
                        </div>
                    </div>
                @endif

                <div class="space-y-5">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                        <div class="relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fa-regular fa-user text-gray-400 text-lg"></i>
                            </div>
                            <input id="name" name="name" type="text" required autofocus autocomplete="name"
                                class="block w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0EC774]/50 focus:border-[#0EC774] bg-gray-50 focus:bg-white" 
                                placeholder="Nama Anda" value="{{ old('name', $googleUser->name ?? '') }}" {{ isset($googleUser) ? 'readonly' : '' }}>
                        </div>
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Company Name -->
                    <div>
                        <label for="company_name" class="block text-sm font-semibold text-gray-700 mb-1">Nama Perusahaan / Instansi</label>
                        <div class="relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fa-solid fa-building text-gray-400 text-lg"></i>
                            </div>
                            <input id="company_name" name="company_name" type="text" required autocomplete="organization"
                                class="block w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0EC774]/50 focus:border-[#0EC774] bg-gray-50 focus:bg-white" 
                                placeholder="Nama Perusahaan" value="{{ old('company_name') }}">
                        </div>
                        <x-input-error :messages="$errors->get('company_name')" class="mt-2" />
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email Address</label>
                        <div class="relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fa-regular fa-envelope text-gray-400 text-lg"></i>
                            </div>
                            <input id="email" name="email" type="email" required autocomplete="username"
                                class="block w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0EC774]/50 focus:border-[#0EC774] bg-gray-50 focus:bg-white" 
                                placeholder="nama@perusahaan.com" value="{{ old('email', $googleUser->email ?? '') }}" {{ isset($googleUser) ? 'readonly' : '' }}>
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    @if(!isset($googleUser))
                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                        <div class="relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fa-solid fa-lock text-gray-400 text-lg"></i>
                            </div>
                            <input id="password" name="password" type="password" required autocomplete="new-password"
                                class="block w-full pl-11 pr-11 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0EC774]/50 focus:border-[#0EC774] bg-gray-50 focus:bg-white" 
                                placeholder="••••••••">
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center cursor-pointer" onclick="togglePassword('password', 'togglePasswordIcon')">
                                <i class="fa-regular fa-eye text-gray-400 text-lg" id="togglePasswordIcon"></i>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1">Konfirmasi Password</label>
                        <div class="relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fa-solid fa-lock text-gray-400 text-lg"></i>
                            </div>
                            <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                                class="block w-full pl-11 pr-11 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0EC774]/50 focus:border-[#0EC774] bg-gray-50 focus:bg-white" 
                                placeholder="••••••••">
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center cursor-pointer" onclick="togglePassword('password_confirmation', 'toggleConfirmPasswordIcon')">
                                <i class="fa-regular fa-eye text-gray-400 text-lg" id="toggleConfirmPasswordIcon"></i>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>
                    @else
                        <input type="hidden" name="password" value="google_auth_no_password">
                        <input type="hidden" name="password_confirmation" value="google_auth_no_password">
                        <div class="bg-green-50 text-green-700 p-3 rounded-lg text-sm border border-green-200">
                            <i class="fa-solid fa-check-circle mr-2"></i> Password tidak diperlukan karena Anda mendaftar menggunakan Google.
                        </div>
                    @endif
                </div>

                <div>
                    <button type="submit" class="group relative w-full flex justify-center py-3.5 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-gradient-to-r from-[#008159] to-[#0EC774] hover:from-[#0EC774] hover:to-[#76E47E] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0EC774] shadow-lg shadow-[#008159]/20 transition-all duration-300 transform hover:-translate-y-0.5">
                        Daftar Sekarang
                    </button>
                </div>
                
                <div class="text-center mt-4">
                    <p class="text-sm text-gray-600">
                        Sudah punya akun? 
                        <a href="{{ route('login') }}" class="font-medium text-[#008159] hover:text-[#0EC774] transition-colors">
                            Masuk disini
                        </a>
                    </p>
                </div>
            </form>
            <div class="mt-8 text-center text-xs text-gray-400">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </div>
        </div>
    </div>
<script>
function togglePassword(inputId, iconId) {
    const pwd = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (pwd.type === 'password') {
        pwd.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        pwd.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
</body>
</html>
