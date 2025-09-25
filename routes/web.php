<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicRegistrationController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // General dashboard route that redirects based on user role
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->hasRole('superadmin')) {
            return redirect()->route('superadmin.dashboard');
        } elseif ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('employee')) {
            return redirect()->route('employee.dashboard');
        }

        // Default fallback
        return redirect()->route('employee.dashboard');
    })->name('dashboard');
});

// Public Instansi Registration
Route::get('/register-instansi', [PublicRegistrationController::class, 'create'])->name('public.register.instansi.create');
Route::post('/register-instansi', [PublicRegistrationController::class, 'store'])->name('public.register.instansi.store');

// Include role-based route files
require __DIR__ . '/auth.php';
require __DIR__ . '/superadmin.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/employee.php';
