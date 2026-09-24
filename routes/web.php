<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PendaftaranController as AdminPendaftaranController;
use App\Http\Controllers\Mahasiswa\PendaftaranController as MahasiswaPendaftaranController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - PMB Online
|--------------------------------------------------------------------------
| Halaman publik, auth, lalu grup admin & mahasiswa yang dilindungi
| middleware role masing-masing.
*/

// Halaman publik
Route::redirect('/', '/informasi-pmb');
Route::get('/informasi-pmb', [InformationController::class, 'index'])->name('informasi-pmb');

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.attempt');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Dashboard router: arahkan otomatis sesuai role
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

// ==================== ADMIN ====================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminPendaftaranController::class, 'dashboard'])->name('dashboard');

    Route::resource('users', UserController::class)->except(['show']);

    Route::get('/pendaftaran', [AdminPendaftaranController::class, 'index'])->name('pendaftaran.index');
    Route::get('/pendaftaran/{pendaftaran}', [AdminPendaftaranController::class, 'show'])->name('pendaftaran.show');
    Route::get('/pendaftaran/{pendaftaran}/edit', [AdminPendaftaranController::class, 'edit'])->name('pendaftaran.edit');
    Route::put('/pendaftaran/{pendaftaran}', [AdminPendaftaranController::class, 'update'])->name('pendaftaran.update');
    Route::delete('/pendaftaran/{pendaftaran}', [AdminPendaftaranController::class, 'destroy'])->name('pendaftaran.destroy');

    Route::get('/provinces/{province}/regencies', [AdminPendaftaranController::class, 'regenciesByProvince'])->name('regencies-by-province');
});

// ==================== CALON MAHASISWA ====================
Route::middleware(['auth', 'mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/dashboard', [MahasiswaPendaftaranController::class, 'dashboard'])->name('dashboard');

    Route::get('/pendaftaran/create', [MahasiswaPendaftaranController::class, 'create'])->name('pendaftaran.create');
    Route::post('/pendaftaran', [MahasiswaPendaftaranController::class, 'store'])->name('pendaftaran.store');
    Route::get('/pendaftaran', [MahasiswaPendaftaranController::class, 'show'])->name('pendaftaran.show');
    Route::get('/pendaftaran/cetak', [MahasiswaPendaftaranController::class, 'cetak'])->name('pendaftaran.cetak');

    Route::get('/provinces/{province}/regencies', [MahasiswaPendaftaranController::class, 'regenciesByProvince'])->name('regencies-by-province');
});
