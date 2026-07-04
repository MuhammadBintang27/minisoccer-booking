<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\JadwalController as AdminJadwalController;
use App\Http\Controllers\Admin\JadwalLapanganController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\LapanganController;
use App\Http\Controllers\Admin\LayananTambahanController;
use App\Http\Controllers\Admin\MemberController as AdminMemberController;
use App\Http\Controllers\Admin\PemesananController as AdminPemesananController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Guest\PemesananController as GuestPemesananController;
use App\Http\Controllers\Member\JadwalController as MemberJadwalController;
use App\Http\Controllers\Member\LanggananController;
use App\Http\Controllers\Member\RiwayatController as MemberRiwayatController;
use App\Http\Controllers\PublicPageController;
use App\Http\Controllers\Webhooks\MidtransWebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicPageController::class, 'home'])->name('home');
Route::get('/terima-kasih', [PublicPageController::class, 'terimaKasih'])->name('terima-kasih');
Route::redirect('/jadwal', '/#jadwal');
Route::redirect('/tentang-kami', '/#tentang-kami');

Route::post('/webhooks/midtrans', [MidtransWebhookController::class, 'handle'])->name('webhooks.midtrans');

Route::prefix('pesan')->name('guest.pemesanan.')->group(function () {
    Route::get('/', [GuestPemesananController::class, 'create'])->name('create');
    Route::post('/', [GuestPemesananController::class, 'store'])->name('store')->middleware('throttle:5,1');
    Route::get('/{pemesanan}/status', [GuestPemesananController::class, 'show'])->name('show')->middleware('signed');
});

Route::get('/cek-pemesanan', [GuestPemesananController::class, 'cek'])->name('guest.pemesanan.cek')->middleware('throttle:20,1');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/jadwal', [AdminJadwalController::class, 'index'])->name('jadwal.index');

    Route::get('/pemesanan', [AdminPemesananController::class, 'index'])->name('pemesanan.index');

    Route::resource('member', AdminMemberController::class)->only(['index', 'edit', 'update', 'destroy']);

    Route::get('/laporan/pendapatan', [LaporanController::class, 'pendapatan'])->name('laporan.pendapatan');
    Route::get('/laporan/pendapatan/export', [LaporanController::class, 'exportPendapatan'])->name('laporan.pendapatan.export');

    Route::resource('lapangan', LapanganController::class)->except(['show']);

    Route::resource('layanan-tambahan', LayananTambahanController::class)->except(['show']);

    Route::get('/lapangan/{lapangan}/jadwal', [JadwalLapanganController::class, 'index'])->name('lapangan.jadwal.index');
    Route::post('/lapangan/{lapangan}/jadwal', [JadwalLapanganController::class, 'store'])->name('lapangan.jadwal.store');
    Route::put('/lapangan/{lapangan}/jadwal/{jadwal}', [JadwalLapanganController::class, 'update'])->name('lapangan.jadwal.update');
    Route::delete('/lapangan/{lapangan}/jadwal/{jadwal}', [JadwalLapanganController::class, 'destroy'])->name('lapangan.jadwal.destroy');
});

Route::prefix('member')->name('member.')->middleware(['auth', 'role:member'])->group(function () {
    Route::get('/jadwal', [MemberJadwalController::class, 'index'])->name('jadwal');
    Route::get('/riwayat', [MemberRiwayatController::class, 'index'])->name('riwayat');

    Route::get('/langganan/create', [LanggananController::class, 'create'])->name('langganan.create');
    Route::post('/langganan', [LanggananController::class, 'store'])->name('langganan.store');
    Route::get('/langganan/{paket}', [LanggananController::class, 'show'])->name('langganan.show');
});
