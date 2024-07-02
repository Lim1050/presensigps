<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartemenController;
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
    Route::get('/admin/karyawan', [KaryawanController::class, 'KaryawanIndex'])->name('admin.karyawan');
    // Karyawan Store
    Route::post('/admin/karyawan/store', [KaryawanController::class, 'KaryawanStore'])->name('admin.karyawan.store');
    // Karyawan edit
    Route::get('/admin/karyawan/edit/{nik}', [KaryawanController::class, 'KaryawanEdit'])->name('admin.karyawan.edit');
    // Karyawan update
    Route::post('/admin/karyawan/update', [KaryawanController::class, 'KaryawanUpdate'])->name('admin.karyawan.update');
    // Karyawan delete
    Route::get('/admin/karyawan/delete/{nik}', [KaryawanController::class, 'KaryawanDelete'])->name('admin.karyawan.delete');

    // departemen Index
    Route::get('/admin/departemen', [DepartemenController::class, 'DepartemenIndex'])->name('admin.departemen');
    // departemen Store
    Route::post('/admin/departemen/store', [DepartemenController::class, 'DepartemenStore'])->name('admin.departemen.store');
    // departemen edit
    Route::get('/admin/departemen/edit/{kode_departemen}', [DepartemenController::class, 'DepartemenEdit'])->name('admin.departemen.edit');
    // departemen update
    Route::post('/admin/departemen/update', [DepartemenController::class, 'DepartemenUpdate'])->name('admin.departemen.update');
    // departemen delete
    Route::get('/admin/departemen/delete/{nik}', [DepartemenController::class, 'DepartemenDelete'])->name('admin.departemen.delete');

    // laporan presensi
    Route::get('/admin/laporan/presensi', [PresensiController::class, 'LaporanPresensi'])->name('admin.laporan.presensi');
    // print laporan presensi
    Route::post('/admin/laporan/print', [PresensiController::class, 'LaporanPrint'])->name('admin.laporan.print');


    // monitoring presensi Index
    Route::get('/admin/monitoring/presensi', [PresensiController::class, 'MonitoringPresensi'])->name('admin.monitoring.presensi');
    // monitoring getpresensi
    Route::post('/admin/monitoring/getpresensi', [PresensiController::class, 'MonitoringGetPresensi'])->name('admin.monitoring.getpresensi');
    // tampilkan peta
    Route::post('/admin/presensi/tampilkanpeta', [PresensiController::class, 'TampilkanPeta'])->name('admin.presensi.tampilkanpeta');

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


