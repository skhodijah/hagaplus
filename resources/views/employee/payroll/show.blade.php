<x-employee-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6 no-print">
                <a href="{{ route('employee.payroll.index') }}" class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
                </a>
                <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
                    <i class="fa-solid fa-download mr-2"></i> Unduh PDF
                </button>
            </div>

            <!-- Payslip Preview -->
            <div class="payslip-container">
                @include('admin.payroll.print-content', ['payroll' => $payroll])
            </div>
        </div>
    </div>

    <style>
        @media print {
            /* Hide UI elements */
            .no-print { display: none !important; }
            
            /* Reset body for print */
            body { 
                background: white !important;
            }
            
            /* Ensure payslip is visible */
            .payslip-container {
                display: block !important;
            }
        }
    </style>
</x-employee-layout>
