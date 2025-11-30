<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Core\User;
use App\Models\SuperAdmin\Instansi;
use App\Models\SuperAdmin\Package;
use App\Models\SuperAdmin\Subscription;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $googleUser = session('google_user');

        // If user submitted the form with "google_auth_no_password" but session is gone
        if (!$googleUser && $request->password === 'google_auth_no_password') {
            return redirect()->route('auth.google')
                ->with('error', 'Sesi Google berakhir. Silakan login ulang.');
        }
        
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'company_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
        ];

        if (!$googleUser) {
            $rules['password'] = ['required', 'confirmed', Rules\Password::defaults()];
        }

        $request->validate($rules);

        // Create Instansi
        $instansi = Instansi::create([
            'nama_instansi' => $request->company_name,
            'email' => $request->email,
            'is_active' => true,
        ]);

        // Check for Google User session
        $googleUser = session('google_user');
        
        $password = $request->password;
        if ($googleUser) {
            $password = \Illuminate\Support\Str::random(24);
        }

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($password),
            'instansi_id' => $instansi->id,
            'system_role_id' => 2, // Default role untuk user yang mendaftar
        ];

        // Add Google data if applicable
        if ($googleUser && $googleUser->email === $request->email) {
            $userData['google_id'] = $googleUser->id;
            $userData['email_verified_at'] = now();
            $userData['avatar'] = $googleUser->avatar;
            
            // Clear session
            session()->forget('google_user');
        }

        // Create User
        $user = User::create($userData);

        // Dapatkan paket TRIAL (ID 5)
        $trialPackage = Package::find(5);
        
        // Cek apakah ada paket yang dipilih dari URL
        $selectedPackageId = $request->input('package');
        $selectedPackage = $selectedPackageId ? Package::find($selectedPackageId) : null;
        
        // Selalu buat subscription untuk paket TRIAL terlebih dahulu
        if ($trialPackage) {
            $trialSubscription = Subscription::create([
                'instansi_id' => $instansi->id,
                'package_id' => $trialPackage->id,
                'status' => 'active',
                'start_date' => now(),
                'end_date' => now()->addDays($trialPackage->duration_days),
                'price' => $trialPackage->price,
                'payment_status' => 'paid',
                'is_trial' => true,
                'trial_ends_at' => now()->addDays($trialPackage->duration_days),
            ]);
            
            // Update Instansi package info dengan paket TRIAL
            $instansi->update([
                'package_id' => $trialPackage->id,
                'subscription_start' => now(),
                'subscription_end' => now()->addDays($trialPackage->duration_days),
                'status_langganan' => 'active',
                'max_employees' => $trialPackage->max_employees,
            ]);
        }
        
        // Jika ada paket yang dipilih dan bukan paket TRIAL
        if ($selectedPackage && $selectedPackage->id != 5) {
            // Buat subscription untuk paket yang dipilih (status inactive)
            $subscription = Subscription::create([
                'instansi_id' => $instansi->id,
                'package_id' => $selectedPackage->id,
                'status' => 'inactive',
                'start_date' => now(),
                'end_date' => now()->addDays($selectedPackage->duration_days),
                'price' => $selectedPackage->price,
                'payment_status' => 'pending',
                'is_trial' => false,
            ]);
            
            // Buat subscription request untuk paket yang dipilih
            DB::table('subscription_requests')->insert([
                'instansi_id' => $instansi->id,
                'package_id' => $selectedPackage->id,
                'subscription_id' => $subscription->id,
                'amount' => $selectedPackage->price,
                'payment_method' => 'pending',
                'payment_status' => 'pending',
                'transaction_id' => 'NEW-' . $instansi->id . '-' . time(),
                'notes' => 'Pendaftaran dengan paket ' . $selectedPackage->name,
                'created_by' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
                'target_package_id' => $selectedPackage->id
            ]);
        }
        
        // Semua logika subscription sudah dipindah ke atas

        // Check email verification setting
        $emailVerificationSetting = \App\Models\SystemSetting::where('key', 'email_verification_enabled')->first();
        if ($emailVerificationSetting && $emailVerificationSetting->value === 'false') {
            $user->markEmailAsVerified();
        }

        event(new Registered($user));

        Auth::login($user);

        if (!$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        return redirect(route('dashboard', absolute: false));
    }
}
