<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        @props(['header' => null])

        <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
            <!-- Guest Navigation -->
            <x-guest-navigation />

            <!-- Page Content -->
            <main class="py-12">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    @if (isset($header))
                        <div class="mb-8 text-center">
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $header }}</h1>
                        </div>
                    @endif

                    {{ $slot }}
                </div>
            </main>

            <!-- Footer -->
            <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 mt-12">
                <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                    <div class="md:flex md:items-center md:justify-between">
                        <div class="flex justify-center md:justify-start">
                            <a href="{{ route('home') }}" class="flex items-center">
                                <x-application-logo class="h-8 w-auto fill-current text-gray-800 dark:text-gray-200" />
                                <span class="ml-2 text-xl font-semibold text-gray-900 dark:text-white">{{ config('app.name') }}</span>
                            </a>
                        </div>
                        <div class="mt-8 md:mt-0">
                            <p class="text-center text-sm text-gray-500 dark:text-gray-400">
                                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                            </p>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
