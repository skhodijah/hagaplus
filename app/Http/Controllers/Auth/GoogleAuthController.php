<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GoogleAuthController extends Controller
{
    /**
     * Redirect to Google OAuth
     */
    public function redirectToGoogle()
    {
        return \Laravel\Socialite\Facades\Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = \Laravel\Socialite\Facades\Socialite::driver('google')->user();
            
            // Find user
            $user = \App\Models\Core\User::where('google_id', $googleUser->id)
                ->orWhere('email', $googleUser->email)
                ->first();

            if ($user) {
                // Update google_id if not set
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->id]);
                }

                // Auto-verify email for Google users
                if (!$user->hasVerifiedEmail()) {
                    $user->markEmailAsVerified();
                }

                // Login the user
                \Illuminate\Support\Facades\Auth::login($user, true);

                // Check if user has employee record (for employee role)
                if ($user->systemRole && $user->systemRole->name === 'employee') {
                    $employee = $user->employee;
                    
                    if (!$employee) {
                        \Illuminate\Support\Facades\Auth::logout();
                        return redirect()->route('login')->with('error', 'Akun Anda belum terdaftar sebagai karyawan. Silakan hubungi administrator untuk mendaftarkan akun Anda.');
                    }
                }

                // Redirect based on role
                if ($user->systemRole && $user->systemRole->name === 'admin') {
                    return redirect()->route('admin.dashboard');
                } elseif ($user->systemRole && $user->systemRole->name === 'superadmin') {
                    return redirect()->route('superadmin.dashboard');
                } else {
                    return redirect()->route('employee.dashboard');
                }
            } else {
                // User not found - Redirect to register page with Google data
                session(['google_user' => $googleUser]);
                return redirect()->route('register');
            }

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Google OAuth Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Gagal login dengan Google. Silakan coba lagi.');
        }
    }

    public function showChoice()
    {
        $googleUser = session('google_user');
        if (!$googleUser) {
            return redirect()->route('login');
        }
        return view('auth.google-choice', compact('googleUser'));
    }

    public function processChoice(Request $request)
    {
        $action = $request->input('action');
        $googleUser = session('google_user');
        
        if (!$googleUser) {
            return redirect()->route('login');
        }

        if ($action === 'create_instansi') {
            // Redirect to instansi registration with data
            // We need to pass the data to the registration controller
            // We can keep it in session 'google_user' and access it there
            return redirect()->route('public.register.instansi.create');
        } else {
            // User chose not to create instansi
            session()->forget('google_user');
            return redirect()->route('login')->with('info', 'Pendaftaran dibatalkan. Silakan hubungi administrator jika Anda seharusnya memiliki akun.');
        }
    }
}
