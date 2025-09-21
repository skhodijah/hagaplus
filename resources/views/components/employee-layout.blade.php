<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Employee</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Navigation -->
        <nav class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('employee.dashboard') }}">
                                <x-application-logo class="block h-10 w-auto fill-current text-gray-600" />
                            </a>
                        </div>
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <x-nav-link :href="route('employee.dashboard')" :active="request()->routeIs('employee.dashboard')">
                                {{ __('Dashboard') }}
                            </x-nav-link>
                            <x-nav-link :href="route('employee.attendance.index')" :active="request()->routeIs('employee.attendance.*')">
                                {{ __('Attendance') }}
                            </x-nav-link>
                            <x-nav-link :href="route('employee.payroll.index')" :active="request()->routeIs('employee.payroll.*')">
                                {{ __('Payroll') }}
                            </x-nav-link>
                        </div>
                    </div>
                    
                    <!-- Right side - User menu with logout -->
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-700">{{ Auth::user()->name }}</span>
                            <span class="text-xs text-gray-500 bg-gray-200 px-2 py-1 rounded">{{ ucfirst(Auth::user()->role) }}</span>
                        </div>
                        
                        <!-- Logout Button -->
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm">
                                {{ __('Logout') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
</body>
</html>