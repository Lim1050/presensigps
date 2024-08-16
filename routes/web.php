<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CabangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\GajiController;
use App\Http\Controllers\IzinController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KonfigurasiController;
use App\Http\Controllers\PenggajianController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
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
    Route::get('/admin/gaji/delete/{kode_gaji}', [gajiController::class, 'GajiDelete'])->name('admin.gaji.delete');

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

    // Penggajian Index
    Route::get('/admin/penggajian', [PenggajianController::class, 'PenggajianIndex'])->name('admin.penggajian');
    // Penggajian Create
    Route::get('/admin/penggajian/create', [PenggajianController::class, 'PenggajianCreate'])->name('admin.penggajian.create');
    // Penggajian Store
    Route::post('/admin/penggajian/store', [PenggajianController::class, 'PenggajianStore'])->name('admin.penggajian.store');
    Route::get('/penggajian/hitung', [PenggajianController::class, 'hitungGaji'])->name('penggajian.hitung');

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

    // profile
    Route::get('/profile', [ProfileController::class, 'Profile'])->name('profile');
    // update profile
    Route::post('/profile/update', [ProfileController::class, 'ProfileUpdate'])->name('profile.update');

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
