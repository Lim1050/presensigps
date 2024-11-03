<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CabangController;
use App\Http\Controllers\CashbonController;
use App\Http\Controllers\CashbonLimitController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\GajiController;
use App\Http\Controllers\IzinController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\JamKerjaLokasiPenugasanController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\KonfigurasiController;
use App\Http\Controllers\KonfigurasiGajiController;
use App\Http\Controllers\KonfigurasiPotonganController;
use App\Http\Controllers\LemburController;
use App\Http\Controllers\LokasiPenugasanController;
use App\Http\Controllers\PenggajianController;
use App\Http\Controllers\PotonganController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ThrController;
use App\Http\Controllers\UserController;
use App\Models\Penggajian;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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
// Route::group(['middleware' => ['role:super-admin|admin|development,user']], function () {
    // admin dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'AdminDashboard'])->name('admin.dashboard');

    // Karyawan Index
    Route::get('/admin/karyawan', [KaryawanController::class, 'KaryawanIndex'])->name('admin.karyawan');
    // Karyawan Store
    Route::post('/admin/karyawan/store', [KaryawanController::class, 'KaryawanStore'])->name('admin.karyawan.store');
    // Karyawan edit
    Route::get('/admin/karyawan/edit/{nik}', [KaryawanController::class, 'KaryawanEdit'])->name('admin.karyawan.edit');
    // Karyawan update
    Route::put('/admin/karyawan/update/{nik}', [KaryawanController::class, 'KaryawanUpdate'])->name('admin.karyawan.update');
    // Karyawan setting
    Route::get('/admin/karyawan/setting/{nik}', [KaryawanController::class, 'KaryawanSetting'])->name('admin.karyawan.setting');
    // Karyawan setting store
    Route::post('/admin/karyawan/setting/store', [KaryawanController::class, 'KaryawanSettingStore'])->name('admin.karyawan.setting.store');
    // Karyawan setting update
    Route::post('/admin/karyawan/setting/update', [KaryawanController::class, 'KaryawanSettingUpdate'])->name('admin.karyawan.setting.update');
    // Karyawan reset password
    Route::get('/admin/karyawan/reset/password/{nik}', [KaryawanController::class, 'KaryawanResetPassword'])->name('admin.karyawan.reset.password');
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

    // Lokasi Penugasan Index
    Route::get('/admin/lokasi/penugasan', [LokasiPenugasanController::class, 'LokasiPenugasanIndex'])->name('admin.lokasi.penugasan');
    // Lokasi Penugasan Store
    Route::post('/admin/lokasi/penugasan/store', [LokasiPenugasanController::class, 'LokasiPenugasanStore'])->name('admin.lokasi.penugasan.store');
    // Lokasi Penugasan edit
    Route::get('/admin/lokasi/penugasan/edit/{kode_lokasi_penugasan}', [LokasiPenugasanController::class, 'LokasiPenugasanEdit'])->name('admin.lokasi.penugasan.edit');
    // Lokasi Penugasan update
    Route::put('/admin/lokasi/penugasan/update/{kode_lokasi_penugasan}', [LokasiPenugasanController::class, 'LokasiPenugasanUpdate'])->name('admin.lokasi.penugasan.update');
    // Lokasi Penugasan delete
    Route::delete('/admin/lokasi/penugasan/delete/{kode_lokasi_penugasan}', [LokasiPenugasanController::class, 'LokasiPenugasanDelete'])->name('admin.lokasi.penugasan.delete');

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

    // Master Jabatan
    Route::get('/admin/jabatan', [JabatanController::class, 'JabatanIndex'])->name('admin.jabatan');
    // Master Jabatan Store
    Route::post('/admin/jabatan/store', [JabatanController::class, 'JabatanStore'])->name('admin.jabatan.store');
    // Master Jabatan Update
    Route::put('/admin/jabatan/update/{kode_jabatan}', [JabatanController::class, 'JabatanUpdate'])->name('admin.jabatan.update');
    // Master Jabatan
    Route::get('/admin/jabatan/delete/{kode_jabatan}', [JabatanController::class, 'JabatanDelete'])->name('admin.jabatan.delete');

    // Master Gaji
    Route::get('/admin/gaji', [GajiController::class, 'GajiIndex'])->name('admin.gaji');
    // Master Gaji Store
    Route::post('/admin/gaji/store', [GajiController::class, 'GajiStore'])->name('admin.gaji.store');
    // Master Gaji Update
    Route::put('/admin/gaji/update/{kode_gaji}', [GajiController::class, 'GajiUpdate'])->name('admin.gaji.update');
    // Master Gaji
    Route::get('/admin/gaji/delete/{kode_gaji}', [GajiController::class, 'GajiDelete'])->name('admin.gaji.delete');

    // Master Potongan
    Route::get('/admin/potongan', action: [PotonganController::class, 'PotonganIndex'])->name('admin.potongan');
    // Master Potongan Store
    Route::post('/admin/potongan/store', [PotonganController::class, 'PotonganStore'])->name('admin.potongan.store');
    // Master Potongan Update
    Route::put('/admin/potongan/update/{kode_potongan}', [PotonganController::class, 'PotonganUpdate'])->name('admin.potongan.update');
    // Master Potongan
    Route::get('/admin/potongan/delete/{kode_potongan}', [PotonganController::class, 'PotonganDelete'])->name('admin.potongan.delete');

    // persetujuan sakit izin
    Route::get('/admin/persetujuan/sakit/izin', [IzinController::class, 'PersetujuanSakitIzin'])->name('admin.persetujuan.sakit.izin');
    // approval sakit izin
    Route::post('/admin/approval/sakit/izin', [IzinController::class, 'ApprovalSakitIzin'])->name('admin.approval.sakit.izin');
    // batalkan sakit izin
    Route::get('/admin/batalkan/sakit/izin/{kode_izin}', [IzinController::class, 'BatalkanSakitIzin'])->name('admin.batalkan.sakit.izin');
    // cek pengajuan sakit/izin
    Route::post('/admin/cek/pengajuan/sakit-izin', [IzinController::class, 'CekPengajuanSakitIzin'])->name('admin.cek.pengajuan.sakit-izin');

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

    // lembur route
    Route::get('admin/lembur', [LemburController::class, 'LemburIndex'])->name('admin.lembur');
    Route::get('admin/lembur/create', [LemburController::class, 'LemburCreate'])->name('admin.lembur.create');
    Route::post('admin/lembur/store', [LemburController::class, 'LemburStore'])->name('admin.lembur.store');
    // Route::put('admin/lembur/{id}/setuju', action: [LemburController::class, 'LemburSetuju'])->name('admin.lembur.setuju');
    // Route::put('admin/lembur/{id}/batal', action: [LemburController::class, 'LemburBatal'])->name('admin.lembur.batal');
    Route::get('/admin/lembur/show/{id}', action: [LemburController::class, 'LemburShow'])->name('admin.lembur.show');
    Route::get('/admin/lembur/edit/{id}', action: [LemburController::class, 'LemburEdit'])->name('admin.lembur.edit');
    Route::put('/admin/lembur/update/{id}', action: [LemburController::class, 'LemburUpdate'])->name('admin.lembur.update');
    Route::delete('/admin/lembur/delete/{id}', action: [LemburController::class, 'LemburDelete'])->name('admin.lembur.delete');

    Route::get('admin/lokasi-penugasan/get-by-cabang/{kode_cabang}', [LokasiPenugasanController::class, 'getByKodeCabang'])->name('lokasi-penugasan.get-by-cabang');
    Route::get('admin/karyawan/get-by-lokasi/{kode_lokasi_penugasan}', [KaryawanController::class, 'getByLokasiPenugasan'])->name('karyawan.get-by-lokasi');
    Route::get('admin/karyawan/get-jabatan/{nik}', [KaryawanController::class, 'getJabatan'])->name('karyawan.get-jabatan');

    // Penggajian Index
    Route::get('/admin/penggajian', [PenggajianController::class, 'PenggajianIndex'])->name('admin.penggajian');
    // Penggajian Create
    Route::get('/admin/penggajian/create', [PenggajianController::class, 'PenggajianCreate'])->name('admin.penggajian.create');
    // Penggajian Store
    Route::post('/admin/penggajian/store', [PenggajianController::class, 'PenggajianStore'])->name('admin.penggajian.store');
    Route::get('/penggajian/hitung', [PenggajianController::class, 'hitungGaji'])->name('penggajian.hitung');
    Route::get('/admin/penggajian/show/{id}', action: [PenggajianController::class, 'PenggajianShow'])->name('admin.penggajian.show');
    Route::get('/admin/penggajian/export/{id}', [PenggajianController::class, 'ExportPDF'])->name('admin.penggajian.export');
    Route::get('/admin/penggajian/edit/{id}', action: [PenggajianController::class, 'PenggajianEdit'])->name('admin.penggajian.edit');
    Route::put('/admin/penggajian/update/{id}', action: [PenggajianController::class, 'PenggajianUpdate'])->name('admin.penggajian.update');
    Route::get('/admin/penggajian/delete/{id}', action: [PenggajianController::class, 'PenggajianDelete'])->name('admin.penggajian.delete');

    // Cashbon Index
    Route::get('/admin/cashbon', [CashbonController::class, 'CashbonIndex'])->name('admin.cashbon');
    // Cashbon show
    Route::get('/admin/cashbon/show/{id}', [CashbonController::class, 'CashbonShow'])->name('admin.cashbon.show');
    // Cashbon persetujuan
    Route::post('/admin/cashbon/persetujuan', [CashbonController::class, 'CashbonPersetujuan'])->name('admin.cashbon.persetujuan');
    // Cashbon pembatalan
    Route::post('/admin/cashbon/pembatalan', [CashbonController::class, 'CashbonPembatalan'])->name('admin.cashbon.pembatalan');

    // thr Index
    Route::get('/admin/thr', [ThrController::class, 'ThrIndex'])->name('admin.thr');
    Route::get('/admin/thr/create', [ThrController::class, 'ThrCreate'])->name('admin.thr.create');
    Route ::post('/admin/thr/store', action: [ThrController::class, 'ThrStore'])->name('admin.thr.store');
    // thr show
    Route::get('/admin/thr/show/{kode_thr}', [ThrController::class, 'ThrShow'])->name('admin.thr.show');
    // thr persetujuan
    // Route::post('/admin/thr/persetujuan', [ThrController::class, 'ThrPersetujuan'])->name('admin.thr.persetujuan');
    // thr pembatalan
    // Route::post('/admin/thr/pembatalan', [ThrController::class, 'ThrPembatalan'])->name('admin.thr.pembatalan');
    // thr edit
    Route::get('/admin/thr/edit/{kode_thr}', action: [ThrController::class, 'ThrEdit'])->name('admin.thr.edit');
    // thr update
    Route::put('/admin/thr/update/{kode_thr}', action: [ThrController::class, 'ThrUpdate'])->name('admin.thr.update');
    // thr delete
    Route::delete('/admin/thr/delete/{kode_thr}', action: [ThrController::class, 'ThrDelete'])->name('admin.thr.delete');
    // thr cetak
    Route::get('/admin/thr/export/{kdoe_thr}', action: [ThrController::class, 'ExportPDF'])->name('admin.thr.export');

    // untuk form create dengan pencarian
    Route::get('/admin/karyawan/search', [KaryawanController::class, 'search'])->name('admin.karyawan.search');
    Route::get('/admin/jabatan/search', [JabatanController::class, 'search'])->name('admin.jabatan.search');
    Route::get('/admin/lokasi-penugasan/search', [LokasiPenugasanController::class, 'search'])->name('admin.lokasi-penugasan.search');
    Route::get('/admin/cabang/search', [CabangController::class, 'search'])->name('admin.cabang.search');
    Route::get('/admin/karyawan/get-data/{nik}', [KaryawanController::class, 'getKaryawanData'])->name('admin.karyawan.getData');
    Route::get('/admin/thr/get-thr/{nik}', [ThrController::class, 'getTHR'])->name(name: 'admin.thr.get-thr');


    // Konfigurasi Jenis Gaji
    Route::get('/admin/konfigurasi/jenis/gaji', action: [KonfigurasiGajiController::class, 'KonfigurasiGajiIndex'])->name('admin.konfigurasi.jenis.gaji');
    // Konfigurasi Jenis Gaji Store
    Route::post('/admin/konfigurasi/jenis/gaji/store', [KonfigurasiGajiController::class, 'KonfigurasiGajiStore'])->name('admin.konfigurasi.jenis.gaji.store');
    // Konfigurasi Jenis Gaji Update
    Route::put('/admin/konfigurasi/jenis/gaji/update/{kode_jenis_gaji}', [KonfigurasiGajiController::class, 'KonfigurasiGajiUpdate'])->name('admin.konfigurasi.jenis.gaji.update');
    // Kantor Jenis Gaji delete
    Route::get('/admin/konfigurasi/jenis/gaji/delete/{kode_jenis_gaji}', [KonfigurasiGajiController::class, 'KonfigurasiGajiDelete'])->name('admin.konfigurasi.jenis.gaji.delete');

    // Konfigurasi Jenis Potongan
    Route::get('/admin/konfigurasi/jenis/potongan', action: [KonfigurasiPotonganController::class, 'KonfigurasiPotonganIndex'])->name('admin.konfigurasi.jenis.potongan');
    // Konfigurasi Jenis Potongan Store
    Route::post('/admin/konfigurasi/jenis/potongan/store', [KonfigurasiPotonganController::class, 'KonfigurasiPotonganStore'])->name('admin.konfigurasi.jenis.potongan.store');
    // Konfigurasi Jenis Potongan Update
    Route::put('/admin/konfigurasi/jenis/potongan/update/{kode_jenis_potongan}', [KonfigurasiPotonganController::class, 'KonfigurasiPotonganUpdate'])->name('admin.konfigurasi.jenis.potongan.update');
    // Kantor Jenis Potongan delete
    Route::get('/admin/konfigurasi/jenis/potongan/delete/{kode_jenis_potongan}', [KonfigurasiPotonganController::class, 'KonfigurasiPotonganDelete'])->name('admin.konfigurasi.jenis.potongan.delete');

    // Konfigurasi Cashbon Limit
    Route::get('/admin/konfigurasi/cashbon/limit', action: [CashbonLimitController::class, 'CashbonLimitIndex'])->name('admin.konfigurasi.cashbon.limit');
    Route::post('/admin/konfigurasi/cashbon/global-limit', action: [CashbonLimitController::class, 'CashbonSetGlobalLimit'])->name('admin.konfigurasi.cashbon.global.limit');
    Route::post('/admin/konfigurasi/cashbon/karyawan-limit/{nik}', action: [CashbonLimitController::class, 'CashbonSetKaryawanLimit'])->name('admin.konfigurasi.cashbon.karyawan.limit');

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

    // Konfigurasi Jam Kerja lokasi penugasan
    Route::get('/admin/konfigurasi/jam-kerja/lokasi-penugasan', [JamKerjaLokasiPenugasanController::class, 'JamKerjaLokasiPenugasanIndex'])->name('admin.konfigurasi.jam-kerja.lokasi-penugasan');
    // Konfigurasi Jam Kerja lokasi penugasan Create
    Route::get('/admin/konfigurasi/jam-kerja/lokasi-penugasan/create', [JamKerjaLokasiPenugasanController::class, 'JamKerjaLokasiPenugasanCreate'])->name('admin.konfigurasi.jam-kerja.lokasi-penugasan.create');
    // Konfigurasi Jam Kerja lokasi penugasan Store
    Route::post('/admin/konfigurasi/jam-kerja/lokasi-penugasan/store', [JamKerjaLokasiPenugasanController::class, 'JamKerjaLokasiPenugasanStore'])->name('admin.konfigurasi.jam-kerja.lokasi-penugasan.store');
    // Konfigurasi Jam Kerja lokasi penugasan view
    Route::get('/admin/konfigurasi/jam-kerja/lokasi-penugasan/view/{kode_jk_lp_c}', [JamKerjaLokasiPenugasanController::class, 'JamKerjaLokasiPenugasanView'])->name('admin.konfigurasi.jam-kerja.lokasi-penugasan.view');
    // Konfigurasi Jam Kerja lokasi penugasan edit
    Route::get('/admin/konfigurasi/jam-kerja/lokasi-penugasan/edit/{kode_jk_lp_c}', [JamKerjaLokasiPenugasanController::class, 'JamKerjaLokasiPenugasanEdit'])->name('admin.konfigurasi.jam-kerja.lokasi-penugasan.edit');
    // Konfigurasi Jam Kerja lokasi penugasan update
    Route::post('/admin/konfigurasi/jam-kerja/lokasi-penugasan/update/{kode_jk_lp_c}', [JamKerjaLokasiPenugasanController::class, 'JamKerjaLokasiPenugasanUpdate'])->name('admin.konfigurasi.jam-kerja.lokasi-penugasan.update');
    // Konfigurasi Jam Kerja lokasi penugasan delete
    Route::get('/admin/konfigurasi/jam-kerja/lokasi-penugasan/delete/{kode_jk_lp_c}', [JamKerjaLokasiPenugasanController::class, 'JamKerjaLokasiPenugasanDelete'])->name('admin.konfigurasi.jam-kerja.lokasi-penugasan.delete');

    // Konfigurasi User
    Route::get('/admin/konfigurasi/user', [UserController::class, 'UserIndex'])->name('admin.konfigurasi.user');
    // Konfigurasi User Store
    Route::post('/admin/konfigurasi/user/store', [UserController::class, 'UserStore'])->name('admin.konfigurasi.user.store');
    // Konfigurasi User Update
    Route::put('/admin/konfigurasi/user/update/{id}', [UserController::class, 'UserUpdate'])->name('admin.konfigurasi.user.update');
    // Konfigurasi User reset password
    Route::get('/admin/konfigurasi/user/reset/password/{id}', [UserController::class, 'UserResetPassword'])->name('admin.konfigurasi.user.reset.password');
    // Konfigurasi User delete
    Route::get('/admin/konfigurasi/user/delete/{id}', [UserController::class, 'UserDelete'])->name('admin.konfigurasi.user.delete');

    // Konfigurasi Role
    Route::get('/admin/konfigurasi/role', [RoleController::class, 'RoleIndex'])->name('admin.konfigurasi.role');
    // Konfigurasi role Import
    Route::post('/admin/konfigurasi/role/import', [RoleController::class, 'RoleImport'])->name('admin.konfigurasi.role.import');
    // Konfigurasi role Export
    Route::get('/admin/konfigurasi/role/export', [RoleController::class, 'RoleExport'])->name('admin.konfigurasi.role.export');
    // Konfigurasi role Store
    Route::post('/admin/konfigurasi/role/store', [RoleController::class, 'RoleStore'])->name('admin.konfigurasi.role.store');
    // Konfigurasi role Update
    Route::put('/admin/konfigurasi/role/update/{id}', [RoleController::class, 'RoleUpdate'])->name('admin.konfigurasi.role.update');
    // Konfigurasi role delete
    Route::get('/admin/konfigurasi/role/delete/{id}', [RoleController::class, 'RoleDelete'])->name('admin.konfigurasi.role.delete');

    // Konfigurasi Permission
    Route::get('/admin/konfigurasi/permission', [RoleController::class, 'PermissionIndex'])->name('admin.konfigurasi.permission');
    // Konfigurasi Permission Import
    Route::post('/admin/konfigurasi/permission/import', [RoleController::class, 'PermissionImport'])->name('admin.konfigurasi.permission.import');
    // Konfigurasi Permission Export
    Route::get('/admin/konfigurasi/permission/export', [RoleController::class, 'PermissionExport'])->name('admin.konfigurasi.permission.export');
    // Konfigurasi Permission Store
    Route::post('/admin/konfigurasi/permission/store', [RoleController::class, 'PermissionStore'])->name('admin.konfigurasi.permission.store');
    // Konfigurasi Permission Update
    Route::put('/admin/konfigurasi/permission/update/{id}', [RoleController::class, 'PermissionUpdate'])->name('admin.konfigurasi.permission.update');
    // Konfigurasi Permission delete
    Route::get('/admin/konfigurasi/permission/delete/{id}', [RoleController::class, 'PermissionDelete'])->name('admin.konfigurasi.permission.delete');

    // Konfigurasi Roles in Permission
    Route::get('/admin/konfigurasi/add-role-in-permission', [RoleController::class, 'RolesInPermissionsIndex'])->name('admin.konfigurasi.add-role-in-permission');
    // Konfigurasi Roles in Permission Store
    Route::post('/admin/konfigurasi/add-role-in-permission-store', [RoleController::class, 'RolesInPermissionsStore'])->name('admin.konfigurasi.add-role-in-permission-store');
    // Konfigurasi Roles in Permission Edit
    Route::get('/admin/konfigurasi/add-role-in-permission-edit/{id}', [RoleController::class, 'RolesInPermissionsEdit'])->name('admin.konfigurasi.add-role-in-permission-edit');
    // Konfigurasi Roles in Permission update
    Route::post('/admin/konfigurasi/add-role-in-permission-update/{id}', [RoleController::class, 'RolesInPermissionsUpdate'])->name('admin.konfigurasi.add-role-in-permission-update');
    // Konfigurasi Roles in Permission Delete
    Route::get('/admin/konfigurasi/add-role-in-permission-delete/{id}', [RoleController::class, 'RolesInPermissionsDelete'])->name('admin.konfigurasi.add-role-in-permission-delete');


    // Admin Profile
    Route::get('/admin/profile', [UserController::class, 'AdminProfile'])->name('admin.profile');
    Route::put('/admin/profile/update/foto', [UserController::class, 'AdminProfileUpdateFoto'])->name('admin.profile.update.foto');
    Route::put('/admin/profile/update/detail', [UserController::class, 'AdminProfileUpdateDetail'])->name('admin.profile.update.detail');
    Route::put('/admin/profile/update/password', action: [UserController::class, 'AdminProfileUpdatePassword'])->name('admin.profile.update.password');
    // Admin logout
    Route::get('/admin/logout', [AuthController::class, 'AdminLogout'])->name('admin.logout');
});

