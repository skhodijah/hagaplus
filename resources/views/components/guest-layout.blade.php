<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'HagaPlus') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
    <!-- Left side image (desktop only) -->
    <div class="hidden lg:flex w-1/2 relative bg-gray-900">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('images/login-bg.png') }}');"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-black/70 to-black/30"></div>
        <div class="relative z-10 flex flex-col justify-center p-16 text-white h-full">
            <h2 class="text-5xl font-bold mb-6 leading-tight">Kelola Absensi<br>Lebih Efisien</h2>
            <p class="text-xl text-gray-200 max-w-lg leading-relaxed">Platform manajemen kehadiran karyawan yang modern, akurat, dan mudah digunakan untuk produktivitas bisnis Anda.</p>
        </div>
    </div>
    <!-- Right side content -->
    <div class="w-full lg:w-1/2 flex flex-col justify-center items-center p-8 bg-white relative overflow-y-auto">
        <div class="w-full max-w-md space-y-8 py-12">
            {{ $slot }}
        </div>
    </div>

</body>
</html>
