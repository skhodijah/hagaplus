<x-admin-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Action Buttons -->
            <div class="flex justify-between items-center mb-6 no-print">
                <a href="{{ route('admin.payroll.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Back to List
                </a>
                <div class="flex space-x-2">
                    <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                        <i class="fa-solid fa-print mr-2"></i> Print PDF
                    </button>
                    <a href="{{ route('admin.payroll.edit', $payroll) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                        <i class="fa-solid fa-edit mr-2"></i> Edit
                    </a>
                    <form method="POST" action="{{ route('admin.payroll.destroy', $payroll) }}" onsubmit="return confirm('Are you sure you want to delete this payroll record?')" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            <i class="fa-solid fa-trash mr-2"></i> Delete
                        </button>
                    </form>
                </div>
            </div>

            <!-- Payslip (Print View) -->
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
</x-admin-layout>