// auth karyawan middleware
Route::middleware(['auth:karyawan'])->group(function () {
    // dashboard page
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // update status lembur
    Route::post('/lembur/{id}/update-status', [LemburController::class, 'LemburUpdateStatus'])->name('lembur.update.status');

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
    // Izin Show Act
    Route::get('/izin/{kode_izin}/showact', [IzinController::class, 'IzinShowAct']);

    // Izin Absen
    Route::get('/izin/absen', [IzinController::class, 'CreateIzinAbsen'])->name('izin.absen');
    // Izin Absen Store
    Route::post('/izin/absen/store', [IzinController::class, 'StoreIzinAbsen'])->name('izin.absen.store');
    // Izin Absen edit
    Route::get('/izin/absen/edit/{kode_izin}', [IzinController::class, 'EditIzinAbsen'])->name('izin.absen.edit');
    // Izin Absen update
    Route::post('/izin/absen/update/{kode_izin}', [IzinController::class, 'UpdateIzinAbsen'])->name('izin.absen.update');

    // Izin Sakit
    Route::get('/izin/sakit', [IzinController::class, 'CreateIzinSakit'])->name('izin.sakit');
    // Izin Sakit Store
    Route::post('/izin/sakit/store', [IzinController::class, 'StoreIzinSakit'])->name('izin.sakit.store');
    // Izin sakit edit
    Route::get('/izin/sakit/edit/{kode_izin}', [IzinController::class, 'EditIzinSakit'])->name('izin.sakit.edit');
    // Izin sakit update
    Route::post('/izin/sakit/update/{kode_izin}', [IzinController::class, 'UpdateIzinSakit'])->name('izin.sakit.update');

    // Izin Cuti
    Route::get('/izin/cuti', [IzinController::class, 'CreateIzinCuti'])->name('izin.cuti');
    // Izin Cuti Store
    Route::post('/izin/cuti/store', [IzinController::class, 'StoreIzinCuti'])->name('izin.cuti.store');
    // Izin cuti edit
    Route::get('/izin/cuti/edit/{kode_izin}', [IzinController::class, 'EditIzinCuti'])->name('izin.cuti.edit');
    // Izin cuti update
    Route::post('/izin/cuti/update/{kode_izin}', [IzinController::class, 'UpdateIzinCuti'])->name('izin.cuti.update');

    // Izin sakit cuti delete
    Route::get('/izin/delete/{kode_izin}', [IzinController::class, 'DeleteIzin'])->name('izin.delete');

    // keuangan
    Route::get('/keuangan', [KeuanganController::class, 'KeuanganIndex'])->name('keuangan');
    // keuangan gaji
    Route::get('/keuangan/gaji', [KeuanganController::class, 'KeuanganGaji'])->name('keuangan.gaji');
    // keuangan cashbon
    Route::get('/keuangan/cashbon', [KeuanganController::class, 'KeuanganCashbon'])->name('keuangan.cashbon');
    // keuangan Cashbon show
    Route::get('/keuangan/cashbon/show/{id}', [KeuanganController::class, 'KeuanganCashbonShow'])->name('keuangan.cashbon.show');
    // keuangan cashbon create
    Route::get('/keuangan/cashbon/create', action: [KeuanganController::class, 'KeuanganCashbonCreate'])->name('keuangan.cashbon.create');
    // keuangan cashbon store
    Route::post('/keuangan/cashbon/store', action: [KeuanganController::class, 'KeuanganCashbonStore'])->name('keuangan.cashbon.store');
    // Rute untuk menampilkan halaman edit
    Route::get('/keuangan/cashbon/edit/{id}', action: [KeuanganController::class, 'KeuanganCashbonEdit'])->name('keuangan.cashbon.edit');
    // Rute untuk memperbarui data cashbon
    Route::put('/keuangan/cashbon/update/{id}', [KeuanganController::class, 'KeuanganCashbonUpdate'])->name('keuangan.cashbon.update');
    // Rute untuk delete data cashbon
    Route::delete('/keuangan/cashbon/delete/{id}', [KeuanganController::class, 'KeuanganCashbonDelete'])->name('keuangan.cashbon.delete');

    // profile
    Route::get('/profile', [ProfileController::class, 'Profile'])->name('profile');
    // update profile
    // Route::post('/profile/update', [ProfileController::class, 'ProfileUpdate'])->name('profile.update');
    Route::put('/profile/update/foto', action: [ProfileController::class, 'ProfileUpdateFoto'])->name('profile.update.foto');
    Route::put('/profile/update/detail', [ProfileController::class, 'ProfileUpdateDetail'])->name('profile.update.detail');
    Route::put('/profile/update/password', action: [ProfileController::class, 'ProfileUpdatePassword'])->name('profile.update.password');

    // logout
    Route::get('/logout', [AuthController::class, 'Logout'])->name('logout');
});

// Route::get('/create-role-permission', function () {
//     try {
//         Role::create(['name' => 'admin-presensi']);
//         // Permission::create(['name' => 'view-karyawan']);
//         // Permission::create(['name' => 'view-departemen']);
//         echo "Success";
//     } catch (\Throwable $th) {
//         echo "Error";
//     }
// });

// Route::get('/give-user-role', function() {
//     try {
//         $user = User::findOrFail(2);
//         $user->assignRole('admin');
//         echo "Success";
//     } catch (\Throwable $th) {
//         echo "Error";
//     }
// });

// Route::get('/give-role-permission', function() {
//     try {
//         $role = Role::findOrFail(1);
//         $permission = 'view-departemen';
//         $role->givePermissionTo($permission);
//         echo "Success";
//     } catch (\Throwable $th) {
//         echo "Error";
//     }
// });
