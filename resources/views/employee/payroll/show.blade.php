<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji - {{ $payroll->user->name }} - {{ \Carbon\Carbon::createFromDate($payroll->period_year, $payroll->period_month, 1)->format('F Y') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @page { 
            size: A4 portrait; 
            margin: 0;
        }
        body { 
            background: white;
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            .payslip-container {
                margin: 0 !important;
                padding: 0 !important;
            }
        }
    </style>
</head>
<body>
    <!-- Action Buttons (Screen Only) -->
    <div class="no-print sticky top-0 z-50 bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <a href="{{ route('employee.payroll.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors shadow-sm">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Back to List
                </a>
                <button onclick="window.print()" class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-sm font-medium">
                    <i class="fa-solid fa-print mr-2"></i> Print
                </button>
            </div>
        </div>
    </div>

    <!-- Payslip Content (Full Screen) -->
    <div class="payslip-container">
        @include('admin.payroll.print-content', ['payroll' => $payroll])
    </div>
</body>
</html>
