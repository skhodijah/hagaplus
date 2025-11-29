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
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'company_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Generate unique subdomain
        $baseSubdomain = \Illuminate\Support\Str::slug($request->company_name);
        $subdomain = $baseSubdomain;
        $counter = 1;
        
        while (Instansi::where('subdomain', $subdomain)->exists()) {
            $subdomain = $baseSubdomain . '-' . $counter;
            $counter++;
        }

        // Create Instansi
        $instansi = Instansi::create([
            'nama_instansi' => $request->company_name,
            'subdomain' => $subdomain,
            'email' => $request->email,
            'is_active' => true,
        ]);

        // Create User
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'instansi_id' => $instansi->id,
        ]);

        // Handle Package and Subscription
        $packageId = $request->input('package');
        $package = null;

        if ($packageId) {
            $package = Package::find($packageId);
        }

        // Default to TRIAL if no package selected or package not found
        if (!$package) {
            $package = Package::where('name', 'TRIAL')->first();
        }

        // Fallback if TRIAL package doesn't exist (safety check)
        if (!$package) {
             // You might want to handle this case, e.g., create a default package or throw error
             // For now, we'll proceed assuming at least one package exists or handle gracefully
             // But strictly speaking, we should have a package.
             // Let's try to get the first available package if TRIAL is missing
             $package = Package::first();
        }

        if ($package) {
            $isTrial = $package->price == 0;
            $status = $isTrial ? 'active' : 'pending';
            $paymentStatus = $isTrial ? 'paid' : 'unpaid';
            
            // Create Subscription
            Subscription::create([
                'instansi_id' => $instansi->id,
                'package_id' => $package->id,
                'status' => $status,
                'start_date' => now(),
                'end_date' => now()->addDays($package->duration_days),
                'price' => $package->price,
                'payment_status' => $paymentStatus,
                'is_trial' => $isTrial,
                'trial_ends_at' => $isTrial ? now()->addDays($package->duration_days) : null,
            ]);

            // Update Instansi package info
            $instansi->update([
                'package_id' => $package->id,
                'subscription_start' => now(),
                'subscription_end' => now()->addDays($package->duration_days),
                'status_langganan' => $status,
                'max_employees' => $package->max_employees,
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        if (isset($isTrial) && !$isTrial) {
            return redirect()->route('admin.subscription.index');
        }

        return redirect(route('dashboard', absolute: false));
    }
}
