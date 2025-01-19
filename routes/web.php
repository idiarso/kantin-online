<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Seller\ProductController as SellerProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\Admin\LandingController;
use App\Models\User;
use App\Http\Controllers\DigitalCardController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\KantinAdmin\ReportController as KantinReportController;
use App\Http\Controllers\KantinAdmin\DashboardController as KantinDashboardController;
use App\Http\Controllers\KantinAdmin\MenuController as KantinMenuController;
use App\Http\Controllers\KantinAdmin\CategoryController as KantinCategoryController;
use App\Http\Controllers\KantinAdmin\OrderController as KantinOrderController;
use App\Http\Controllers\KantinAdmin\SettingController as KantinSettingController;

// Home & Public Routes
Route::get('/', [WelcomeController::class, 'index'])->name('home');

// Auth Routes
require __DIR__.'/auth.php';

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('categories', AdminCategoryController::class);
    Route::resource('landing', LandingController::class);
    Route::post('landing/reorder', [LandingController::class, 'reorder'])->name('landing.reorder');
});

// Kantin Admin routes
Route::middleware(['auth', 'role:kantin_admin'])->prefix('kantin-admin')->name('kantin.admin.')->group(function () {
    Route::get('/dashboard', [KantinDashboardController::class, 'index'])->name('dashboard');
    Route::resource('menu', MenuController::class);
    Route::patch('menu/{product}/stock', [MenuController::class, 'updateStock'])->name('menu.stock');
    Route::patch('menu/{product}/status', [MenuController::class, 'updateStatus'])->name('menu.status');
    Route::get('/kitchen', [OrderController::class, 'kitchen'])->name('kitchen');
    Route::resource('categories', CategoryController::class);
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::post('/settings/operational-hours', [SettingController::class, 'updateOperationalHours'])->name('settings.operational-hours');
    Route::post('/settings/payment-methods', [SettingController::class, 'updatePaymentMethods'])->name('settings.payment-methods');
    Route::post('/settings/notifications', [SettingController::class, 'updateNotificationSettings'])->name('settings.notifications');
    Route::get('/reports', [KantinReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [KantinReportController::class, 'export'])->name('reports.export');
    Route::get('/kantin-admin/export/excel', [DashboardController::class, 'exportExcel'])
        ->name('kantin.admin.export.excel');
    Route::get('/kantin-admin/export/pdf', [DashboardController::class, 'exportPDF'])
        ->name('kantin.admin.export.pdf');
});

// Kantin Staff routes
Route::middleware(['auth', 'role:kantin_staff'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos/scan-qr', [PosController::class, 'scanQr'])->name('pos.scan-qr');
    Route::post('/pos/process', [PosController::class, 'process'])->name('pos.process');
    Route::get('/pos/receipt/{order}', [PosController::class, 'printReceipt'])->name('pos.receipt');
});

// Dashboard Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Categories Routes
    Route::resource('categories', CategoryController::class);
    
    // Products Routes
    Route::resource('products', ProductController::class);
    
    // Orders Routes
    Route::resource('orders', OrderController::class);

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Transaction Routes
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::post('/transactions/topup', [TransactionController::class, 'topUp'])->name('transactions.topup');

    // Digital Card Routes
    Route::get('/digital-card', [DigitalCardController::class, 'show'])->name('digital-card.show');
    Route::get('/digital-card/download', [DigitalCardController::class, 'download'])->name('digital-card.download');
});
