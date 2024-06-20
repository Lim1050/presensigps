<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PresensiController;
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
    Route::get('/', function () {
        return view('auth.login');
    // login proses
    })->name('login.page');
    Route::post('/login', [AuthController::class, 'Login'])->name('login');
});

// auth karyawan middleware
Route::middleware(['auth:karyawan'])->group(function () {
    // dashboard page
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // presensi
    Route::get('/presensi/create', [PresensiController::class, 'PresensiCreate'])->name('presensi.create');

    // logout
    Route::get('/logout', [AuthController::class, 'Logout'])->name('logout');
});
