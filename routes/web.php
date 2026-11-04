<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\StandController as AdminStandController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StandController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Kantin Sekolah [MELATIAREA]
|--------------------------------------------------------------------------
*/

// Beranda & Landing Page
Route::get('/', [HomeController::class, 'index'])->name('home');

// 8 Stand Kantin
Route::get('/stands', [StandController::class, 'index'])->name('stands.index');
Route::get('/stands/{stand}', [StandController::class, 'show'])->name('stands.show');

// Menu Produk & Filter
Route::get('/menu', [ProductController::class, 'index'])->name('menu.index');
Route::get('/menu/{product}', [ProductController::class, 'show'])->name('menu.show');

// Keranjang Belanja
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add/{product}', [CartController::class, 'add'])->name('add');
    Route::post('/update/{id}', [CartController::class, 'update'])->name('update');
    Route::post('/remove/{id}', [CartController::class, 'remove'])->name('remove');
    Route::post('/clear', [CartController::class, 'clear'])->name('clear');
});

// Checkout / Pemesanan
Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/', [CheckoutController::class, 'process'])->name('process');
    Route::get('/success/{order}', [CheckoutController::class, 'success'])->name('success');
});

// Pesanan Saya (Status Pesanan & Riwayat)
Route::prefix('orders')->name('orders.')->group(function () {
    Route::get('/', [OrderController::class, 'index'])->name('index');
    Route::get('/{order}', [OrderController::class, 'show'])->name('show');
});

// Autentikasi (Login, Register, Logout)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// --------------------------------------------------------------------------
// Admin Dashboard & Manajemen (Dilindungi Middleware Auth & Admin)
// --------------------------------------------------------------------------
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Redirect /admin to /admin/dashboard
    Route::get('/', fn() => redirect()->route('admin.dashboard'));

    // Dashboard Utama
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // CRUD 8 Stand Kantin
    Route::post('/stands/{stand}/toggle-status', [AdminStandController::class, 'toggleStatus'])->name('stands.toggle');
    Route::resource('stands', AdminStandController::class);

    // CRUD Produk
    Route::post('/products/{product}/stock', [AdminProductController::class, 'updateStock'])->name('products.stock');
    Route::resource('products', AdminProductController::class);

    // Kategori Produk
    Route::resource('categories', AdminCategoryController::class)->except(['create', 'show', 'edit']);

    // Manajemen Pesanan
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [AdminOrderController::class, 'index'])->name('index');
        Route::get('/{order}', [AdminOrderController::class, 'show'])->name('show');
        Route::post('/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('status');
        Route::get('/{order}/print', [AdminOrderController::class, 'print'])->name('print');
    });

    // Laporan Penjualan & Export
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [AdminReportController::class, 'index'])->name('index');
        Route::get('/export-csv', [AdminReportController::class, 'exportCsv'])->name('export_csv');
        Route::get('/print', [AdminReportController::class, 'print'])->name('print');
    });
});
