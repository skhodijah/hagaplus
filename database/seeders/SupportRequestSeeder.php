<?php

namespace Database\Seeders;

use App\Models\SuperAdmin\Instansi;
use App\Models\SuperAdmin\SupportRequest;
use App\Models\Core\User;
use Illuminate\Database\Seeder;

class SupportRequestSeeder extends Seeder
{
    public function run(): void
    {
        $instansis = Instansi::all();
        $users = User::where('role', 'admin')->get();

        $supportRequests = [
            [
                'instansi_id' => $instansis->first()?->id,
                'user_id' => $users->first()?->id,
                'subject' => 'Masalah Login Sistem',
                'message' => 'Admin tidak dapat login ke sistem setelah update password. Sistem menampilkan error "Invalid credentials".',
                'priority' => 'high',
                'status' => 'open',
                'category' => 'technical_issue',
            ],
            [
                'instansi_id' => $instansis->skip(1)->first()?->id,
                'user_id' => $users->skip(1)->first()?->id,
                'subject' => 'Permintaan Fitur Tambahan',
                'message' => 'Mohon tambahkan fitur export data attendance dalam format Excel dengan filter tanggal custom.',
                'priority' => 'medium',
                'status' => 'in_progress',
                'category' => 'feature_request',
            ],
            [
                'instansi_id' => $instansis->skip(2)->first()?->id,
                'user_id' => $users->skip(2)->first()?->id,
                'subject' => 'Laporan Bug pada Mobile App',
                'message' => 'Aplikasi mobile force close ketika user mencoba check-in menggunakan QR code. Terjadi pada Android version 12.',
                'priority' => 'high',
                'status' => 'resolved',
                'category' => 'bug_report',
            ],
            [
                'instansi_id' => $instansis->skip(3)->first()?->id,
                'user_id' => $users->skip(3)->first()?->id,
                'subject' => 'Konfirmasi Pembayaran Langganan',
                'message' => 'Sudah transfer pembayaran langganan bulan ini sebesar Rp 100.000 pada tanggal 15. Mohon konfirmasi.',
                'priority' => 'medium',
                'status' => 'closed',
                'category' => 'billing',
            ],
            [
                'instansi_id' => $instansis->skip(4)->first()?->id,
                'user_id' => $users->skip(4)->first()?->id,
                'subject' => 'Permintaan Training',
                'message' => 'Tim HR memerlukan training penggunaan sistem payroll dan attendance management. Berapa biaya training nya?',
                'priority' => 'low',
                'status' => 'open',
                'category' => 'training',
            ],
        ];

        foreach ($supportRequests as $request) {
            if ($request['instansi_id'] && $request['user_id']) {
                SupportRequest::create($request);
            }
        }
    }
}