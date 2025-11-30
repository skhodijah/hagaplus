<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Access Denied</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased bg-gray-50 dark:bg-gray-900 h-screen flex items-center justify-center overflow-hidden">
    <div class="relative w-full max-w-lg px-4 mx-auto text-center">
        <!-- Background Decoration -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-red-100 dark:bg-red-900/20 rounded-full blur-3xl opacity-50 pointer-events-none"></div>
        
        <div class="relative z-10">
            <!-- Icon -->
            <div class="w-24 h-24 mx-auto mb-6 bg-red-50 dark:bg-red-900/20 rounded-full flex items-center justify-center animate-bounce-slow">
                <i class="fa-solid fa-lock text-4xl text-red-500 dark:text-red-400"></i>
            </div>

            <!-- Text -->
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2 tracking-tight">Access Denied</h1>
            <p class="text-lg text-gray-600 dark:text-gray-400 mb-8">
                Sorry, you don't have permission to access this area.<br>
                Please contact your administrator if you believe this is a mistake.
            </p>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <button onclick="history.back()" class="w-full sm:w-auto px-6 py-3 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 flex items-center justify-center font-medium">
                    <i class="fa-solid fa-arrow-left mr-2"></i>
                    Go Back
                </button>
                
                <a href="{{ route('dashboard') }}" class="w-full sm:w-auto px-6 py-3 bg-blue-600 text-white rounded-xl shadow-lg shadow-blue-500/30 hover:bg-blue-700 hover:shadow-blue-500/40 transition-all duration-200 flex items-center justify-center font-medium transform hover:-translate-y-0.5">
                    <i class="fa-solid fa-gauge mr-2"></i>
                    Return to Dashboard
                </a>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="mt-12 text-sm text-gray-400 dark:text-gray-500">
            Error Code: 403 | Forbidden
        </div>
    </div>

    <style>
        @keyframes bounce-slow {
            0%, 100% { transform: translateY(-5%); }
            50% { transform: translateY(5%); }
        }
        .animate-bounce-slow {
            animation: bounce-slow 3s infinite ease-in-out;
        }
    </style>
</body>
</html>
