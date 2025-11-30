<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Slip Gaji - {{ $payroll->user->name }} - {{ \Carbon\Carbon::createFromDate($payroll->period_year, $payroll->period_month, 1)->format('F Y') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @page { 
            size: A4 portrait; 
            margin: 12mm; 
        }
        body { 
            background: white; 
            font-size: 11px; 
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        @media print {
            body * {
                visibility: hidden;
            }
            #printable-area, #printable-area * {
                visibility: visible;
            }
            #printable-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 0;
                margin: 0;
            }
            #printBtn {
                display: none;
            }
        }
        @media screen {
            #printable-area {
                max-width: 210mm;
                margin: 0 auto;
                padding: 20px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
        }
    </style>
</head>
<body class="p-4">
    <div class="flex justify-end mb-4">
        <button id="printBtn" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
            <i class="fas fa-print mr-2"></i> Cetak Slip Gaji
        </button>
    </div>

    <div id="printable-area">
        @include('admin.payroll.print-content', ['payroll' => $payroll])
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const printBtn = document.getElementById('printBtn');
            
            if (printBtn) {
                printBtn.addEventListener('click', function() {
                    window.print();
                });
            }
            
            // Optional: Auto-print when the page loads
            // window.print();
        });
    </script>
</body>
</html>
