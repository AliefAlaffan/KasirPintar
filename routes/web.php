<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Manajer\DashboardController as ManajerDashboard;
use App\Http\Controllers\Kasir\DashboardController as KasirDashboard;
use App\Http\Controllers\Admin\CategoryController; 
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;

Route::middleware(['auth', 'verified'])->group(function () {

    // Redirect otomatis setelah login sesuai role
    Route::get('/dashboard', function () {
        $user = auth()->user();

        return match ($user->role) {
            'admin'   => redirect()->route('admin.dashboard'),
            'manajer' => redirect()->route('manajer.dashboard'),
            'kasir'   => redirect()->route('kasir.dashboard'),
            default   => redirect()->route('login'),
        };
    })->name('dashboard');

    // Grup khusus Admin
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        Route::resource('categories', CategoryController::class)->except(['create', 'edit', 'show']);
        Route::resource('suppliers', SupplierController::class)->except(['create', 'edit', 'show']);
        Route::resource('products', ProductController::class)->except('show');
        Route::resource('users', UserController::class)->except(['create', 'edit', 'show']);
        Route::patch('users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    });

    // Grup khusus Manajer
    Route::middleware(['role:manajer'])->prefix('manajer')->name('manajer.')->group(function () {
        Route::get('/dashboard', [ManajerDashboard::class, 'index'])->name('dashboard');
    });

    // Grup khusus Kasir
    Route::middleware(['role:kasir'])->prefix('kasir')->name('kasir.')->group(function () {
        Route::get('/dashboard', [KasirDashboard::class, 'index'])->name('dashboard');
    });
});