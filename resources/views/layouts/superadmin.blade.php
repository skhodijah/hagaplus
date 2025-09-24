<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Super Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">
    <div class="min-h-screen">
        <!-- Sidebar -->
        <aside class="hidden md:flex md:w-64 flex-col border-r border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-950 fixed inset-y-0 left-0 z-20">
            <div class="h-16 flex items-center px-6 border-b border-gray-200 dark:border-gray-800">
                <a href="{{ route('superadmin.dashboard') }}" class="flex items-center space-x-2">
                    <img src="{{ asset('images/Haga.png') }}" alt="Haga+" class="h-8 w-auto">
                    <span class="font-semibold text-gray-900 dark:text-gray-100">Haga+</span>
                </a>
            </div>
            <nav class="flex-1 p-4 space-y-1">
                <a href="{{ route('superadmin.dashboard') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('superadmin.dashboard') ? 'bg-gray-100 text-gray-900 dark:bg-gray-800 dark:text-white' : 'text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-800' }}">Dashboard</a>
                <a href="{{ route('superadmin.instansi.index') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('superadmin.instansi.*') ? 'bg-gray-100 text-gray-900 dark:bg-gray-800 dark:text-white' : 'text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-800' }}">Instansi</a>
                <a href="{{ route('superadmin.packages.index') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('superadmin.packages.*') ? 'bg-gray-100 text-gray-900 dark:bg-gray-800 dark:text-white' : 'text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-800' }}">Packages</a>
                <a href="{{ route('superadmin.subscriptions.index') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('superadmin.subscriptions.*') ? 'bg-gray-100 text-gray-900 dark:bg-gray-800 dark:text-white' : 'text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-800' }}">Subscriptions</a>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <div class="md:pl-64 flex flex-col min-h-screen">
            <!-- Header -->
            <header class="h-16 flex items-center justify-between px-4 md:px-6 border-b border-gray-200 dark:border-gray-800 bg-white/80 dark:bg-gray-950/80 backdrop-blur supports-[backdrop-filter]:bg-white/60 dark:supports-[backdrop-filter]:bg-gray-950/60 sticky top-0 z-10">
                <div class="flex items-center gap-3">
                    <button type="button" x-data="{}" @click="document.getElementById('mobileSidebar').classList.remove('hidden')" class="md:hidden inline-flex items-center justify-center rounded-md p-2 text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <a href="{{ route('superadmin.dashboard') }}" class="flex items-center space-x-2 md:hidden">
                        <img src="{{ asset('images/Haga.png') }}" alt="Haga+" class="h-8 w-auto">
                        <span class="font-semibold text-gray-900 dark:text-gray-100">Haga+</span>
                    </a>
                </div>
                <div class="flex items-center space-x-3">
                    <button type="button" onclick="window.toggleTheme()" class="inline-flex items-center justify-center rounded-md p-2 text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800" aria-label="Toggle theme">
                        <svg class="h-5 w-5 hidden dark:block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M21.752 15.002A9.718 9.718 0 0112 21.75 9.75 9.75 0 1118.998 2.248a.75.75 0 01.073 1.393 7.5 7.5 0 102.288 10.968.75.75 0 01.393.393z"/></svg>
                        <svg class="h-5 w-5 dark:hidden" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 18a6 6 0 100-12 6 6 0 000 12z"/><path fill-rule="evenodd" d="M12 2.25a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0V3a.75.75 0 01.75-.75zm0 16.5a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5a.75.75 0 01.75-.75zM4.72 4.72a.75.75 0 011.06 0l1.06 1.06a.75.75 0 11-1.06 1.06L4.72 5.78a.75.75 0 010-1.06zm12.38 12.38a.75.75 0 010-1.06l1.06-1.06a.75.75 0 111.06 1.06l-1.06 1.06a.75.75 0 01-1.06 0zM2.25 12a.75.75 0 01.75-.75h1.5a.75.75 0 010 1.5H3a.75.75 0 01-.75-.75zm16.5 0a.75.75 0 01.75-.75h1.5a.75.75 0 010 1.5h-1.5a.75.75 0 01-.75-.75zM4.72 19.28a.75.75 0 010-1.06l1.06-1.06a.75.75 0 111.06 1.06L5.78 19.28a.75.75 0 01-1.06 0zm12.38-12.38a.75.75 0 010-1.06l1.06-1.06a.75.75 0 111.06 1.06l-1.06 1.06a.75.75 0 01-1.06 0z" clip-rule="evenodd"/></svg>
                    </button>

                    <div class="hidden sm:flex items-center space-x-4">
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ Auth::user()->name }}</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400 bg-gray-200 dark:bg-gray-800 px-2 py-1 rounded">{{ ucfirst(Auth::user()->role) }}</span>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm">{{ __('Logout') }}</button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="p-4 md:p-6">
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Mobile Sidebar -->
    <div id="mobileSidebar" class="fixed inset-0 z-20 hidden" x-data="{}">
        <div class="absolute inset-0 bg-black/50" @click="$el.parentElement.classList.add('hidden')"></div>
        <div class="relative h-full w-72 bg-white dark:bg-gray-950 border-r border-gray-200 dark:border-gray-800 p-4">
            <div class="flex items-center justify-between h-12">
                <a href="{{ route('superadmin.dashboard') }}" class="flex items-center space-x-2">
                    <img src="{{ asset('images/Haga.png') }}" alt="Haga+" class="h-7 w-auto">
                    <span class="font-semibold text-gray-900 dark:text-gray-100">Haga+</span>
                </a>
                <button class="p-2 rounded-md text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800" @click="$el.closest('#mobileSidebar').classList.add('hidden')">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <nav class="mt-4 space-y-1">
                <a href="{{ route('superadmin.dashboard') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('superadmin.dashboard') ? 'bg-gray-100 text-gray-900 dark:bg-gray-800 dark:text-white' : 'text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-800' }}">Dashboard</a>
                <a href="{{ route('superadmin.instansi.index') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('superadmin.instansi.*') ? 'bg-gray-100 text-gray-900 dark:bg-gray-800 dark:text-white' : 'text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-800' }}">Instansi</a>
                <a href="{{ route('superadmin.packages.index') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('superadmin.packages.*') ? 'bg-gray-100 text-gray-900 dark:bg-gray-800 dark:text-white' : 'text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-800' }}">Packages</a>
                <a href="{{ route('superadmin.subscriptions.index') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('superadmin.subscriptions.*') ? 'bg-gray-100 text-gray-900 dark:bg-gray-800 dark:text-white' : 'text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-800' }}">Subscriptions</a>
                <div class="pt-4 border-t border-gray-200 dark:border-gray-800 mt-4">
                    <button type="button" onclick="window.toggleTheme()" class="w-full inline-flex items-center justify-between rounded-md px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800">
                        <span>Toggle Theme</span>
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 18a6 6 0 100-12 6 6 0 000 12z"/></svg>
                    </button>
                    <form method="POST" action="{{ route('logout') }}" class="mt-3">
                        @csrf
                        <button type="submit" class="w-full bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm">{{ __('Logout') }}</button>
                    </form>
                </div>
            </nav>
        </div>
    </div>
</body>
</html>
