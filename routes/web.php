<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin routes
    Route::get('/admin/companies', [DashboardController::class, 'companies'])->name('admin.companies');
    Route::get('/admin/companies/create', [DashboardController::class, 'createCompany'])->name('admin.companies.create');
    Route::post('/admin/companies', [DashboardController::class, 'storeCompany'])->name('admin.companies.store');
    Route::get('/admin/companies/{company}/edit', [DashboardController::class, 'editCompany'])->name('admin.companies.edit');
    Route::put('/admin/companies/{company}', [DashboardController::class, 'updateCompany'])->name('admin.companies.update');
    Route::delete('/admin/companies/{company}', [DashboardController::class, 'deleteCompany'])->name('admin.companies.delete');
});

require __DIR__ . '/auth.php';
