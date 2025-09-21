<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Haga Plus</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans">

    <!-- Navbar -->
    <nav class="bg-blue-600 p-4 text-white">
        <h1 class="text-xl font-bold">Haga Plus Dashboard</h1>
    </nav>

    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow h-screen p-4">
            <ul class="space-y-2">
                <li><a href="#" class="block p-2 hover:bg-blue-100 rounded">🏠 Dashboard</a></li>
                <li><a href="#" class="block p-2 hover:bg-blue-100 rounded">👨‍💼 Karyawan</a></li>
                <li><a href="#" class="block p-2 hover:bg-blue-100 rounded">🕒 Absensi</a></li>
                <li><a href="#" class="block p-2 hover:bg-blue-100 rounded">💰 Penggajian</a></li>
                <li><a href="#" class="block p-2 hover:bg-blue-100 rounded">🏢 Instansi</a></li>
                <li><a href="#" class="block p-2 hover:bg-blue-100 rounded">📦 Paket</a></li>
            </ul>
        </aside>

        <!-- Konten -->
        <main class="flex-1 p-6">
            <h2 class="text-2xl font-semibold mb-4">Ringkasan</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-4 shadow rounded">
                    <h3 class="text-lg font-medium">Jumlah Karyawan</h3>
                    <p class="text-3xl font-bold text-blue-600">120</p>
                </div>
                <div class="bg-white p-4 shadow rounded">
                    <h3 class="text-lg font-medium">Absensi Hari Ini</h3>
                    <p class="text-3xl font-bold text-green-600">110</p>
                </div>
                <div class="bg-white p-4 shadow rounded">
                    <h3 class="text-lg font-medium">Paket Aktif</h3>
                    <p class="text-3xl font-bold text-purple-600">Pro</p>
                </div>
            </div>
        </main>
    </div>

</body>
</html>
