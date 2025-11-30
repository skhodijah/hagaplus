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
                margin: 0 !important;
                padding: 0 !important;
            }
        }
        @media screen {
            body {
                background: #f3f4f6;
                padding: 20px 0;
            }
        }
    </style>
</head>
<body>
    <!-- Print Button (Screen Only) -->
    <div class="no-print" style="text-align: center; padding: 15px; background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1); position: sticky; top: 0; z-index: 50; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 12px 32px; background: #2563eb; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 15px; font-weight: 600; box-shadow: 0 2px 4px rgba(37,99,235,0.3);">
            <i class="fas fa-print" style="margin-right: 8px;"></i> Cetak Slip Gaji
        </button>
    </div>

    <!-- Payslip Content (Original Design) -->
    @include('admin.payroll.print-content', ['payroll' => $payroll])

    <script>
        // Optional: Auto print on load
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
