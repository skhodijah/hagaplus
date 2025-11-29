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
            
            // Find or create user
            $user = \App\Models\Core\User::where('google_id', $googleUser->id)
                ->orWhere('email', $googleUser->email)
                ->first();

            if ($user) {
                // Update google_id if not set
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->id]);
                }
            } else {
                // Create new user
                $user = \App\Models\Core\User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'email_verified_at' => now(),
                    'avatar' => $googleUser->avatar,
                ]);

                // Assign default employee role if exists
                $employeeRole = \App\Models\Admin\SystemRole::where('name', 'employee')->first();
                if ($employeeRole) {
                    $user->system_role_id = $employeeRole->id;
                    $user->save();
                }
            }

            // Login the user
            \Illuminate\Support\Facades\Auth::login($user, true);

            // Check if user has employee record (for employee role)
            if ($user->systemRole && $user->systemRole->name === 'employee') {
                $employee = $user->employee;
                
                if (!$employee) {
                    // Employee record doesn't exist
                    \Illuminate\Support\Facades\Auth::logout();
                    return redirect()->route('login')->with('error', 'Akun Anda belum terdaftar sebagai karyawan. Silakan hubungi administrator untuk mendaftarkan akun Anda.');
                }
            }

            // Redirect based on role
            if ($user->systemRole && $user->systemRole->name === 'admin') {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('employee.dashboard');
            }

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Google OAuth Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Gagal login dengan Google. Silakan coba lagi.');
        }
    }
}
