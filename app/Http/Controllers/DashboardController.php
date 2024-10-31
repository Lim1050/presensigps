<?php

namespace App\Http\Controllers;

use App\Models\Lembur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use IntlDateFormatter;

class DashboardController extends Controller
{
    public function Index()
    {
        $hari_ini = date("Y-m-d");
        $bulan_ini = date("m");
        $tahun_ini = date("Y");
        $nik = Auth::guard('karyawan')->user()->nik;
        $nama = Auth::guard('karyawan')->user()->nama_lengkap;
        $jabatan = Auth::guard('karyawan')->user()->kode_jabatan;
        $presensi_hari_ini = DB::table('presensi')
                                ->select('presensi.*', 'pengajuan_izin.keterangan')
                                ->leftJoin('pengajuan_izin', 'presensi.kode_izin', '=', 'pengajuan_izin.kode_izin')
                                ->where('presensi.nik', $nik)
                                ->where('tanggal_presensi', $hari_ini)
                                ->first();

        $history_bulan_ini = DB::table('presensi')
                                ->select('presensi.*', 'jam_kerja.nama_jam_kerja', 'jam_kerja.jam_masuk as jam_kerja_masuk', 'jam_kerja.jam_pulang', 'pengajuan_izin.keterangan')
                                ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
                                ->leftJoin('pengajuan_izin', 'presensi.kode_izin', '=', 'pengajuan_izin.kode_izin')
                                ->where('presensi.nik', $nik)
                                ->whereRaw('MONTH(presensi.tanggal_presensi) = ?', [$bulan_ini])
                                ->whereRaw('YEAR(presensi.tanggal_presensi) = ?', [$tahun_ini])
                                ->orderBy('presensi.tanggal_presensi', 'desc')
                                ->get();

        $rekap_presensi = DB::table('presensi')
                            ->selectRaw('
                            SUM(IF(status="hadir",1,0))as jml_hadir,
                            SUM(IF(status="izin",1,0))as jml_izin,
                            SUM(IF(status="sakit",1,0))as jml_sakit,
                            SUM(IF(status="cuti",1,0))as jml_cuti,
                            SUM(IF(presensi.jam_masuk > jam_kerja.jam_masuk,1,0)) as jml_terlambat')
                            ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
                            ->where('nik', $nik)
                            ->whereRaw('MONTH(tanggal_presensi)="' . $bulan_ini . '"')
                            ->whereRaw('YEAR(tanggal_presensi)="' . $tahun_ini . '"')
                            ->first();
        // dd($rekap_presensi);
        $leaderboards = DB::table('presensi')
                            ->select('presensi.*', 'karyawan.*', 'jam_kerja.jam_masuk as jam_kerja_masuk')
                            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
                            ->join('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
                            ->where('tanggal_presensi', $hari_ini)
                            ->orderBy('jam_masuk', 'ASC')
                            ->get();

        // dd($leaderboards);

        // dd($rekap_presensi);
        $months = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ];

        // Ambil nama bulan dari array
        $monthName = $months[$bulan_ini];

        $daftar_lembur = Lembur::where('nik', operator: $nik)->get();
        // dd($daftar_lembur);

        // rekap izin sakit
        // $rekap_sakit_izin = DB::table('pengajuan_izin')
        //     ->selectRaw('SUM(IF(status="izin",1,0)) as jumlah_izin, SUM(IF(status="sakit",1,0)) as jumlah_sakit, SUM(IF(status="cuti",1,0)) as jumlah_cuti')
        //     ->where('nik', $nik)
        //     ->whereRaw('MONTH(tanggal_izin_dari)="' . $bulan_ini . '"')
        //     ->whereRaw('YEAR(tanggal_izin_dari)="' . $tahun_ini . '"')
        //     ->where('status_approved', 1)
        //     ->first();

        return view('dashboard.dashboard', compact('rekap_presensi', 'monthName', 'tahun_ini', 'nama','jabatan','presensi_hari_ini', 'history_bulan_ini', 'leaderboards', 'daftar_lembur'));
    }

    public function AdminDashboard()
    {
        $hari_ini = date("Y-m-d");
        $bulan_ini = date("m");
        $tahun_ini = date("Y");
        $rekap_presensi = DB::table('presensi')
                            ->selectRaw('
                            SUM(IF(status="hadir",1,0))as jml_hadir,
                            SUM(IF(status="izin",1,0))as jml_izin,
                            SUM(IF(status="sakit",1,0))as jml_sakit,
                            SUM(IF(status="cuti",1,0))as jml_cuti,
                            SUM(IF(presensi.jam_masuk > jam_kerja.jam_masuk,1,0)) as jml_terlambat')
                            ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
                            ->where('tanggal_presensi', $hari_ini)
                            ->first();
        $jumlah_karyawan = DB::table('karyawan')->count('nik');

        return view('dashboard.admin_dashboard', compact('rekap_presensi', 'jumlah_karyawan'));
    }
}
