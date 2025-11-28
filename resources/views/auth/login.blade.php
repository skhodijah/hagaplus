<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'HagaPlus') }} - Login</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Custom Colors */
        :root {
            --sea-green: #008159;
            --emerald: #0EC774;
            --light-green: #76E47E;
            --lime-cream: #D2FE8C;
            --black: #000000;
        }
        
        /* Light Theme (Default) */
        body {
            background-color: #f3f4f6;
        }
        .theme-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        .theme-input {
            background-color: white;
            border-color: #e5e7eb;
            color: #111827;
        }
        .theme-input:focus {
            background-color: #f9fafb;
        }
        .theme-text-main { color: #111827; }
        .theme-text-sub { color: #4b5563; }
        .theme-icon { color: #9ca3af; }

        /* Dark Theme (Activated via JS) */
        html.dark body {
            background-color: var(--black);
        }
        html.dark .theme-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        html.dark .theme-input {
            background-color: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.1);
            color: white;
        }
        html.dark .theme-input:focus {
            background-color: rgba(255, 255, 255, 0.1);
        }
        html.dark .theme-text-main { color: white; }
        html.dark .theme-text-sub { color: #9ca3af; }
        html.dark .theme-icon { color: #6b7280; }
        
        /* Common Styles */
        body {
            font-family: 'Figtree', sans-serif;
        }

        .theme-card {
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }

        .input-group:focus-within i {
            color: var(--emerald);
        }

        .input-group:focus-within input {
            border-color: var(--emerald);
            box-shadow: 0 0 0 1px var(--emerald);
        }

        /* Liquid Animation */
        @keyframes drift {
            0% { transform: translate(0, 0); }
            50% { transform: translate(10px, 20px); }
            100% { transform: translate(0, 0); }
        }
    </style>
    <script>
        // Check localStorage for dark mode preference
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="min-h-screen flex items-center justify-center relative overflow-hidden selection:bg-[#0EC774] selection:text-black transition-colors duration-300">

    <!-- Background Elements -->
    <div class="absolute inset-0 z-0">
        <!-- Logo Background -->
        <div class="absolute inset-0 flex items-center justify-center opacity-10 pointer-events-none">
            <img src="{{ asset('images/Haga.png') }}" alt="Background" class="w-[120%] max-w-none h-auto object-cover opacity-20 grayscale brightness-100 dark:brightness-50">
        </div>
    </div>

    <!-- Back to Home Button -->
    <a href="{{ route('home') }}" class="absolute top-6 left-6 z-20 flex items-center gap-2 px-4 py-2 rounded-full theme-card theme-text-sub hover:text-[#0EC774] transition-all duration-300 group">
        <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
        <span class="font-medium">Back to Home</span>
    </a>

    <!-- Login Container -->
    <div class="relative z-10 w-full max-w-xl p-6 mx-4">
        <div class="theme-card rounded-3xl p-8 sm:p-12 shadow-2xl">
            
            <!-- Header -->
            <div class="text-center mb-10">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-gradient-to-br from-[#008159] to-[#0EC774] mb-6 shadow-lg shadow-[#0EC774]/20 transform hover:scale-105 transition-transform duration-300">
                    <img src="{{ asset('images/Haga.png') }}" alt="Logo" class="w-12 h-12 object-contain brightness-0 invert">
                </div>
                <h1 class="text-3xl font-bold theme-text-main mb-2 tracking-tight">Welcome Back</h1>
                <p class="theme-text-sub text-sm">Please sign in to continue to your dashboard</p>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email -->
                <div class="space-y-2">
                    <label for="email" class="text-sm font-medium theme-text-sub ml-1">Email Address</label>
                    <div class="relative input-group transition-all duration-300">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-regular fa-envelope theme-icon transition-colors duration-300"></i>
                        </div>
                        <input id="email" type="email" name="email" :value="old('email')" required autofocus 
                            class="w-full pl-11 pr-4 py-3 theme-input border rounded-xl placeholder-gray-400 focus:outline-none transition-all duration-300"
                            placeholder="name@company.com">
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="space-y-2">
                    <label for="password" class="text-sm font-medium theme-text-sub ml-1">Password</label>
                    <div class="relative input-group transition-all duration-300">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-lock theme-icon transition-colors duration-300"></i>
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                            class="w-full pl-11 pr-4 py-3 theme-input border rounded-xl placeholder-gray-400 focus:outline-none transition-all duration-300"
                            placeholder="••••••••">
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember & Forgot -->
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center theme-text-sub hover:text-[#0EC774] cursor-pointer transition-colors">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 dark:border-gray-600 bg-white dark:bg-white/5 text-[#0EC774] focus:ring-[#0EC774] focus:ring-offset-0">
                        <span class="ml-2">Remember me</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-[#008159] dark:text-[#76E47E] hover:text-[#0EC774] dark:hover:text-[#D2FE8C] font-medium transition-colors">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3.5 px-4 bg-gradient-to-r from-[#008159] to-[#0EC774] hover:from-[#0EC774] hover:to-[#76E47E] text-white font-bold rounded-xl shadow-lg shadow-[#008159]/30 transform hover:-translate-y-0.5 hover:shadow-[#0EC774]/40 transition-all duration-300 relative overflow-hidden group">
                    <span class="relative z-10">Sign In</span>
                    <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                </button>
            </form>
        </div>
        
        <!-- Footer -->
        <div class="text-center mt-8 text-xs theme-text-sub opacity-70">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>

    <style>
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        .animation-delay-4000 {
            animation-delay: 4s;
        }
    </style>
</body>
</html>
