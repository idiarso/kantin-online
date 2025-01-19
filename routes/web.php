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
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\Customer\ProductController as CustomerProductController;

// Home & Public Routes
Route::get('/', [WelcomeController::class, 'index'])->name('home');

// Auth Routes
require __DIR__.'/auth.php';

// Admin Routes
Route::middleware(['auth', \App\Http\Middleware\CheckRole::class . ':admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Landing Page Management
    Route::prefix('landing')->name('landing.')->group(function () {
        Route::get('/content', [LandingPageController::class, 'content'])->name('content');
        Route::post('/content', [LandingPageController::class, 'updateContent'])->name('content.update');
        Route::get('/banners', [LandingPageController::class, 'banners'])->name('banners');
        Route::post('/banners', [LandingPageController::class, 'updateBanners'])->name('banners.update');
        Route::delete('/banners/{index}', [LandingPageController::class, 'deleteBanner'])->name('banners.delete');
        Route::get('/announcements', [LandingPageController::class, 'announcements'])->name('announcements');
        Route::post('/announcements', [LandingPageController::class, 'updateAnnouncements'])->name('announcements.update');
    });

    // User Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        Route::post('/{user}/status', [UserController::class, 'toggleStatus'])->name('toggle-status');
        
        // Role-specific views
        Route::get('/teachers', [UserController::class, 'teachers'])->name('teachers');
        Route::get('/students', [UserController::class, 'students'])->name('students');
        Route::get('/parents', [UserController::class, 'parents'])->name('parents');
    });
    
    // Canteen Management
    Route::prefix('canteen')->name('canteen.')->group(function () {
        Route::get('/hours', [SettingController::class, 'hours'])->name('hours');
        Route::post('/hours', [SettingController::class, 'updateHours'])->name('hours.update');
    });
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    
    // Digital Cards
    Route::prefix('cards')->name('cards.')->group(function () {
        Route::get('/generate', [DigitalCardController::class, 'generate'])->name('generate');
        Route::get('/print', [DigitalCardController::class, 'print'])->name('print');
        Route::get('/batch', [DigitalCardController::class, 'batch'])->name('batch');
        Route::post('/generate', [DigitalCardController::class, 'processGenerate'])->name('generate.process');
        Route::post('/batch', [DigitalCardController::class, 'processBatch'])->name('batch.process');
    });
    
    // Financial Management
    Route::prefix('finance')->name('finance.')->group(function () {
        Route::get('/deposits', [TransactionController::class, 'deposits'])->name('deposits');
        Route::get('/transactions', [TransactionController::class, 'transactions'])->name('transactions');
        Route::get('/reports', [TransactionController::class, 'reports'])->name('reports');
        Route::post('/deposits/approve', [TransactionController::class, 'approveDeposit'])->name('deposits.approve');
        Route::get('/reports/export', [TransactionController::class, 'exportReport'])->name('reports.export');
    });
    
    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/general', [SettingController::class, 'general'])->name('general');
        Route::get('/notifications', [SettingController::class, 'notifications'])->name('notifications');
        Route::post('/update/{type}', [SettingController::class, 'update'])->name('update');
    });
});

// Kantin Admin routes
Route::middleware(['auth', \App\Http\Middleware\CheckRole::class . ':kantin_admin'])->prefix('kantin-admin')->name('kantin.admin.')->group(function () {
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
Route::middleware(['auth', \App\Http\Middleware\CheckRole::class . ':kantin_staff'])->prefix('staff')->name('staff.')->group(function () {
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

    // Menu Routes
    Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
});

// Customer Routes
Route::middleware(['auth'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/products', [CustomerProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [CustomerProductController::class, 'show'])->name('products.show');
    Route::post('/products/{product}/cart', [CustomerProductController::class, 'addToCart'])->name('products.addToCart');
});
