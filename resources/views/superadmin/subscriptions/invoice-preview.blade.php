<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $instansi->nama_instansi ?? 'N/A' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
    <style>
        :root {
            --shamrock: #049460;
            --emerald: #10C874;
        }
        
        @page { 
            size: A4 portrait; 
            margin: 15mm;
        }
        body { 
            background: white;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
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
    </style>
</head>
<body>
    <!-- Print Button (Screen Only) -->
    <div class="no-print" style="text-align: center; padding: 12px; background: white; border-bottom: 1px solid #e5e7eb; position: sticky; top: 0; z-index: 50;">
        <button onclick="window.print()" style="padding: 10px 24px; background: var(--shamrock); color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600;">
            🖨️ Print Invoice
        </button>
    </div>

    <!-- Invoice Content -->
    <div style="max-width: 210mm; margin: 0 auto; padding: 20px; background: white;">
        <!-- Header -->
        <table style="width: 100%; margin-bottom: 20px; border-bottom: 2px solid var(--shamrock); padding-bottom: 15px;">
            <tr>
                <td style="width: 50%; vertical-align: top;">
                    <img src="{{ asset('images/Haga.png') }}" alt="HagaPlus" style="height: 45px; margin-bottom: 8px;">
                    <p style="margin: 0; font-size: 11px; color: #6b7280;">HR Management System</p>
                    <p style="margin: 0; font-size: 11px; color: #6b7280;">support@hagaplus.com</p>
                </td>
                <td style="width: 50%; text-align: right; vertical-align: top;">
                    <h1 style="margin: 0; font-size: 28px; font-weight: bold; color: var(--shamrock);">INVOICE</h1>
                    <p style="margin: 4px 0 0 0; font-size: 12px; color: #6b7280; font-weight: 600;">{{ $invoiceNumber }}</p>
                    <p style="margin: 2px 0 0 0; font-size: 11px; color: #6b7280;">{{ now()->format('d M Y') }}</p>
                </td>
            </tr>
        </table>

        <!-- Bill To & From -->
        <table style="width: 100%; margin-bottom: 20px;">
            <tr>
                <td style="width: 50%; vertical-align: top; padding-right: 20px;">
                    <p style="margin: 0 0 8px 0; font-size: 10px; font-weight: 600; color: var(--shamrock); text-transform: uppercase;">Bill To:</p>
                    <p style="margin: 0; font-size: 14px; font-weight: 700; color: #111827;">{{ $instansi->nama_instansi ?? 'N/A' }}</p>
                    @if($instansi->email)
                    <p style="margin: 4px 0 0 0; font-size: 11px; color: #4b5563;">📧 {{ $instansi->email }}</p>
                    @endif
                    @if($instansi->phone)
                    <p style="margin: 2px 0 0 0; font-size: 11px; color: #4b5563;">📱 {{ $instansi->phone }}</p>
                    @endif
                </td>
                <td style="width: 50%; vertical-align: top; padding-left: 20px;">
                    <p style="margin: 0 0 8px 0; font-size: 10px; font-weight: 600; color: var(--shamrock); text-transform: uppercase;">From:</p>
                    <p style="margin: 0; font-size: 14px; font-weight: 700; color: #111827;">HagaPlus</p>
                    <p style="margin: 4px 0 0 0; font-size: 11px; color: #4b5563;">PT HagaPlus Indonesia</p>
                    <p style="margin: 2px 0 0 0; font-size: 11px; color: #4b5563;">support@hagaplus.com</p>
                </td>
            </tr>
        </table>

        <!-- Invoice Items -->
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <thead>
                <tr style="background: var(--shamrock); color: white;">
                    <th style="padding: 10px; text-align: left; font-size: 11px; font-weight: 600;">DESCRIPTION</th>
                    <th style="padding: 10px; text-align: center; font-size: 11px; font-weight: 600;">PERIOD</th>
                    <th style="padding: 10px; text-align: right; font-size: 11px; font-weight: 600;">AMOUNT</th>
                </tr>
            </thead>
            <tbody>
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 12px 10px;">
                        <p style="margin: 0; font-weight: 600; font-size: 13px;">{{ $package->name ?? 'N/A' }} Subscription</p>
                        <p style="margin: 2px 0 0 0; font-size: 11px; color: #6b7280;">Monthly subscription plan</p>
                    </td>
                    <td style="padding: 12px 10px; text-align: center; font-size: 11px; color: #4b5563;">
                        {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}
                    </td>
                    <td style="padding: 12px 10px; text-align: right; font-weight: 600; font-size: 13px;">
                        Rp {{ number_format($price, 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Summary -->
        <table style="width: 100%; margin-bottom: 20px;">
            <tr>
                <td style="width: 60%;"></td>
                <td style="width: 40%;">
                    <table style="width: 100%; font-size: 12px;">
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 8px 0; color: #6b7280;">Subtotal</td>
                            <td style="padding: 8px 0; text-align: right; font-weight: 500;">Rp {{ number_format($price, 0, ',', '.') }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 8px 0; color: #6b7280;">Tax (0%)</td>
                            <td style="padding: 8px 0; text-align: right; font-weight: 500;">Rp 0</td>
                        </tr>
                        <tr style="background: #f9fafb;">
                            <td style="padding: 10px 8px; font-weight: 700; font-size: 14px;">TOTAL</td>
                            <td style="padding: 10px 8px; text-align: right; font-weight: 700; font-size: 16px; color: var(--shamrock);">Rp {{ number_format($price, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Payment Methods -->
        <div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 15px; margin-bottom: 20px;">
            <h3 style="margin: 0 0 12px 0; font-size: 13px; font-weight: 600; color: var(--shamrock);">💳 Payment Information</h3>
            
            @php
                $bankTransfer = $paymentMethods->where('type', 'bank_transfer')->first();
                $qris = $paymentMethods->where('type', 'qris')->first();
            @endphp

            <table style="width: 100%;">
                <tr>
                    <!-- Bank Transfer -->
                    @if($bankTransfer)
                    <td style="width: {{ $qris ? '50%' : '100%' }}; vertical-align: top; padding-right: {{ $qris ? '15px' : '0' }}; {{ $qris ? 'border-right: 1px solid #e5e7eb;' : '' }}">
                        <p style="margin: 0 0 8px 0; font-size: 11px; font-weight: 600; color: #6b7280; text-transform: uppercase;">Bank Transfer</p>
                        <table style="width: 100%; font-size: 11px;">
                            <tr>
                                <td style="padding: 4px 0; color: #6b7280; width: 40%;">Bank</td>
                                <td style="padding: 4px 0; font-weight: 600;">{{ $bankTransfer->bank_name }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 4px 0; color: #6b7280;">Account No.</td>
                                <td style="padding: 4px 0; font-weight: 600; font-family: monospace;">{{ $bankTransfer->account_number }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 4px 0; color: #6b7280;">Account Name</td>
                                <td style="padding: 4px 0; font-weight: 600;">{{ $bankTransfer->account_name }}</td>
                            </tr>
                        </table>
                    </td>
                    @endif

                    <!-- QRIS -->
                    @if($qris)
                    <td style="width: {{ $bankTransfer ? '50%' : '100%' }}; vertical-align: top; padding-left: {{ $bankTransfer ? '15px' : '0' }}; text-align: center;">
                        <p style="margin: 0 0 8px 0; font-size: 11px; font-weight: 600; color: #6b7280; text-transform: uppercase;">QRIS Payment</p>
                        <div id="qrcode-container" style="display: inline-block; padding: 8px; background: white; border: 1px solid #e5e7eb; border-radius: 6px;">
                            <div id="qrcode" style="width: 150px; height: 150px;"></div>
                        </div>
                        <p style="margin: 6px 0 0 0; font-size: 10px; color: #6b7280;">Scan to pay Rp {{ number_format($price, 0, ',', '.') }}</p>
                    </td>
                    @endif
                </tr>
            </table>
        </div>

        <!-- Footer -->
        <div style="text-align: center; padding-top: 15px; border-top: 1px solid #e5e7eb;">
            <p style="margin: 0; font-size: 12px; color: var(--shamrock); font-weight: 600;">Thank you for your business!</p>
            <p style="margin: 6px 0 0 0; font-size: 10px; color: #9ca3af;">This is a computer-generated invoice. No signature required.</p>
        </div>
    </div>

    <script>
        @if($qris && $qris->qris_data)
        // QRIS Generation Functions
        function pad(number) {
            return number < 10 ? "0" + number : number.toString();
        }

        function toCRC16(input) {
            let crc = 0xffff;
            for (let i = 0; i < input.length; i++) {
                crc ^= input.charCodeAt(i) << 8;
                for (let j = 0; j < 8; j++) {
                    crc = crc & 0x8000 ? (crc << 1) ^ 0x1021 : crc << 1;
                }
            }
            let hex = (crc & 0xffff).toString(16).toUpperCase();
            return hex.length === 3 ? "0" + hex : hex;
        }

        function makeString(qris, { nominal } = {}) {
            if (!qris || !nominal) return null;
            
            let qrisModified = qris.slice(0, -4).replace("010211", "010212");
            let qrisParts = qrisModified.split("5802ID");
            
            if (qrisParts.length < 2) return qris;
            
            let amount = "54" + pad(nominal.toString().length) + nominal;
            amount += "5802ID";
            
            let output = qrisParts[0].trim() + amount + qrisParts[1].trim();
            output += toCRC16(output);
            
            return output;
        }

        // Generate QRIS on load
        window.onload = function() {
            const qrisData = '{{ $qris->qris_data }}';
            const amount = {{ $price }};
            
            try {
                const qrisDinamis = makeString(qrisData, { nominal: amount });
                if (qrisDinamis) {
                    QRCode.toCanvas(
                        qrisDinamis,
                        { margin: 1, width: 150, color: { dark: "#000000", light: "#ffffff" } },
                        function (err, canvas) {
                            if (!err) {
                                const container = document.getElementById('qrcode');
                                if (container) {
                                    container.innerHTML = '';
                                    container.appendChild(canvas);
                                }
                            } else {
                                console.error("QR Code Error:", err);
                            }
                        }
                    );
                }
            } catch (e) {
                console.error("Error generating QRIS:", e);
            }
        };
        @endif
    </script>
</body>
</html>
