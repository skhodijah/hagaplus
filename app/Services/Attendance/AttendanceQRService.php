<?php

namespace App\Services\Attendance;

use App\Models\Admin\Branch;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AttendanceQRService
{
    public function generateQRCode(Branch $branch)
    {
        $data = [
            'branch_id' => $branch->id,
            'timestamp' => now()->timestamp,
            'type' => 'attendance'
        ];

        $qrData = base64_encode(json_encode($data));

        return QrCode::size(200)->generate($qrData);
    }

    public function validateQRCode($qrData)
    {
        try {
            $data = json_decode(base64_decode($qrData), true);

            if (!$data || !isset($data['branch_id'], $data['timestamp'], $data['type'])) {
                return false;
            }

            // Check if QR code is not too old (e.g., 1 hour)
            $timestamp = $data['timestamp'];
            if (now()->timestamp - $timestamp > 3600) {
                return false;
            }

            return $data;
        } catch (\Exception $e) {
            return false;
        }
    }
}
