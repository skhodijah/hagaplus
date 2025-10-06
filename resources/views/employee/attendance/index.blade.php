<x-employee-layout>
    <div class="py-0 sm:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-white">
                    <h1 class="text-2xl font-bold mb-6">Employee Attendance</h1>

                    @include('employee.partials.status')

                    <div class="grid mt-5 grid-cols-1 gap-6">

                        <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow-md">
                            <h3 class="text-lg font-semibold mb-4">Scan QR Code for Attendance</h3>
                            <div class="relative">
                                <!-- QR Scanner Placeholder -->
                                <div id="qr-placeholder"
                                    class="w-full aspect-video bg-gray-100 dark:bg-gray-600 rounded-lg flex flex-col items-center justify-center">
                                    <i class="fas fa-qrcode text-6xl text-gray-400 dark:text-gray-500 mb-4"></i>
                                    <p class="text-gray-500 dark:text-gray-400">Click "Start Scanning" to activate
                                        camera</p>
                                </div>
                                <!-- QR Scanner Video -->
                                <div id="video-wrapper" class="hidden">
                                    <video id="qr-video" class="w-full rounded-lg"></video>
                                </div>
                            </div>
                            <div class="mt-4 flex space-x-4">
                                <button id="start-scan"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Start Scanning
                                </button>
                                <button id="stop-scan"
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded hidden">
                                    Stop Scanning
                                </button>
                            </div>
                            <p id="scan-error" class="mt-2 text-red-500 hidden"></p>
                            <p id="scan-result" class="mt-2 text-green-500 hidden"></p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    @vite(['resources/js/pages/attendance.js'])
</x-employee-layout>