<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ObController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => Auth::check()
    ? redirect()->route(Auth::user()->homeRoute())
    : redirect()->route('login'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:10,1');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profil.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profil.update');
});

// Mahasiswa
Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('dashboard');
    Route::get('/laporan/buat', [ReportController::class, 'create'])->name('laporan.create');
    Route::post('/laporan', [ReportController::class, 'store'])->name('laporan.store');
});

// OB
Route::middleware(['auth', 'role:ob'])->prefix('ob')->name('ob.')->group(function () {
    Route::get('/', [ObController::class, 'index'])->name('dashboard');
});

// Detail laporan & foto: semua role (akses mahasiswa dibatasi ke laporan sendiri di controller)
Route::middleware(['auth', 'role:mahasiswa,ob,admin,superadmin'])->group(function () {
    Route::get('/laporan/{report}', [ReportController::class, 'show'])->whereNumber('report')->name('laporan.show');
    Route::get('/foto/{report}', [ReportController::class, 'photo'])->whereNumber('report')->name('foto.show');
});
Route::patch('/laporan/{report}/status', [ReportController::class, 'updateStatus'])
    ->whereNumber('report')->name('laporan.status')
    ->middleware(['auth', 'role:ob,admin,superadmin']);

// Admin & Superadmin
Route::middleware(['auth', 'role:admin,superadmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/laporan', [Admin\ReportController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export', [Admin\ReportController::class, 'export'])->name('laporan.export');

    Route::get('/data-master', [Admin\MasterDataController::class, 'index'])->name('master');
    Route::post('/gedung', [Admin\MasterDataController::class, 'storeBuilding'])->name('gedung.store');
    Route::put('/gedung/{building}', [Admin\MasterDataController::class, 'updateBuilding'])->name('gedung.update');
    Route::delete('/gedung/{building}', [Admin\MasterDataController::class, 'destroyBuilding'])->name('gedung.destroy');
    Route::post('/gedung/{building}/ruangan', [Admin\MasterDataController::class, 'storeRoom'])->name('ruangan.store');
    Route::put('/ruangan/{room}', [Admin\MasterDataController::class, 'updateRoom'])->name('ruangan.update');
    Route::delete('/ruangan/{room}', [Admin\MasterDataController::class, 'destroyRoom'])->name('ruangan.destroy');
    Route::post('/kategori', [Admin\MasterDataController::class, 'storeCategory'])->name('kategori.store');
    Route::put('/kategori/{category}', [Admin\MasterDataController::class, 'updateCategory'])->name('kategori.update');
    Route::delete('/kategori/{category}', [Admin\MasterDataController::class, 'destroyCategory'])->name('kategori.destroy');

    Route::get('/pengguna/{tab?}', [Admin\UserController::class, 'index'])->name('users');
    Route::post('/pengguna', [Admin\UserController::class, 'store'])->name('users.store');
    Route::put('/pengguna/{user}', [Admin\UserController::class, 'update'])->name('users.update');
    Route::patch('/pengguna/{user}/toggle', [Admin\UserController::class, 'toggle'])->name('users.toggle');
});
