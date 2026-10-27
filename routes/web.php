
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\PelangganAuthController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\Admin\ManajemenStokController;
use App\Http\Controllers\Admin\PesananController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Pelanggan\ProdukController as ProdukPelangganController;
use App\Http\Controllers\Pelanggan\KeranjangController;
use App\Http\Controllers\Pelanggan\CheckoutController;
use App\Http\Controllers\Pelanggan\KonfirmasiPesananController;
use App\Http\Controllers\Pelanggan\RiwayatPesananController;
use App\Http\Controllers\HomeController;


// ==================================================
// HALAMAN UTAMA
// ==================================================

Route::get('/', [HomeController::class, 'index'])->name('home');

// ==================================================
// AUTENTIKASI TAMU
// ==================================================

Route::middleware('guest')->group(function () {
    // Registrasi manual
    Route::get('/register', [PelangganAuthController::class, 'register'])
        ->name('register');

    Route::post('/register', [PelangganAuthController::class, 'storeRegister'])
        ->name('register.store');

    // Verifikasi OTP email
    Route::get('/register/verify', [PelangganAuthController::class, 'showVerify'])
        ->name('register.verify');

    Route::post('/register/verify', [PelangganAuthController::class, 'verifyOtp'])
        ->name('register.verify.submit');

    Route::post('/register/resend-otp', [PelangganAuthController::class, 'resendOtp'])
        ->middleware('throttle:3,10')
        ->name('register.otp.resend');

    // Login Google
    Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])
        ->name('google.redirect');

    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])
        ->name('google.callback');

    // Login manual
    Route::get('/login', [PelangganAuthController::class, 'login'])
        ->name('login');

    Route::post('/login', [PelangganAuthController::class, 'storeLogin'])
        ->middleware('throttle:5,1')
        ->name('login.store');
});

// ==================================================
// LOGOUT
// ==================================================

Route::post('/logout', [PelangganAuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ==================================================
// KATALOG PRODUK PUBLIK
// ==================================================

Route::get('/produk', [ProdukPelangganController::class, 'index'])
    ->name('produk.index');

Route::get('/produk/{slug}', [ProdukPelangganController::class, 'show'])
    ->name('produk.show');

// ==================================================
// KERANJANG BELANJA
// ==================================================

Route::get('/keranjang', [KeranjangController::class, 'index'])
    ->name('keranjang.index');

Route::post('/keranjang/{slug}', [KeranjangController::class, 'store'])
    ->name('keranjang.store');

Route::patch('/keranjang/{id}', [KeranjangController::class, 'update'])
    ->name('keranjang.update');

Route::delete('/keranjang/{id}', [KeranjangController::class, 'destroy'])
    ->name('keranjang.destroy');

// ==================================================
// FITUR PELANGGAN YANG MEMERLUKAN LOGIN
// ==================================================

Route::middleware('auth')->group(function () {
    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])
        ->name('checkout.index');

    Route::post('/checkout', [CheckoutController::class, 'store'])
        ->name('checkout.store');

    Route::get('/checkout/{kode_pesanan}/pembayaran', [CheckoutController::class,'payment',])
        ->name('checkout.payment');

    Route::post('/checkout/{kode_pesanan}/pembayaran', [CheckoutController::class,'uploadProof',])
        ->name('checkout.payment.upload');

    Route::get('/pesanan/{kode_pesanan}/selesai', [CheckoutController::class, 'success'])
        ->name('checkout.success');

    // Konfirmasi pesanan diterima
    Route::post('/pesanan/{id}/konfirmasi-diterima', [
        KonfirmasiPesananController::class,
        'konfirmasiDiterima',
    ])->name('pelanggan.pesanan.konfirmasiDiterima');

    // Riwayat pesanan
    Route::get('/riwayat-pesanan', [RiwayatPesananController::class, 'index'])
        ->name('pelanggan.pesanan.index');

    Route::get('/riwayat-pesanan/{kode_pesanan}', [
        RiwayatPesananController::class,
        'show',
    ])->name('pelanggan.pesanan.show');
});

// ==================================================
// PANEL ADMIN DAN OWNER
// ==================================================

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:owner,admin'])
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // Kategori
        Route::resource('kategori', KategoriController::class)
            ->except(['show']);

        // Produk
        Route::resource('produk', ProdukController::class)
            ->except(['show']);

        // Manajemen stok
        Route::get('/stok/export-pdf', [ManajemenStokController::class, 'exportPdf'])
            ->name('stok.export-pdf');

        Route::get('/stok', [ManajemenStokController::class, 'index'])
            ->name('stok.index');

        Route::get('/stok/create', [ManajemenStokController::class, 'create'])
            ->name('stok.create');

        Route::post('/stok', [ManajemenStokController::class, 'store'])
            ->name('stok.store');

        // Manajemen pesanan
        Route::get('/pesanan', [PesananController::class, 'index'])
            ->name('pesanan.index');

        Route::get('/pesanan/{id}', [PesananController::class, 'show'])
            ->name('pesanan.show');

        Route::patch('/pesanan/{id}/status', [PesananController::class, 'updateStatus'])
            ->name('pesanan.updateStatus');

        Route::post('/pesanan/{id}/serahkan', [PesananController::class, 'serahkanBarang'])
            ->name('pesanan.serahkan');

        // Manajemen pembayaran
        Route::get('/pembayaran', [PembayaranController::class, 'index'])
            ->name('pembayaran.index');

        Route::patch('/pembayaran/{id}/verifikasi', [
            PembayaranController::class,
            'verifikasi',
        ])->name('pembayaran.verifikasi');

        // Laporan
        Route::get('/laporan', [LaporanController::class, 'index'])
            ->name('laporan.index');

        Route::get('/laporan/export-pdf', [LaporanController::class, 'exportPdf'])
            ->name('laporan.export-pdf');
    });
