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
        $packages = Package::where('is_active', true)->get();
        return view('public.register-instansi', compact('packages'));
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
            // Package field
            'package_id' => 'nullable|exists:packages,id',
        ]);

        // Determine selected package
        $selectedPackage = null;
        if (!empty($validated['package_id'])) {
            $selectedPackage = Package::find($validated['package_id']);
        }

        // If no package selected or selected package is Free/Trial, ensure Free package exists
        $isTrial = false;
        if (!$selectedPackage || $selectedPackage->price == 0 || stripos($selectedPackage->name, 'Free') !== false || stripos($selectedPackage->name, 'Trial') !== false) {
            $isTrial = true;
            // Ensure a Free package exists if not selected
            if (!$selectedPackage) {
                $selectedPackage = Package::where('name', 'Free')->first();
                if (!$selectedPackage) {
                    $selectedPackage = Package::create([
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
            }
        }

        // Create instansi
        $instansi = Instansi::create([
            'nama_instansi' => $validated['nama_instansi'],
            'subdomain' => $validated['subdomain'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'package_id' => $selectedPackage->id,
            'is_active' => true,
            'status_langganan' => $isTrial ? 'active' : 'inactive', // Inactive if paid package pending payment
            'max_employees' => $selectedPackage->max_employees,
            'max_branches' => $selectedPackage->max_branches,
        ]);

        // Create admin user for this instansi
        $user = User::create([
            'name' => $validated['user_name'],
            'email' => $validated['user_email'],
            'password' => Hash::make($validated['password']),
            'system_role_id' => 2, // Admin role
            'instansi_id' => $instansi->id,
        ]);

        if ($isTrial) {
            // Create active trial subscription
            $start = Carbon::now()->toDateString();
            $end = Carbon::now()->addDays($selectedPackage->duration_days ?? 30)->toDateString();
            
            Subscription::create([
                'instansi_id' => $instansi->id,
                'package_id' => $selectedPackage->id,
                'status' => 'active',
                'start_date' => $start,
                'end_date' => $end,
                'price' => 0,
                'is_trial' => true,
                'trial_ends_at' => $end,
            ]);

            $message = 'Pendaftaran berhasil. Silakan login menggunakan email dan password yang Anda buat.';
        } else {
            // Create pending subscription request (transaction)
            DB::table('subscription_requests')->insert([
                'instansi_id' => $instansi->id,
                'package_id' => $selectedPackage->id,
                'amount' => $selectedPackage->price,
                'payment_status' => 'pending',
                'transaction_id' => 'REG-' . $instansi->id . '-' . time(),
                'created_by' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
                'notes' => 'Pendaftaran baru dengan paket ' . $selectedPackage->name,
            ]);

            $message = 'Pendaftaran berhasil. Silakan login dan selesaikan pembayaran untuk mengaktifkan akun Anda.';
        }

        // Notify superadmin (user_id = 1)
        try {
            DB::table('notifications')->insert([
                'user_id' => 1,
                'title' => 'Instansi Baru Terdaftar',
                'message' => 'Instansi "' . $instansi->nama_instansi . '" mendaftar dengan paket ' . $selectedPackage->name . '.',
                'type' => 'info',
                'is_read' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Throwable $e) {
            // ignore notification errors
        }

        return redirect()->route('login')
            ->with('status', $message);
    }
} 