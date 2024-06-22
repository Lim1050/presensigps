<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
                                ->whereRaw('MONTH(tanggal_presensi)="' . $bulan_ini . '"')
                                ->whereRaw('YEAR(tanggal_presensi)="' . $tahun_ini . '"')
                                ->orderBy('tanggal_presensi')
                                ->get();

        return view('dashboard.dashboard', compact('nama','jabatan','presensi_hari_ini', 'history_bulan_ini'));
    }
}
