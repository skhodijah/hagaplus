<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'HagaPlus') }} - Lupa Password</title>
    
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
    <!-- Right Side - Form -->
    <div class="w-full lg:w-1/2 flex flex-col justify-center items-center p-8 bg-white relative overflow-y-auto">
        <a href="{{ route('login') }}" class="absolute top-8 left-8 text-gray-500 hover:text-[#008159] transition-colors flex items-center gap-2 group">
            <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            <span class="font-medium">Kembali</span>
        </a>
        <div class="w-full max-w-md space-y-8 py-12">
            <div class="text-center mb-6">
                <img src="{{ asset('images/Haga.png') }}" alt="Haga Logo" class="h-24 w-auto object-contain drop-shadow-md mx-auto mb-4">
                <h2 class="text-2xl font-bold text-gray-900">Lupa Password</h2>
                <p class="text-gray-600 mt-2">Masukkan email Anda untuk menerima tautan reset password.</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email Address</label>
                    <div class="relative rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-regular fa-envelope text-gray-400 text-lg"></i>
                        </div>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                            class="block w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0EC774]/50 focus:border-[#0EC774] bg-gray-50 focus:bg-white" 
                            placeholder="nama@perusahaan.com">
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
                
                <div class="flex items-center justify-between pt-2">
                    <a href="{{ route('login') }}" class="text-sm font-medium text-[#008159] hover:text-[#0EC774] transition-colors">Kembali ke Login</a>
                    <button type="submit" class="group relative flex justify-center py-3 px-6 border border-transparent text-sm font-bold rounded-xl text-white bg-gradient-to-r from-[#008159] to-[#0EC774] hover:from-[#0EC774] hover:to-[#76E47E] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0EC774] shadow-lg shadow-[#008159]/20 transition-all duration-300 transform hover:-translate-y-0.5">
                        {{ __('Kirim Link Reset') }}
                    </button>
                </div>
            </form>
            
            <div class="mt-8 text-center text-xs text-gray-400">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>
