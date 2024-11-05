<?php

namespace App\Http\Controllers;

use App\Models\PersetujuanSakitIzin;
use App\Models\presensi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanPresensiExport;
use App\Models\Karyawan;

class PresensiController extends Controller
{
    public function gethari($hari)
    {
        // $hari = date("D");

        switch ($hari) {
            case 'Sun':
                $hari_ini = "Minggu";
                break;

            case 'Mon':
                $hari_ini = "Senin";
                break;

            case 'Tue':
                $hari_ini = "Selasa";
                break;

            case 'Wed':
                $hari_ini = "Rabu";
                break;

            case 'Thu':
                $hari_ini = "Kamis";
                break;

            case 'Fri':
                $hari_ini = "Jumat";
                break;

            case 'Sat':
                $hari_ini = "Sabtu";
                break;

            default:
                $hari_ini = "Tidak diketahui";
                break;
        }
        return $hari_ini;
    }

    public function PresensiCreate()
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $kode_lokasi_penugasan = Auth::guard('karyawan')->user()->kode_lokasi_penugasan;
        $kode_cabang = Auth::guard('karyawan')->user()->kode_cabang;

        $hari_ini = date("Y-m-d");
        $nama_hari = $this->gethari(date('D', strtotime($hari_ini)));

        // Cek presensi hari sebelumnya untuk lintas hari
        $tgl_sebelumnya = date('Y-m-d', strtotime("-1 days", strtotime($hari_ini)));
        $presensi_sebelumnya = DB::table('presensi')
            ->join('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->where('tanggal_presensi', $tgl_sebelumnya)
            ->where('nik', $nik)
            ->first();

        $cek_lintas_hari = $presensi_sebelumnya != null ? $presensi_sebelumnya->lintas_hari : 0;

        if ($cek_lintas_hari == 1 && $presensi_sebelumnya->foto_keluar == null) {
            $hari_ini = $tgl_sebelumnya;
        }

        // Get presensi hari ini
        $presensi_hari_ini = DB::table('presensi')
            ->select('presensi.*', 'pengajuan_izin.keterangan')
            ->leftJoin('pengajuan_izin', 'presensi.kode_izin', '=', 'pengajuan_izin.kode_izin')
            ->where('presensi.nik', $nik)
            ->where('tanggal_presensi', $hari_ini)
            ->first();

        // Cek lembur yang disetujui
        $lembur_hari_ini = DB::table('lembur')
            ->where('nik', $nik)
            ->where('tanggal_presensi', $hari_ini)
            ->where('status', 'disetujui')
            ->first();

        // Data yang dibutuhkan view
        $data = [
            'nama_hari' => $nama_hari,
            'cek_masuk' => $presensi_hari_ini ? 1 : 0,
            'cek_keluar' => $presensi_hari_ini && $presensi_hari_ini->foto_keluar ? 1 : 0,
            'foto_keluar' => $presensi_hari_ini ? $presensi_hari_ini->foto_keluar : null,
            'lokasi_penugasan' => DB::table('lokasi_penugasan')->where('kode_lokasi_penugasan', $kode_lokasi_penugasan)->first(),
            'cek_izin' => $presensi_hari_ini,
            'hari_ini' => $hari_ini,
            'cek_lintas_hari' => $cek_lintas_hari,
            'cek_presensi_sebelumnya' => $presensi_sebelumnya,
            'lembur_hari_ini' => $lembur_hari_ini
        ];

        // Get jam kerja normal
        $jam_kerja_normal = DB::table('jam_kerja_karyawan')
            ->join('jam_kerja', 'jam_kerja_karyawan.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->where('nik', $nik)
            ->where('hari', $nama_hari)
            ->first();

        // if (!$jam_kerja_normal) {
        //     $jam_kerja_normal = DB::table('jam_kerja_dept_detail')
        //         ->join('jam_kerja_dept', 'jam_kerja_dept_detail.kode_jk_dept', '=', 'jam_kerja_dept.kode_jk_dept')
        //         ->join('jam_kerja', 'jam_kerja_dept_detail.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
        //         ->where('kode_lokasi_penugasan', $kode_lokasi_penugasan)
        //         ->where('kode_cabang', $kode_cabang)
        //         ->where('hari', $nama_hari)
        //         ->first();
        // }

        // Jika ada lembur, gunakan waktu lembur sebagai jam kerja
        if ($lembur_hari_ini) {
            // Kurangi 30 menit untuk awal_jam_masuk
            $awal_jam_masuk = date('H:i:s', strtotime('-30 minutes', strtotime($lembur_hari_ini->waktu_mulai)));

            // Tambah 30 menit untuk akhir_jam_masuk
            $akhir_jam_masuk = date('H:i:s', strtotime('+30 minutes', strtotime($lembur_hari_ini->waktu_mulai)));
            $data['jam_kerja_karyawan'] = (object) [
                'kode_jam_kerja' => 'LEMBUR',
                'nama_jam_kerja' => 'Lembur',
                'awal_jam_masuk' => $awal_jam_masuk,
                'jam_masuk' => $lembur_hari_ini->waktu_mulai,
                'akhir_jam_masuk' => $akhir_jam_masuk,
                'jam_pulang' => $lembur_hari_ini->waktu_selesai,
                'lembur' => 1
            ];
        } else {
            $data['jam_kerja_karyawan'] = $jam_kerja_normal;
        }

        // Return view yang sesuai
        if ($data['jam_kerja_karyawan'] == null && !$lembur_hari_ini) {
            return view('presensi.jadwal_kosong', $data);
        } elseif ($presensi_hari_ini && $presensi_hari_ini->status !== 'hadir') {
            $data['presensi_hari_ini'] = $presensi_hari_ini;
            return view('presensi.jadwal_izin', $data);
        } else {
            return view('presensi.create_presensi', $data);
        }
    }


    public function PresensiStore(Request $request)
    {
        // Inisiasi data
        $hari_ini = date("Y-m-d");
        $jam_sekarang = date("H:i:s");
        $nik = Auth::guard('karyawan')->user()->nik;

        // Cek lintas hari
        $tgl_sebelumnya = date('Y-m-d', strtotime("-1 days", strtotime($hari_ini)));
        $cek_presensi_sebelumnya = DB::table('presensi')
            ->join('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->where('tanggal_presensi', $tgl_sebelumnya)
            ->where('nik', $nik)
            ->first();

        $tgl_presensi = ($cek_presensi_sebelumnya && !$cek_presensi_sebelumnya->jam_keluar)
            ? $tgl_sebelumnya
            : $hari_ini;

        // Format jam untuk nama file
        $jam_save = str_replace(':', '', $jam_sekarang);

        // Get jam kerja dan lembur
        $nama_hari = $this->gethari(date('D', strtotime($tgl_presensi)));
        $jam_kerja_karyawan = $this->getJamKerja($nik, $nama_hari);
        $lembur_hari_ini = DB::table('lembur')
            ->where('nik', $nik)
            ->where('tanggal_presensi', $tgl_presensi)
            ->where('status', 'disetujui')
            ->first();

        // Jika tidak ada jam kerja normal tapi ada lembur
        if (!$jam_kerja_karyawan && $lembur_hari_ini) {
            $jam_kerja_karyawan = $this->createLemburJamKerja($lembur_hari_ini);
        }

        // Jika tidak ada jam kerja dan tidak ada lembur
        if (!$jam_kerja_karyawan && !$lembur_hari_ini) {
            return "error|Tidak ada jadwal kerja atau lembur untuk hari ini|sch";
        }

        // Validasi lokasi
        $lokasi_penugasan = DB::table('lokasi_penugasan')
            ->where('kode_lokasi_penugasan', Auth::guard('karyawan')->user()->kode_lokasi_penugasan)
            ->first();

        $radius = $this->validateLocation($request->lokasi, $lokasi_penugasan);
        if ($radius > $lokasi_penugasan->radius) {
            return "error|Maaf, anda diluar radius, jarak anda " . $radius . " meter dari kantor!|rad";
        }

        // Proses foto
        $foto = $this->processFoto($request->image, $nik, $tgl_presensi, $jam_save);

        // Cek presensi hari ini
        $cek = DB::table('presensi')
            ->where('tanggal_presensi', $tgl_presensi)
            ->where('nik', $nik)
            ->first();

        if ($cek) {
            // Proses Absen Pulang
            return $this->prosesAbsenPulang($cek, $jam_sekarang, $foto, $request->lokasi, $jam_kerja_karyawan, $lembur_hari_ini, $request);
        } else {
            // Proses Absen Masuk
            return $this->prosesAbsenMasuk($jam_sekarang, $foto, $request->lokasi, $jam_kerja_karyawan, $lembur_hari_ini, $tgl_presensi, $request);
        }
    }

    private function processFoto($image, $nik, $tgl_presensi, $jam_save)
    {
        $folderPath = "public/uploads/absensi/";

        // Tentukan jenis absen (masuk/keluar)
        $cek = DB::table('presensi')
            ->where('tanggal_presensi', $tgl_presensi)
            ->where('nik', $nik)
            ->count();
        $ket = ($cek > 0) ? "keluar" : "masuk";

        // Format nama file
        $formatName = $nik . "-" . $tgl_presensi . "-" . $jam_save . "-" . $ket;
        $fileName = $formatName . ".png";
        $file = $folderPath . $fileName;

        // Simpan foto
        Storage::put($file, base64_decode(explode(";base64,", $image)[1]));

        return $file;
    }

    private function createLemburJamKerja($lembur)
    {
        return (object) [
            'kode_jam_kerja' => 'LEMBUR',
            'nama_jam_kerja' => 'Lembur',
            'awal_jam_masuk' => date('H:i:s', strtotime('-30 minutes', strtotime($lembur->waktu_mulai))),
            'jam_masuk' => $lembur->waktu_mulai,
            'akhir_jam_masuk' => date('H:i:s', strtotime('+30 minutes', strtotime($lembur->waktu_mulai))),
            'jam_pulang' => $lembur->waktu_selesai,
            'lembur' => 1
        ];
    }

    private function prosesAbsenMasuk($jam_sekarang, $foto, $lokasi, $jam_kerja, $lembur, $tgl_presensi)
    {
        // Tentukan jam masuk yang digunakan
        $jam_masuk_minimal = $jam_kerja->awal_jam_masuk;
        $jam_masuk_maksimal = $jam_kerja->akhir_jam_masuk;

        if ($jam_sekarang < $jam_masuk_minimal) {
            return "error|Maaf Belum Waktunya Melakukan Presensi!|in";
        }

        if ($jam_sekarang > $jam_masuk_maksimal) {
            return "error|Maaf Sudah Melewati Waktu Melakukan Presensi!|in";
        }

        // Data dasar untuk presensi masuk
        $data_masuk = [
            'nik' => Auth::guard('karyawan')->user()->nik,
            'tanggal_presensi' => $tgl_presensi,
            'jam_masuk' => $jam_sekarang,
            'foto_masuk' => $foto,
            'lokasi_masuk' => $lokasi,
            'kode_jam_kerja' => $jam_kerja->kode_jam_kerja,
            'status' => 'hadir',
            'created_at' => Carbon::now()
        ];

        // Tambahkan data lembur jika ada
        if ($lembur && isset($jam_kerja->lembur) && $jam_kerja->lembur) {
            $data_masuk['lembur'] = 1;
            $data_masuk['mulai_lembur'] = $jam_kerja->jam_masuk;
            $data_masuk['selesai_lembur'] = $jam_kerja->jam_pulang;
        } else {
            $data_masuk['lembur'] = 0;
            $data_masuk['mulai_lembur'] = null;
            $data_masuk['selesai_lembur'] = null;
        }

        if (DB::table('presensi')->insert($data_masuk)) {
            return "success|Terima kasih, Selamat bekerja!|in";
        }

        return "error|Maaf Gagal absen, hubungi Tim IT|in";
    }

    private function prosesAbsenPulang($presensi, $jam_sekarang, $foto, $lokasi, $jam_kerja, $lembur)
    {
        // Tentukan jam pulang yang digunakan
        $jam_pulang = $jam_kerja->jam_pulang;

        // Jika ada lembur, sesuaikan jam pulang
        if ($lembur) {
            $waktu_mulai_lembur = Carbon::parse($lembur->waktu_mulai);
            $waktu_selesai_lembur = Carbon::parse($lembur->waktu_selesai);
            $jam_masuk_normal = Carbon::parse($jam_kerja->jam_masuk);
            $jam_pulang_normal = Carbon::parse($jam_kerja->jam_pulang);

            // Jika lembur setelah jam pulang normal
            if ($waktu_mulai_lembur >= $jam_pulang_normal) {
                $jam_pulang = $lembur->waktu_selesai;
            }
        }

        // Toleransi pulang lebih awal (30 menit)
        $toleransi_pulang = 30;
        $jam_pulang_minimal = Carbon::parse($jam_pulang)->subMinutes($toleransi_pulang)->format('H:i:s');

        if (Carbon::parse($jam_sekarang)->lt(Carbon::parse($jam_pulang_minimal))) {
            $sisa_waktu = Carbon::parse($jam_pulang_minimal)->diffInMinutes(Carbon::parse($jam_sekarang));
            return "error|Maaf Belum Waktunya Pulang! Sisa waktu: {$sisa_waktu} menit|out";
        }

        $data_pulang = [
            'jam_keluar' => $jam_sekarang,
            'foto_keluar' => $foto,
            'lokasi_keluar' => $lokasi,
            'updated_at' => Carbon::now()
        ];

        if (DB::table('presensi')->where('id', $presensi->id)->update($data_pulang)) {
            return "success|Terima kasih, Hati - hati dijalan!|out";
        }

        return "error|Maaf Gagal absen, hubungi Tim IT|out";
    }

    private function getJamKerja($nik, $nama_hari)
    {
        $jam_kerja = DB::table('jam_kerja_karyawan')
            ->join('jam_kerja', 'jam_kerja_karyawan.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->where('nik', $nik)
            ->where('hari', $nama_hari)
            ->first();

        if (!$jam_kerja) {
            $jam_kerja = DB::table('jam_kerja_dept_detail')
                ->join('jam_kerja_dept', 'jam_kerja_dept_detail.kode_jk_dept', '=', 'jam_kerja_dept.kode_jk_dept')
                ->join('jam_kerja', 'jam_kerja_dept_detail.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
                ->where('kode_departemen', Auth::guard('karyawan')->user()->kode_departemen)
                ->where('kode_cabang', Auth::guard('karyawan')->user()->kode_cabang)
                ->where('hari', $nama_hari)
                ->first();
        }

        return $jam_kerja;
    }

     //Menghitung Jarak
    private function validateLocation($lokasi_user, $lokasi_penugasan)
    {
        $lokasi = explode(",", $lokasi_user);
        $latitude_user = $lokasi[0];
        $longitude_user = $lokasi[1];

        $lokasi_penugasan = explode(",", $lokasi_penugasan->lokasi_penugasan);
        $latitude_lokasi_penugasan = $lokasi_penugasan[0];
        $longitude_lokasi_penugasan = $lokasi_penugasan[1];

        return $this->distance($latitude_lokasi_penugasan, $longitude_lokasi_penugasan, $latitude_user, $longitude_user)["meters"];
    }

    private function distance($latitude1, $longitude1, $latitude2, $longitude2)
    {
        $theta = $longitude1 - $longitude2;
        $miles = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) +
                (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $feet = $miles * 5280;
        $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;

        return compact('meters');
    }

    public function PresensiHistory()
    {
        // $monthNumber = date("m");

        $months = [
            '',
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember',
        ];

        // $monthName = $months[$monthNumber];
        return view('presensi.history', compact('months'));
    }

    public function GetHistory(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $nik = Auth::guard('karyawan')->user()->nik;

        // Tampilkan data presensi sesuai bulan dan tahun
        $history = DB::table('presensi')
            ->select('presensi.*',
                DB::raw('CASE WHEN presensi.kode_jam_kerja = "LEMBUR" THEN "Lembur" ELSE jam_kerja.nama_jam_kerja END as nama_jam_kerja'),
                DB::raw('CASE WHEN presensi.kode_jam_kerja = "LEMBUR" THEN lembur.waktu_mulai ELSE jam_kerja.jam_masuk END as jam_kerja_masuk'),
                DB::raw('CASE WHEN presensi.kode_jam_kerja = "LEMBUR" THEN lembur.waktu_selesai ELSE jam_kerja.jam_pulang END as jam_pulang'),
                'pengajuan_izin.keterangan',
                'lembur.waktu_mulai as mulai_lembur',
                'lembur.waktu_selesai as selesai_lembur')
            ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->leftJoin('pengajuan_izin', 'presensi.kode_izin', '=', 'pengajuan_izin.kode_izin')
            ->leftJoin('lembur', function($join) {
                $join->on('presensi.nik', '=', 'lembur.nik')
                    ->on('presensi.tanggal_presensi', '=', 'lembur.tanggal_presensi')
                    ->where('lembur.status', '=', 'disetujui');
            })
            ->where('presensi.nik', $nik)
            ->whereRaw('MONTH(presensi.tanggal_presensi) = ?', [$bulan])
            ->whereRaw('YEAR(presensi.tanggal_presensi) = ?', [$tahun])
            ->orderBy('presensi.tanggal_presensi', 'desc')
            ->get();

        return view('presensi.gethistory', compact('history'));
    }

    // public function SakitIzin()
    // {
    //     $nik = Auth::guard('karyawan')->user()->nik;
    //     $dataIzin = DB::table('pengajuan_sakit_izin')
    //         ->where('nik', $nik)
    //         ->get();
    //     return view('presensi.sakit_izin', compact('dataIzin'));
    // }
    // public function CreateSakitIzin()
    // {

    //     return view('presensi.create_sakit_izin');
    // }
    // public function StoreSakitIzin(Request $request)
    // {
    //     $nik = Auth::guard('karyawan')->user()->nik;
    //     $tanggal_izin = $request->tanggal_izin;
    //     $status = $request->status;
    //     $keterangan = $request->keterangan;

    //     $data = [
    //         'nik' => $nik,
    //         'tanggal_izin' => $tanggal_izin,
    //         'status' => $status,
    //         'keterangan' => $keterangan,
    //         'created_at' => Carbon::now()
    //     ];

    //     $save = DB::table('pengajuan_sakit_izin')->insert($data);

    //     if($save){
    //         return redirect('/presensi/sakit-izin')->with(['success' => 'Data berhasil disimpan!']);
    //     } else {
    //         return redirect()->back()->with(['error' => 'Data gagal disimpan!']);
    //     }
    // }

    // Admin Monitoring Presensi
    public function MonitoringPresensi()
    {
        return view('presensi.monitoring_presensi');
    }

    public function MonitoringGetPresensi(Request $request)
    {
        $tanggal_presensi = $request->tanggal_presensi;
        $presensi = DB::table('presensi')
            ->select('presensi.*',
                'karyawan.nama_lengkap',
                'karyawan.kode_jabatan',
                'departemen.nama_departemen',
                DB::raw('CASE WHEN presensi.kode_jam_kerja = "LEMBUR" THEN lembur.waktu_mulai ELSE jam_kerja.jam_masuk END as jam_masuk_kerja'),
                DB::raw('CASE WHEN presensi.kode_jam_kerja = "LEMBUR" THEN lembur.waktu_selesai ELSE jam_kerja.jam_pulang END as jam_pulang_kerja'),
                DB::raw('CASE WHEN presensi.kode_jam_kerja = "LEMBUR" THEN "Lembur" ELSE jam_kerja.nama_jam_kerja END as nama_jam_kerja'),
                'pengajuan_izin.keterangan as keterangan_izin',
                'lembur.waktu_mulai as mulai_lembur',
                'lembur.waktu_selesai as selesai_lembur')
            ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->leftJoin('pengajuan_izin', 'presensi.kode_izin', '=', 'pengajuan_izin.kode_izin')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->join('departemen', 'karyawan.kode_departemen', '=', 'departemen.kode_departemen')
            ->leftJoin('lembur', function($join) use ($tanggal_presensi) {
                $join->on('presensi.nik', '=', 'lembur.nik')
                    ->where('lembur.tanggal_presensi', '=', DB::raw('presensi.tanggal_presensi'))
                    ->where('lembur.status', '=', 'disetujui');
            })
            ->where('presensi.tanggal_presensi', $tanggal_presensi)
            ->get();

        return view('presensi.monitoring_getpresensi', compact('presensi'));
    }

    public function TampilkanPeta(Request $request)
    {
        $id = $request->id;
        $presensi = DB::table('presensi')
        ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
        ->where('id', $id)
        ->first();
        return view('presensi.monitoring_showmap', compact('presensi'));
    }

    public function LaporanPresensi()
    {
        $months = [
            '',
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember',
        ];

        $karyawan = DB::table('karyawan')->orderBy('nama_lengkap')->get();
        return view('presensi.laporan_presensi', compact('months', 'karyawan'));
    }

    public function LaporanPrint(Request $request)
    {
        $nik = $request->nik;
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $months = [
            '',
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember',
        ];

        $karyawan = DB::table('karyawan')
                    ->join('departemen', 'karyawan.kode_departemen', '=', 'departemen.kode_departemen')
                    ->join('jabatan', 'karyawan.kode_jabatan', '=', 'jabatan.kode_jabatan')
                    ->where('nik', $nik)
                    ->first();

        $presensi = DB::table('presensi')
                    ->select('presensi.*',
                        DB::raw('CASE
                            WHEN presensi.kode_jam_kerja = "LEMBUR" THEN lembur.waktu_mulai
                            ELSE jam_kerja.jam_masuk
                        END as jam_masuk_kerja'),
                        DB::raw('CASE
                            WHEN presensi.kode_jam_kerja = "LEMBUR" THEN lembur.waktu_selesai
                            ELSE jam_kerja.jam_pulang
                        END as jam_pulang_kerja'),
                        DB::raw('CASE
                            WHEN presensi.kode_jam_kerja = "LEMBUR" THEN "Lembur"
                            ELSE jam_kerja.nama_jam_kerja
                        END as nama_jam_kerja'),
                        'pengajuan_izin.keterangan',
                        'jam_kerja.lintas_hari',
                        'lembur.waktu_mulai as mulai_lembur',
                        'lembur.waktu_selesai as selesai_lembur')
                    ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
                    ->leftJoin('pengajuan_izin', 'presensi.kode_izin', '=', 'pengajuan_izin.kode_izin')
                    ->leftJoin('lembur', function($join) {
                        $join->on('presensi.nik', '=', 'lembur.nik')
                            ->whereRaw('DATE(lembur.tanggal_presensi) = DATE(presensi.tanggal_presensi)')
                            ->where('lembur.status', '=', 'disetujui');
                    })
                    ->where('presensi.nik', $nik)
                    ->whereRaw('MONTH(presensi.tanggal_presensi)=?', [$bulan])
                    ->whereRaw('YEAR(presensi.tanggal_presensi)=?', [$tahun])
                    ->orderBy('presensi.tanggal_presensi')
                    ->get();

        // Hitung total hari presensi
        $total_hari = $presensi->groupBy('tanggal_presensi')->count();

        // Hitung total jam dan menit lembur
        $total_jam_lembur = 0;
        $total_menit_lembur = 0;

        foreach ($presensi as $p) {
            if ($p->mulai_lembur && $p->selesai_lembur) {
                $mulai = Carbon::parse($p->mulai_lembur);
                $selesai = Carbon::parse($p->selesai_lembur);

                // Hitung selisih dalam menit
                $selisih_menit = $selesai->diffInMinutes($mulai);

                // Konversi ke jam dan menit
                $total_jam_lembur += floor($selisih_menit / 60);
                $total_menit_lembur += $selisih_menit % 60;
            }
        }

        // Normalisasi jika menit lebih dari 60
        if ($total_menit_lembur >= 60) {
            $jam_tambahan = floor($total_menit_lembur / 60);
            $total_jam_lembur += $jam_tambahan;
            $total_menit_lembur = $total_menit_lembur % 60;
        }

        if(isset($_POST['export_excel'])) {
            $time = date("H:i:s");
            header("Content-type: application/vnd-ms-excel");
            header("Content-Disposition: attachment; filename=Laporan_Presensi_$time.xls");
        }

        return view('presensi.laporan_print', compact(
            'bulan',
            'tahun',
            'months',
            'karyawan',
            'presensi',
            'total_hari',
            'total_jam_lembur',
            'total_menit_lembur'
        ));
    }

    public function RekapPresensi()
    {
        $months = [
            '',
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember',
        ];

        $kantor_cabang = DB::table('kantor_cabang')->get();
        // dd($departemen);

        return view('presensi.rekap_presensi', compact('months', 'kantor_cabang'));
    }

    public function RekapPrint(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $kode_cabang = $request->kode_cabang;
        // dd($kode_cabang);
        $start = $tahun . "-" . $bulan . "-01";
        $dari = date("Y-m-d", strtotime($start));
        // dd($dari);
        $sampai = date("Y-m-t", strtotime($dari));

        $months = [
            '',
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember',
        ];

        $select_date = "";
        $field_date = "";
        $tgl = 1;
        while(strtotime($dari) <= strtotime($sampai)){
            $range_tanggal[] = $dari;

            $select_date .= "MAX(IF(tanggal_presensi = '$dari', CONCAT(
                                IFNULL(presensi.jam_masuk, 'NA'), '|',
                                IFNULL(presensi.jam_keluar, 'NA'), '|',
                                IFNULL(presensi.status, 'NA') , '|',
                                IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
                                IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
                                IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
                                IFNULL(presensi.kode_izin, 'NA') , '|',
                                IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
                            ), NULL)) AS tgl_".$tgl . ",";
            $field_date .= "tgl_" . $tgl . ",";
            $tgl++;

            $dari = date("Y-m-d", strtotime("+1 day", strtotime($dari)));
        }

        // dd($select_date);
        $jml_hari = count($range_tanggal);
        $last_range = $jml_hari - 1;
        $sampai = $range_tanggal[$last_range];

        if($jml_hari == 30){
            array_push($range_tanggal, NULL);
        } elseif ($jml_hari == 29) {
            array_push($range_tanggal, NULL, NULL);
        } elseif ($jml_hari == 28) {
            array_push($range_tanggal, NULL, NULL, NULL);
        }

        // dd($range_tanggal);

        $query = Karyawan::query();
        $query->selectRaw("
                            $field_date
                            karyawan.nik,
                            karyawan.nama_lengkap,
                            karyawan.kode_jabatan,
                            karyawan.kode_cabang
                            "
                        );
        $query->leftJoin(
            DB::raw("(
                    SELECT
                        $select_date
                        presensi.nik
                    FROM
                        presensi
                    LEFT JOIN
                        jam_kerja ON presensi.kode_jam_kerja = jam_kerja.kode_jam_kerja
                    LEFT JOIN
                        pengajuan_izin ON presensi.kode_izin = pengajuan_izin.kode_izin
                    WHERE
                        presensi.tanggal_presensi BETWEEN  '$range_tanggal[0]' AND '$sampai'
                    GROUP BY
                        presensi.nik
                    ) presensi"),
            function ($join) {
                    $join->on('karyawan.nik', '=', 'presensi.nik');
            }
        );
        if (!empty($kode_cabang)) {
            $query->where('kode_cabang', $kode_cabang);
        }

        $query->orderBy('nama_lengkap');
        $rekap = $query->get();

        // dd($rekap);

        if(isset($_POST['export_excel'])){
            $time = date("H:i:s");
            // fungsi header dengan mengirimkan raw data excel
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file export "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Laporan_Presensi_$time.xls");
        }

        return view('presensi.rekap_print', compact('bulan', 'tahun', 'months', 'rekap', 'range_tanggal', 'jml_hari'));
    }

}
