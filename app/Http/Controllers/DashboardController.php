<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Lembur;
use App\Models\presensi;
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

        // Tambahkan join dengan tabel lembur
        $presensi_hari_ini = DB::table('presensi')
            ->select('presensi.*', 'pengajuan_izin.keterangan', 'lembur.waktu_mulai as mulai_lembur', 'lembur.waktu_selesai as selesai_lembur')
            ->leftJoin('pengajuan_izin', 'presensi.kode_izin', '=', 'pengajuan_izin.kode_izin')
            ->leftJoin('lembur', function($join) use ($hari_ini) {
                $join->on('presensi.nik', '=', 'lembur.nik')
                    ->where('lembur.tanggal_presensi', '=', DB::raw('presensi.tanggal_presensi'))
                    ->where('lembur.status', '=', 'disetujui');
            })
            ->where('presensi.nik', $nik)
            ->where('presensi.tanggal_presensi', $hari_ini)
            ->first();

        // Modifikasi history_bulan_ini untuk mencakup data lembur
        // $history_bulan_ini_xx = DB::table('presensi')
        //     ->select('presensi.*',
        //             DB::raw('CASE WHEN presensi.kode_jam_kerja = "LEMBUR" THEN "Lembur" ELSE jam_kerja.nama_jam_kerja END as nama_jam_kerja'),
        //             DB::raw('CASE WHEN presensi.kode_jam_kerja = "LEMBUR" THEN lembur.waktu_mulai ELSE jam_kerja.jam_masuk END as jam_kerja_masuk'),
        //             DB::raw('CASE WHEN presensi.kode_jam_kerja = "LEMBUR" THEN lembur.waktu_selesai ELSE jam_kerja.jam_pulang END as jam_pulang'),
        //             'pengajuan_izin.keterangan',
        //             'lembur.waktu_mulai as mulai_lembur', 'lembur.waktu_selesai as selesai_lembur')
        //     ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
        //     ->leftJoin('pengajuan_izin', 'presensi.kode_izin', '=', 'pengajuan_izin.kode_izin')
        //     ->leftJoin('lembur', function($join) {
        //         $join->on('presensi.nik', '=', 'lembur.nik')
        //             ->on('presensi.tanggal_presensi', '=', 'lembur.tanggal_presensi')
        //             ->where('lembur.status', '=', 'disetujui');
        //     })
        //     ->where('presensi.nik', $nik)
        //     ->whereRaw('MONTH(presensi.tanggal_presensi) = ?', [$bulan_ini])
        //     ->whereRaw('YEAR(presensi.tanggal_presensi) = ?', [$tahun_ini])
        //     ->orderBy('presensi.tanggal_presensi', 'desc')
        //     ->get();

        $history_bulan_ini = Presensi::with(['jamKerja', 'pengajuanIzin', 'lembur'])
            ->where('nik', $nik)
            ->whereMonth('tanggal_presensi', $bulan_ini)
            ->whereYear('tanggal_presensi', $tahun_ini)
            ->orderBy('tanggal_presensi', 'desc')
            ->get()
            ->map(function ($presensi) {
                return [
                    ...$presensi->toArray(),
                    'nama_jam_kerja' => $presensi->nama_jam_kerja,
                    'jam_kerja_masuk' => $presensi->jam_kerja_masuk,
                    'jam_pulang' => $presensi->jam_pulang,
                    'keterangan' => optional($presensi->pengajuanIzin)->keterangan,
                    'waktu_mulai' => optional($presensi->lembur)->waktu_mulai,
                    'waktu_selesai' => optional($presensi->lembur)->waktu_selesai,
                ];
        });

        // dd($history_bulan_ini);

        // Modifikasi rekap_presensi untuk menghitung keterlambatan dengan mempertimbangkan lembur
        $rekap_presensi = DB::table('presensi')
            ->selectRaw('
                SUM(IF(status="hadir",1,0)) as jml_hadir,
                SUM(IF(status="izin",1,0)) as jml_izin,
                SUM(IF(status="sakit",1,0)) as jml_sakit,
                SUM(IF(status="cuti",1,0)) as jml_cuti,
                SUM(IF(
                    CASE
                        WHEN presensi.lembur = 1 THEN presensi.jam_masuk > DATE_ADD(presensi.mulai_lembur, INTERVAL 30 MINUTE)
                        ELSE presensi.jam_masuk > jam_kerja.jam_masuk
                    END,
                    1,0)) as jml_terlambat')
            ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->where('presensi.nik', $nik)
            ->whereRaw('MONTH(tanggal_presensi)="' . $bulan_ini . '"')
            ->whereRaw('YEAR(tanggal_presensi)="' . $tahun_ini . '"')
            ->first();

        // Modifikasi leaderboards untuk mempertimbangkan lembur
        $leaderboards = DB::table('presensi')
            ->select('presensi.*', 'karyawan.*',
                DB::raw('CASE WHEN presensi.kode_jam_kerja = "LEMBUR" THEN lembur.waktu_mulai ELSE jam_kerja.jam_masuk END as jam_kerja_masuk'),
                'lembur.waktu_mulai as mulai_lembur', 'lembur.waktu_selesai as selesai_lembur')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->leftJoin('lembur', function($join) use ($hari_ini) {
                $join->on('presensi.nik', '=', 'lembur.nik')
                    ->where('lembur.tanggal_presensi', '=', DB::raw('presensi.tanggal_presensi'))
                    ->where('lembur.status', '=', 'disetujui');
            })
            ->where('presensi.tanggal_presensi', $hari_ini)
            ->orderBy('presensi.jam_masuk', 'ASC')
            ->get();
        // dd($history_bulan_ini);
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

        $monthName = $months[$bulan_ini];

        // Ambil daftar lembur yang disetujui
        $daftar_lembur = Lembur::where('nik', $nik)
            // ->where('status', 'disetujui')
            ->whereYear('tanggal_presensi', operator: $tahun_ini)
            ->whereMonth('tanggal_presensi', $bulan_ini)
            ->orderBy('tanggal_presensi', 'desc')
            ->get();

        return view('dashboard.dashboard', compact(
            'rekap_presensi',
            'monthName',
            'tahun_ini',
            'nama',
            'jabatan',
            'presensi_hari_ini',
            'history_bulan_ini',
            'leaderboards',
            'daftar_lembur'
        ));
    }

    // public function AdminDashboard()
    // {
    //     $hari_ini = date("Y-m-d");
    //     $bulan_ini = date("m");
    //     $tahun_ini = date("Y");
    //     $rekap_presensi = DB::table('presensi')
    //                         ->selectRaw('
    //                         SUM(IF(status="hadir",1,0))as jml_hadir,
    //                         SUM(IF(status="izin",1,0))as jml_izin,
    //                         SUM(IF(status="sakit",1,0))as jml_sakit,
    //                         SUM(IF(status="cuti",1,0))as jml_cuti,
    //                         SUM(IF(presensi.jam_masuk > jam_kerja.jam_masuk,1,0)) as jml_terlambat')
    //                         ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
    //                         ->where('tanggal_presensi', $hari_ini)
    //                         ->first();
    //     $jumlah_karyawan = DB::table('karyawan')->count('nik');

    //     return view('dashboard.admin_dashboard', compact('rekap_presensi', 'jumlah_karyawan'));
    // }

    public function AdminDashboard()
    {
        $hari_ini = date("Y-m-d");

        // Mendapatkan user yang sedang login
        $user = auth()->user();

        // Query dasar untuk rekap presensi dengan join ke tabel karyawan
        $rekap_presensi_query = DB::table('presensi')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->selectRaw('
                SUM(IF(status="hadir", 1, 0)) as jml_hadir,
                SUM(IF(status="izin", 1, 0)) as jml_izin,
                SUM(IF(status="sakit", 1, 0)) as jml_sakit,
                SUM(IF(status="cuti", 1, 0)) as jml_cuti,
                SUM(IF(presensi.jam_masuk > jam_kerja.jam_masuk, 1, 0)) as jml_terlambat
            ')
            ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->where('tanggal_presensi', $hari_ini);

        // Query dasar untuk jumlah karyawan
        $jumlah_karyawan_query = DB::table('karyawan');

        // Pengecekan berdasarkan peran
        if ($user->role === 'admin-cabang') {
            // Admin cabang hanya melihat data cabangnya sendiri
            $rekap_presensi = $rekap_presensi_query
                ->where('karyawan.kode_cabang', $user->kode_cabang)
                ->first();

            $jumlah_karyawan = $jumlah_karyawan_query
                ->where('kode_cabang', $user->kode_cabang)
                ->count('nik');

            $cabang = Cabang::where('kode_cabang', $user->kode_cabang)->first('nama_cabang');
            // dd($nama_cabang);
        } else {
            // Pengguna selain admin cabang (super admin, dll) melihat semua data
            $cabang = null;
            $rekap_presensi = $rekap_presensi_query->first();
            $jumlah_karyawan = $jumlah_karyawan_query->count('nik');
        }

        return view('dashboard.admin_dashboard', compact('rekap_presensi', 'jumlah_karyawan', 'cabang'));
    }
}
