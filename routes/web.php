<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Manajer\DashboardController as ManajerDashboard;
use App\Http\Controllers\Kasir\DashboardController as KasirDashboard;
use App\Http\Controllers\Admin\CategoryController; 
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Manajer\RestockController;
use App\Http\Controllers\Manajer\StockOpnameController;
use App\Http\Controllers\Kasir\TransactionController;
use App\Http\Controllers\Admin\ReportController;


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

    Route::middleware(['role:manajer'])->prefix('manajer')->name('manajer.')->group(function () {
        Route::get('/dashboard', [ManajerDashboard::class, 'index'])->name('dashboard');

        Route::resource('restocks', RestockController::class)->only(['index', 'create', 'store', 'show']);
        Route::resource('stock-opnames', StockOpnameController::class)->only(['index', 'create', 'store', 'show']);
        Route::post('stock-opnames/{id}/complete', [StockOpnameController::class, 'complete'])->name('stock-opnames.complete');
    });

    Route::middleware(['role:kasir'])->prefix('kasir')->name('kasir.')->group(function () {
        Route::get('/dashboard', [KasirDashboard::class, 'index'])->name('dashboard');
        Route::get('/transactions/{id}/receipt', [TransactionController::class, 'receipt'])->name('transactions.receipt');
    });

    Route::middleware(['role:admin,manajer'])->prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/export/pdf', [ReportController::class, 'exportPdf'])->name('export.pdf');
        Route::get('/export/excel', [ReportController::class, 'exportExcel'])->name('export.excel');
    });
});