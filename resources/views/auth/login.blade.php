<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'HagaPlus') }} - Login</title>
    
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
            <h2 class="text-5xl font-bold mb-6 leading-tight">Kelola Absensi<br>Lebih Efisien</h2>
            <p class="text-xl text-gray-200 max-w-lg leading-relaxed">Platform manajemen kehadiran karyawan yang modern, akurat, dan mudah digunakan untuk produktivitas bisnis Anda.</p>
            <div class="text-sm text-gray-400">Photo by AI Generated</div>
        </div>
    </div>
    <!-- Right Side - Login Form -->
    <div class="w-full lg:w-1/2 flex flex-col justify-center items-center p-8 bg-white relative overflow-y-auto">
        <a href="{{ route('home') }}" class="absolute top-8 left-8 text-gray-500 hover:text-[#008159] transition-colors flex items-center gap-2 group">
            <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            <span class="font-medium">Kembali</span>
        </a>
        <div class="w-full max-w-md space-y-8 py-12">
            <div class="text-center">
                <img src="{{ asset('images/Haga.png') }}" alt="Haga Logo" class="h-24 w-auto object-contain drop-shadow-md mx-auto mb-4">
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Selamat Datang di Haga+</h1>
                <p class="mt-3 text-gray-600 text-lg">Aplikasi Absensi Terpercaya</p>
            </div>
            <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-6">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email Address</label>
                        <div class="relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fa-regular fa-envelope text-gray-400 text-lg"></i>
                            </div>
                            <input id="email" name="email" type="email" autocomplete="email" required 
                                class="block w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0EC774]/50 focus:border-[#0EC774] bg-gray-50 focus:bg-white" 
                                placeholder="nama@perusahaan.com" value="{{ old('email') }}">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                        <div class="relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fa-solid fa-lock text-gray-400 text-lg"></i>
                            </div>
                            <input id="password" name="password" type="password" autocomplete="current-password" required 
                                class="block w-full pl-11 pr-11 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0EC774]/50 focus:border-[#0EC774] bg-gray-50 focus:bg-white" 
                                placeholder="••••••••">
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center cursor-pointer" onclick="togglePassword()">
                                <i class="fa-regular fa-eye text-gray-400 text-lg" id="togglePasswordIcon"></i>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-[#008159] focus:ring-[#0EC774] border-gray-300 rounded cursor-pointer">
                        <label for="remember_me" class="ml-2 block text-sm text-gray-600 cursor-pointer select-none">Ingat Saya</label>
                    </div>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm font-medium text-[#008159] hover:text-[#0EC774] transition-colors">Lupa Password?</a>
                    @endif
                </div>
                <div>
                    <button type="submit" class="group relative w-full flex justify-center py-3.5 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-gradient-to-r from-[#008159] to-[#0EC774] hover:from-[#0EC774] hover:to-[#76E47E] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0EC774] shadow-lg shadow-[#008159]/20 transition-all duration-300 transform hover:-translate-y-0.5">
                        Masuk
                    </button>
                    <div class="mt-4">
                        <button type="button" class="w-full flex items-center justify-center py-3.5 px-4 border border-gray-300 rounded-xl text-gray-800 bg-white hover:bg-gray-100 shadow-sm transition-colors">
                            <i class="fa-brands fa-google mr-2"></i> Login dengan Google
                        </button>
                    </div>
                </div>
            </form>
            <div class="mt-8 text-center text-xs text-gray-400">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </div>
        </div>
    </div>
<script>
function togglePassword() {
    const pwd = document.getElementById('password');
    const icon = document.getElementById('togglePasswordIcon');
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
