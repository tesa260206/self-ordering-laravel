<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController; // <- Import Controller Admin di sini

// Arahkan domain utama langsung ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});

// ==========================================
Route::prefix('customer')->name('customer.')->group(function () {
    Route::get('/order', [\App\Http\Controllers\Customer\OrderController::class, 'welcome'])->name('welcome');
    Route::get('/menu', [\App\Http\Controllers\Customer\OrderController::class, 'menu'])->name('menu');
    Route::get('/checkout', [\App\Http\Controllers\Customer\OrderController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [\App\Http\Controllers\Customer\OrderController::class, 'storeOrder'])->name('storeOrder');
    Route::get('/success/{orderNumber}', [\App\Http\Controllers\Customer\OrderController::class, 'success'])->name('success');
    Route::get('/status/{orderNumber}', [\App\Http\Controllers\Customer\OrderController::class, 'status'])->name('status');
    Route::get('/status/{orderNumber}/check', [\App\Http\Controllers\Customer\OrderController::class, 'checkStatus'])->name('checkStatus');
    // Cek apakah meja sudah punya order aktif (untuk auto-fill customer info)
    Route::get('/check-active-order', [\App\Http\Controllers\Customer\OrderController::class, 'checkActiveOrder'])->name('checkActiveOrder');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Authenticated Routes (Admin, Cashier, Kitchen) disatukan
Route::middleware('auth')->group(function () {
    
    // ==========================================
    // MODULE ADMIN
    // ==========================================
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        
        // Dashboard Admin
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('tables', \App\Http\Controllers\Admin\TableController::class)->except(['create', 'show']);
        // Kategori Menu
Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class)->except(['create', 'show']);
// Menu Makanan
Route::resource('menus', \App\Http\Controllers\Admin\MenuController::class)->except(['create', 'show']);
Route::patch('menus/{menu}/toggle', [\App\Http\Controllers\Admin\MenuController::class, 'toggleAvailable'])->name('menus.toggle');
// Monitoring Status Meja
Route::get('/table-status', [\App\Http\Controllers\Admin\TableStatusController::class, 'index'])->name('table-status.index');
Route::get('/orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
Route::get('/orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
Route::put('/orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');
// Modul Pembayaran (Kasir)
Route::get('/payments', [\App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('payments.index');
Route::get('/payments/{order}/detail', [\App\Http\Controllers\Admin\PaymentController::class, 'detail'])->name('payments.detail');
Route::post('/payments/{order}', [\App\Http\Controllers\Admin\PaymentController::class, 'process'])->name('payments.process');
Route::get('/payments/{order}/receipt', [\App\Http\Controllers\Admin\PaymentController::class, 'receipt'])->name('payments.receipt');
// Manajemen Pengguna
Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->except(['create', 'show']);
// Modul Laporan
Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
Route::get('/reports/export-pdf', [\App\Http\Controllers\Admin\ReportController::class, 'exportPdf'])->name('reports.export-pdf');
Route::get('/reports/charts', [\App\Http\Controllers\Admin\ReportController::class, 'charts'])->name('reports.charts');
// Pengaturan Sistem
Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
Route::put('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
        
        // (Nanti route Master Meja, Menu, Order ditaruh di sini)
        
    });

    // ==========================================
    // MODULE CASHIER (Dicomment sementara)
    // ==========================================
    // Route::prefix('cashier')->name('cashier.')->middleware('role:cashier')->group(function () {
    //     // Route::get('/dashboard', [CashierDashboardController::class, 'index'])->name('dashboard');
    // });

    // ==========================================
    // MODULE KITCHEN (Dicomment sementara)
 Route::prefix('kitchen')->name('kitchen.')->middleware('role:kitchen|admin')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Kitchen\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/cooking', [\App\Http\Controllers\Kitchen\DashboardController::class, 'cooking'])->name('cooking');
        
        // ROUTE BARU: Halaman Siap Diantar
        Route::get('/ready', [\App\Http\Controllers\Kitchen\DashboardController::class, 'ready'])->name('ready');
        Route::get('/history', [\App\Http\Controllers\Kitchen\DashboardController::class, 'history'])->name('history');
        Route::put('/orders/{order}/status', [\App\Http\Controllers\Kitchen\DashboardController::class, 'updateStatus'])->name('orders.update-status');
    });
    
});