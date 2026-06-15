<?php

use Illuminate\Support\Facades\Route;

// Authentication
use App\Http\Controllers\Auth\AuthController;

// Customer
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;

// Admin
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\TableController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\TableStatusController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;

// Kitchen
use App\Http\Controllers\Kitchen\DashboardController as KitchenDashboardController;

// Arahkan domain utama langsung ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});

// ==========================================
// MODULE CUSTOMER
// ==========================================
Route::prefix('customer')->name('customer.')->group(function () {
    Route::get('/order', [CustomerOrderController::class, 'welcome'])->name('welcome');
    Route::get('/menu', [CustomerOrderController::class, 'menu'])->name('menu');
    Route::get('/checkout', [CustomerOrderController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [CustomerOrderController::class, 'storeOrder'])->name('storeOrder');
    Route::get('/success/{orderNumber}', [CustomerOrderController::class, 'success'])->name('success');
    Route::get('/status/{orderNumber}', [CustomerOrderController::class, 'status'])->name('status');
    Route::get('/status/{orderNumber}/check', [CustomerOrderController::class, 'checkStatus'])->name('checkStatus');
    // Cek apakah meja sudah punya order aktif (untuk auto-fill customer info)
    Route::get('/check-active-order', [CustomerOrderController::class, 'checkActiveOrder'])->name('checkActiveOrder');
});

// ==========================================
// AUTHENTICATION
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ==========================================
// AUTHENTICATED ROUTES
// ==========================================
Route::middleware('auth')->group(function () {
    
    // ------------------------------------------
    // MODULE ADMIN
    // ------------------------------------------
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        
        // Dashboard Admin
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Data Master
        Route::resource('tables', TableController::class)->except(['create', 'show']);
        Route::resource('categories', CategoryController::class)->except(['create', 'show']);
        Route::resource('menus', MenuController::class)->except(['create', 'show']);
        Route::patch('menus/{menu}/toggle', [MenuController::class, 'toggleAvailable'])->name('menus.toggle');
        
        // Transaksi & Status
        Route::get('/table-status', [TableStatusController::class, 'index'])->name('table-status.index');
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::put('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
        
        // Modul Pembayaran (Kasir)
        Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
        Route::get('/payments/{order}/detail', [PaymentController::class, 'detail'])->name('payments.detail');
        Route::post('/payments/{order}', [PaymentController::class, 'process'])->name('payments.process');
        Route::get('/payments/{order}/receipt', [PaymentController::class, 'receipt'])->name('payments.receipt');
        
        // Manajemen Pengguna
        Route::resource('users', UserController::class)->except(['create', 'show']);
        
        // Modul Laporan
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export-pdf', [ReportController::class, 'exportPdf'])->name('reports.export-pdf');
        
        // Pengaturan Sistem
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    });

    // ------------------------------------------
    // MODULE KITCHEN
    // ------------------------------------------
    Route::prefix('kitchen')->name('kitchen.')->middleware('role:kitchen|admin')->group(function () {
        
        // Dashboard Dapur
        Route::get('/dashboard', [KitchenDashboardController::class, 'index'])->name('dashboard');
        Route::get('/cooking', [KitchenDashboardController::class, 'cooking'])->name('cooking');
        Route::get('/ready', [KitchenDashboardController::class, 'ready'])->name('ready');
        Route::get('/history', [KitchenDashboardController::class, 'history'])->name('history');
        
        // Aksi Update Status Masakan
        Route::put('/orders/{order}/status', [KitchenDashboardController::class, 'updateStatus'])->name('orders.update-status');
    });
    
});