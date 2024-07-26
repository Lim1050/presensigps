<?php

namespace App\Http\Controllers;

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
        $jabatan = Auth::guard('karyawan')->user()->jabatan;
        $presensi_hari_ini = DB::table('presensi')
                                ->where('nik', $nik)
                                ->where('tanggal_presensi', $hari_ini)
                                ->first();

        $history_bulan_ini = DB::table('presensi')
                                ->select('presensi.*', 'jam_kerja.nama_jam_kerja', 'jam_kerja.jam_masuk as jam_kerja_masuk', 'jam_kerja.jam_pulang')
                                ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
                                ->where('presensi.nik', $nik)
                                ->whereRaw('MONTH(presensi.tanggal_presensi) = ?', [$bulan_ini])
                                ->whereRaw('YEAR(presensi.tanggal_presensi) = ?', [$tahun_ini])
                                ->orderBy('presensi.tanggal_presensi')
                                ->get();

        $rekap_presensi = DB::table('presensi')
                            ->selectRaw('COUNT(nik) as jml_hadir, SUM(IF(presensi.jam_masuk > jam_kerja.jam_masuk,1,0)) as jml_terlambat')
                            ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
                            ->where('nik', $nik)
                            ->whereRaw('MONTH(tanggal_presensi)="' . $bulan_ini . '"')
                            ->whereRaw('YEAR(tanggal_presensi)="' . $tahun_ini . '"')
                            ->first();

        $leaderboards = DB::table('presensi')
                            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
                            ->where('tanggal_presensi', $hari_ini)
                            ->orderBy('jam_masuk', 'ASC')
                            ->get();

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

        // rekap izin sakit
        $rekap_sakit_izin = DB::table('pengajuan_izin')
            ->selectRaw('SUM(IF(status="izin",1,0)) as jumlah_izin, SUM(IF(status="sakit",1,0)) as jumlah_sakit, SUM(IF(status="cuti",1,0)) as jumlah_cuti')
            ->where('nik', $nik)
            ->whereRaw('MONTH(tanggal_izin_dari)="' . $bulan_ini . '"')
            ->whereRaw('YEAR(tanggal_izin_dari)="' . $tahun_ini . '"')
            ->where('status_approved', 1)
            ->first();

        return view('dashboard.dashboard', compact('rekap_presensi', 'monthName', 'tahun_ini', 'nama','jabatan','presensi_hari_ini', 'history_bulan_ini', 'leaderboards', 'rekap_sakit_izin'));
    }

    public function AdminDashboard()
    {
        $hari_ini = date("Y-m-d");
        $rekap_presensi = DB::table('presensi')
                            ->selectRaw('COUNT(nik) as jumlah_hadir, SUM(IF(jam_masuk > "09:00",1,0)) as jumlah_terlambat')
                            ->where('tanggal_presensi', $hari_ini)
                            ->first();
        $rekap_sakit_izin = DB::table('pengajuan_izin')
                            ->selectRaw('SUM(IF(status = "sakit",1,0)) as jumlah_sakit, SUM(IF(status = "izin",1,0)) as jumlah_izin, SUM(IF(status = "cuti",1,0)) as jumlah_cuti')
                            ->where('tanggal_izin_dari', $hari_ini)
                            ->first();
        $jumlah_karyawan = DB::table('karyawan')->count('nik');

        return view('dashboard.admin_dashboard', compact('rekap_presensi', 'rekap_sakit_izin', 'jumlah_karyawan'));
    }
}
