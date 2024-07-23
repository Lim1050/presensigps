<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CabangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\IzinController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KonfigurasiController;
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
    // Karyawan setting
    Route::get('/admin/karyawan/setting/{nik}', [KaryawanController::class, 'KaryawanSetting'])->name('admin.karyawan.setting');
    // Karyawan setting store
    Route::post('/admin/karyawan/setting/store', [KaryawanController::class, 'KaryawanSettingStore'])->name('admin.karyawan.setting.store');
    // Karyawan setting update
    Route::post('/admin/karyawan/setting/update', [KaryawanController::class, 'KaryawanSettingUpdate'])->name('admin.karyawan.setting.update');
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
    Route::get('/admin/departemen/delete/{kode_departemen}', [DepartemenController::class, 'DepartemenDelete'])->name('admin.departemen.delete');

    // Kantor Cabang
    Route::get('/admin/cabang', [CabangController::class, 'CabangIndex'])->name('admin.cabang');
    // Kantor cabang Store
    Route::post('/admin/cabang/store', [CabangController::class, 'CabangStore'])->name('admin.cabang.store');
    // Kantor cabang edit
    Route::get('/admin/cabang/edit/{kode_cabang}', [CabangController::class, 'cabangEdit'])->name('admin.cabang.edit');
    // Kantor cabang update
    Route::post('/admin/cabang/update', [CabangController::class, 'CabangUpdate'])->name('admin.cabang.update');
    // Kantor cabang delete
    Route::get('/admin/cabang/delete/{kode_cabang}', [CabangController::class, 'cabangDelete'])->name('admin.cabang.delete');

    // Master Cuti
    Route::get('/admin/cuti', [IzinController::class, 'CutiMaster'])->name('admin.cuti');
    // Master Cuti Store
    Route::post('/admin/cuti/store', [IzinController::class, 'CutiMasterStore'])->name('admin.cuti.store');
    // Master Cuti Update
    Route::put('/admin/cuti/update/{kode_cuti}', [IzinController::class, 'CutiMasterUpdate'])->name('admin.cuti.update');
    // Master Cuti delete
    Route::get('/admin/cuti/delete/{kode_cuti}', [IzinController::class, 'CutiMasterDelete'])->name('admin.cuti.delete');

    // laporan presensi
    Route::get('/admin/laporan/presensi', [PresensiController::class, 'LaporanPresensi'])->name('admin.laporan.presensi');
    // print laporan presensi
    Route::post('/admin/laporan/print', [PresensiController::class, 'LaporanPrint'])->name('admin.laporan.print');
    // rekap presensi
    Route::get('/admin/rekap/presensi', [PresensiController::class, 'RekapPresensi'])->name('admin.rekap.presensi');
    // print rekap presensi
    Route::post('/admin/rekap/print', [PresensiController::class, 'RekapPrint'])->name('admin.rekap.print');

    // monitoring presensi Index
    Route::get('/admin/monitoring/presensi', [PresensiController::class, 'MonitoringPresensi'])->name('admin.monitoring.presensi');
    // monitoring getpresensi
    Route::post('/admin/monitoring/getpresensi', [PresensiController::class, 'MonitoringGetPresensi'])->name('admin.monitoring.getpresensi');
    // tampilkan peta
    Route::post('/admin/presensi/tampilkanpeta', [PresensiController::class, 'TampilkanPeta'])->name('admin.presensi.tampilkanpeta');

    // persetujuan sakit izin
    Route::get('/admin/persetujuan/sakit/izin', [PresensiController::class, 'PersetujuanSakitIzin'])->name('admin.persetujuan.sakit.izin');
    // approval sakit izin
    Route::post('/admin/approval/sakit/izin', [PresensiController::class, 'ApprovalSakitIzin'])->name('admin.approval.sakit.izin');
    // batalkan sakit izin
    Route::get('/admin/batalkan/sakit/izin/{kode_izin}', [PresensiController::class, 'BatalkanSakitIzin'])->name('admin.batalkan.sakit.izin');
    // cek pengajuan sakit/izin
    // Route::post('/admin/cek/pengajuan/sakit-izin', [PresensiController::class, 'CekPengajuanSakitIzin'])->name('admin.cek.pengajuan.sakit-izin');

    // Konfigurasi Lokasi Kantor
    Route::get('/admin/konfigurasi/lokasi/kantor', [KonfigurasiController::class, 'LokasiKantor'])->name('admin.konfigurasi.lokasi.kantor');
    // update Lokasi Kantor
    Route::post('/admin/update/lokasi/kantor', [KonfigurasiController::class, 'UpdateLokasiKantor'])->name('admin.update.lokasi.kantor');

    // Konfigurasi Jam Kerja
    Route::get('/admin/konfigurasi/jam/kerja', [KonfigurasiController::class, 'JamKerja'])->name('admin.konfigurasi.jam.kerja');
    // Konfigurasi Jam Kerja Store
    Route::post('/admin/konfigurasi/jam/kerja/store', [KonfigurasiController::class, 'JamKerjaStore'])->name('admin.konfigurasi.jam.kerja.store');
    // Konfigurasi Jam Kerja Update
    Route::put('/admin/konfigurasi/jam/kerja/update/{kode_jam_kerja}', [KonfigurasiController::class, 'JamKerjaUpdate'])->name('admin.konfigurasi.jam.kerja.update');
    // Kantor Jam Kerja delete
    Route::get('/admin/konfigurasi/jam/kerja/delete/{kode_jam_kerja}', [KonfigurasiController::class, 'JamKerjaDelete'])->name('admin.konfigurasi.jam.kerja.delete');

    // Konfigurasi Jam Kerja Departement
    Route::get('/admin/konfigurasi/jam-kerja-dept', [KonfigurasiController::class, 'JamKerjaDept'])->name('admin.konfigurasi.jam-kerja-dept');
    // Konfigurasi Jam Kerja Departement Create
    Route::get('/admin/konfigurasi/jam-kerja-dept/create', [KonfigurasiController::class, 'JamKerjaDeptCreate'])->name('admin.konfigurasi.jam-kerja-dept.create');
    // Konfigurasi Jam Kerja Departement Store
    Route::post('/admin/konfigurasi/jam-kerja-dept/store', [KonfigurasiController::class, 'JamKerjaDeptStore'])->name('admin.konfigurasi.jam-kerja-dept.store');
    // Konfigurasi Jam Kerja Departement view
    Route::get('/admin/konfigurasi/jam-kerja-dept/view/{kode_jk_dept}', [KonfigurasiController::class, 'JamKerjaDeptView'])->name('admin.konfigurasi.jam-kerja-dept.view');
    // Konfigurasi Jam Kerja Departement edit
    Route::get('/admin/konfigurasi/jam-kerja-dept/edit/{kode_jk_dept}', [KonfigurasiController::class, 'JamKerjaDeptEdit'])->name('admin.konfigurasi.jam-kerja-dept.edit');
    // Konfigurasi Jam Kerja Departement update
    Route::post('/admin/konfigurasi/jam-kerja-dept/update/{kode_jk_dept}', [KonfigurasiController::class, 'JamKerjaDeptUpdate'])->name('admin.konfigurasi.jam-kerja-dept.update');
    // Konfigurasi Jam Kerja Departement delete
    Route::get('/admin/konfigurasi/jam-kerja-dept/delete/{kode_jk_dept}', [KonfigurasiController::class, 'JamKerjaDeptDelete'])->name('admin.konfigurasi.jam-kerja-dept.delete');

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


    // // create sakit/izin presensi
    // Route::get('/presensi/create/sakit-izin', [PresensiController::class, 'CreateSakitIzin'])->name('presensi.create.sakit-izin');
    // // store sakit/izin presensi
    // Route::post('/presensi/store/sakit-izin', [PresensiController::class, 'StoreSakitIzin'])->name('presensi.store.sakit-izin');
    // cek pengajuan sakit/izin
    Route::post('/presensi/cek/pengajuan/sakit-izin', [PresensiController::class, 'CekPengajuanSakitIzin'])->name('presensi.cek.pengajuan.sakit-izin');

    // sakit/izin/cuti
    Route::get('/izin', [IzinController::class, 'IzinSakitCuti'])->name('izin');
    // Izin Absen
    Route::get('/izin/absen', [IzinController::class, 'CreateIzinAbsen'])->name('izin.absen');
    // Izin Absen Store
    Route::post('/izin/absen/store', [IzinController::class, 'StoreIzinAbsen'])->name('izin.absen.store');

    // Izin Sakit
    Route::get('/izin/sakit', [IzinController::class, 'CreateIzinSakit'])->name('izin.sakit');
    // Izin Sakit Store
    Route::post('/izin/sakit/store', [IzinController::class, 'StoreIzinSakit'])->name('izin.sakit.store');

    // Izin Cuti
    Route::get('/izin/cuti', [IzinController::class, 'CreateIzinCuti'])->name('izin.cuti');
    // Izin Cuti Store
    Route::post('/izin/cuti/store', [IzinController::class, 'StoreIzinCuti'])->name('izin.cuti.store');

    // profile
    Route::get('/profile', [ProfileController::class, 'Profile'])->name('profile');
    // update profile
    Route::post('/profile/update', [ProfileController::class, 'ProfileUpdate'])->name('profile.update');

    // logout
    Route::get('/logout', [AuthController::class, 'Logout'])->name('logout');
});


