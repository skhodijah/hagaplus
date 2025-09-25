<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\SuperAdmin\Instansi;
use App\Models\SuperAdmin\Package;
use App\Models\SuperAdmin\Subscription;
use App\Models\Core\User;
use Carbon\Carbon;

class PublicRegistrationController extends Controller
{
    public function create()
    {
        return view('public.register-instansi');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Instansi fields
            'nama_instansi' => 'required|string|max:255',
            'subdomain' => 'required|string|max:50|alpha_dash|unique:instansis,subdomain',
            'email' => 'nullable|email|max:255|unique:instansis,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:2000',
            // User fields
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Ensure a Free package exists (price 0, duration 30 days, max_employees 5)
        $freePackage = Package::where('name', 'Free')->first();
        if (!$freePackage) {
            $freePackage = Package::create([
                'name' => 'Free',
                'description' => 'Paket gratis percobaan dengan fitur terbatas',
                'price' => 0,
                'duration_days' => 30,
                'max_employees' => 5,
                'max_branches' => 1,
                'features' => ['qr', 'basic_reports'],
                'is_active' => true,
            ]);
        }

        // Create instansi as active with Free package
        $instansi = Instansi::create([
            'nama_instansi' => $validated['nama_instansi'],
            'subdomain' => $validated['subdomain'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'package_id' => $freePackage->id,
            'is_active' => true,
            'status_langganan' => 'active',
            'max_employees' => 5,
            'max_branches' => 1,
        ]);

        // Create admin user for this instansi
        $user = User::create([
            'name' => $validated['user_name'],
            'email' => $validated['user_email'],
            'password' => Hash::make($validated['password']),
            'role' => 'admin',
            'instansi_id' => $instansi->id,
        ]);

        // Create active trial subscription 30 days
        $start = Carbon::now()->toDateString();
        $end = Carbon::now()->addDays(30)->toDateString();
        Subscription::create([
            'instansi_id' => $instansi->id,
            'package_id' => $freePackage->id,
            'status' => 'active',
            'start_date' => $start,
            'end_date' => $end,
            'price' => 0,
            'is_trial' => true,
            'trial_ends_at' => $end,
        ]);

        // Notify superadmin (user_id = 1) that a new instansi was auto-provisioned
        try {
            DB::table('notifications')->insert([
                'user_id' => 1,
                'title' => 'Instansi Baru (Free Trial) Terdaftar',
                'message' => 'Instansi "' . $instansi->nama_instansi . '" dibuat otomatis dengan paket Free dan admin ' . $user->email . '.',
                'type' => 'info',
                'is_read' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Throwable $e) {
            // ignore notification errors
        }

        return redirect()->route('login')
            ->with('status', 'Pendaftaran berhasil. Silakan login menggunakan email dan password yang Anda buat.');
    }
} 