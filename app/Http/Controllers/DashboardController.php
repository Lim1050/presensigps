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
                                ->where('nik', $nik)
                                ->whereRaw('MONTH(tanggal_presensi)="' . $bulan_ini . '"')
                                ->whereRaw('YEAR(tanggal_presensi)="' . $tahun_ini . '"')
                                ->orderBy('tanggal_presensi')
                                ->get();

        $rekap_presensi = DB::table('presensi')
                            ->selectRaw('COUNT(nik) as jml_hadir, SUM(IF(jam_masuk > "09:00",1,0)) as jml_terlambat')
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

        return view('dashboard.dashboard', compact('rekap_presensi', 'monthName', 'tahun_ini', 'nama','jabatan','presensi_hari_ini', 'history_bulan_ini', 'leaderboards'));
    }
}
