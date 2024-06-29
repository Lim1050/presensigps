<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// auth guest middleware
Route::middleware(['guest:karyawan'])->group(function () {
    // login page
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login.page');
    // login proses
    Route::post('/loginproses', [AuthController::class, 'LoginProses'])->name('loginproses');
});

// auth guest middleware
Route::middleware(['guest:user'])->group(function () {
    // login page
    Route::get('/admin/login', function () {
        return view('auth.admin_login');
    })->name('admin.login');
    // login proses
    Route::post('/admin/proses/login', [AuthController::class, 'AdminProsesLogin'])->name('admin.proses.login');
});

Route::middleware(['auth:user'])->group(function () {
    // admin dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'AdminDashboard'])->name('admin.dashboard');

    // Karyawan Index
    Route::get('/karyawan', [KaryawanController::class, 'KaryawanIndex'])->name('karyawan');
    // Karyawan Store
    Route::post('/karyawan/store', [KaryawanController::class, 'KaryawanStore'])->name('karyawan.store');
    // Karyawan edit
    Route::get('/karyawan/edit/{nik}', [KaryawanController::class, 'KaryawanEdit'])->name('karyawan.edit');
    // Karyawan update
    Route::post('/karyawan/update', [KaryawanController::class, 'KaryawanUpdate'])->name('karyawan.update');
    // Karyawan delete
    Route::get('/karyawan/delete/{nik}', [KaryawanController::class, 'KaryawanDelete'])->name('karyawan.delete');

    // Admin logout
    Route::get('/admin/logout', [AuthController::class, 'AdminLogout'])->name('admin.logout');
});

// auth karyawan middleware
Route::middleware(['auth:karyawan'])->group(function () {
    // dashboard page
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // create presensi
    Route::get('/presensi/create', [PresensiController::class, 'PresensiCreate'])->name('presensi.create');
    // store presensi
    Route::post('/presensi/store', [PresensiController::class, 'PresensiStore'])->name('presensi.store');
    // history presensi
    Route::get('/presensi/history', [PresensiController::class, 'PresensiHistory'])->name('presensi.history');
    // gethistory presensi
    Route::post('/gethistory', [PresensiController::class, 'GetHistory']);
    // sakit/izin presensi
    Route::get('/presensi/sakit-izin', [PresensiController::class, 'SakitIzin'])->name('presensi.sakit-izin');
    // create sakit/izin presensi
    Route::get('/presensi/create/sakit-izin', [PresensiController::class, 'CreateSakitIzin'])->name('presensi.create.sakit-izin');
    // store sakit/izin presensi
    Route::post('/presensi/store/sakit-izin', [PresensiController::class, 'StoreSakitIzin'])->name('presensi.store.sakit-izin');


    // profile
    Route::get('/profile', [ProfileController::class, 'Profile'])->name('profile');
    // update profile
    Route::post('/profile/update', [ProfileController::class, 'ProfileUpdate'])->name('profile.update');

    // logout
    Route::get('/logout', [AuthController::class, 'Logout'])->name('logout');
});


