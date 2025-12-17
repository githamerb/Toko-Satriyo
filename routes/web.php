<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\AdminDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Semua route untuk sistem kasir Satriyo.
| Termasuk login, register, dashboard admin, CRUD barang, laporan & kasir.
|--------------------------------------------------------------------------
*/

// ===========================
// 🔗 ROOT → LOGIN
// ===========================
Route::get('/', fn() => redirect()->route('login'));

// ===========================
// 🔐 LOGIN, REGISTER & LOGOUT
// ===========================
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.process');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ===========================
// 🔒 ROUTE YANG HARUS LOGIN
// ===========================
Route::middleware('auth')->group(function () {

    // =======================
    // 👑 ADMIN PANEL
    // =======================
    Route::prefix('admin')->name('admin.')->group(function () {

        // Dashboard Admin
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // CRUD Barang
        Route::resource('barang', BarangController::class)->except(['show']);
        Route::get('/barang/search', [BarangController::class, 'search'])->name('barang.search');

        // Laporan
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/cetak-pdf', [LaporanController::class, 'cetakPdf'])->name('laporan.pdf');
    });

    // =======================
    // 💰 KASIR PANEL
    // =======================
    Route::prefix('kasir')->name('kasir.')->group(function () {
        Route::get('/', [KasirController::class, 'index'])->name('index');
        Route::get('/keranjang', [KasirController::class, 'keranjang'])->name('keranjang');
        Route::post('/tambah', [KasirController::class, 'tambah'])->name('tambah');
        Route::post('/update-jumlah', [KasirController::class, 'updateJumlah'])->name('updateJumlah');
        Route::post('/hapus', [KasirController::class, 'hapus'])->name('hapus');
        Route::post('/checkout', [KasirController::class, 'checkout'])->name('checkout');
        Route::get('/struk', [KasirController::class, 'struk'])->name('struk');
    });
});
