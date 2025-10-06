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
    <script src="https://kit.fontawesome.com/8c8ccf764d.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />


    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-700">
        <!-- Navigation -->
        <nav class="bg-white dark:bg-gray-800 shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('employee.dashboard') }}" class="flex items-center space-x-3">
                                <img src="{{ asset('images/Haga.png') }}" alt="Haga+" class="w-8 h-8">
                                <span class="text-xl font-semibold text-gray-900 dark:text-white">Haga+</span>
                            </a>
                        </div>
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <x-nav-link :href="route('employee.dashboard')"
                                :active="request()->routeIs('employee.dashboard')">
                                {{ __('Dashboard') }}
                            </x-nav-link>
                            <x-nav-link :href="route('employee.attendance.index')"
                                :active="request()->routeIs('employee.attendance.*')">
                                {{ __('Attendance') }}
                            </x-nav-link>
                            <x-nav-link :href="route('employee.payroll.index')"
                                :active="request()->routeIs('employee.payroll.*')">
                                {{ __('Payroll') }}
                            </x-nav-link>
                        </div>
                    </div>

                    <!-- Right side - User menu with logout -->
                    <div class="flex items-center space-x-0 sm:space-x-4">

                        <button data-theme-toggle
                            class="p-2 rounded-lg text-gray-500 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200"
                            title="Toggle Theme">
                            <span class="block dark:hidden"><i class="fa-solid fa-moon"></i></span>
                            <span class="hidden dark:block"><i class="fa-solid fa-sun"></i></span>
                        </button>
                        <div class="hidden md:flex items-center space-x-2">
                            <span class="text-sm text-gray-700 dark:text-white">{{ Auth::user()->name }}</span>
                            <span
                                class="text-xs text-gray-900 bg-gray-200 px-2 py-1 rounded">{{ ucfirst(Auth::user()->role) }}</span>
                        </div>

                        <!-- Logout Button -->
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit"
                                class="hidden sm:block bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm">
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