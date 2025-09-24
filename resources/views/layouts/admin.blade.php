<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

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
                            <a href="{{ route('admin.dashboard') }}">
                                <x-application-logo class="block h-10 w-auto fill-current text-gray-600" />
                            </a>
                        </div>
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                                {{ __('Dashboard') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.employees.index')" :active="request()->routeIs('admin.employees.*')">
                                {{ __('Employees') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.attendance.index')" :active="request()->routeIs('admin.attendance.*')">
                                {{ __('Attendance') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.payroll.index')" :active="request()->routeIs('admin.payroll.*')">
                                {{ __('Payroll') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.branches.index')" :active="request()->routeIs('admin.branches.*')">
                                {{ __('Branches') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.settings.index')" :active="request()->routeIs('admin.settings.*')">
                                {{ __('Settings') }}
                            </x-nav-link>
                        </div>
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
