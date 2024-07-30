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
    public function gethari()
    {
        $hari = date("D");

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
        // cek apakah sudah absen
        $hari_ini = date("Y-m-d");
        $nama_hari = $this->gethari();

        $nik = Auth::guard('karyawan')->user()->nik;
        $kode_departemen = Auth::guard('karyawan')->user()->kode_departemen;

        $cek_masuk = DB::table('presensi')
                        ->where('tanggal_presensi', $hari_ini)
                        ->where('nik', $nik)
                        ->count();

        $cek_keluar = DB::table('presensi')
                        ->where('tanggal_presensi', $hari_ini)
                        ->where('nik', $nik)
                        ->whereNotNull('foto_keluar')
                        ->count();

        $foto_keluar = DB::table('presensi')
                        ->where('nik', $nik)
                        ->where('tanggal_presensi', $hari_ini)
                        ->whereNotNull('foto_keluar')
                        ->first();

        $kode_cabang = Auth::guard('karyawan')->user()->kode_cabang;

        $lokasi_kantor = DB::table('kantor_cabang')
                            ->where('kode_cabang', $kode_cabang)
                            ->first();

        $jam_kerja_karyawan = DB::table('jam_kerja_karyawan')
                                ->join('jam_kerja', 'jam_kerja_karyawan.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
                                ->where('nik', $nik)
                                ->where('hari', $nama_hari)
                                ->first();

        if ($jam_kerja_karyawan == null) {
            $jam_kerja_karyawan = DB::table('jam_kerja_dept_detail')
                                ->join('jam_kerja_dept', 'jam_kerja_dept_detail.kode_jk_dept', '=', 'jam_kerja_dept.kode_jk_dept')
                                ->join('jam_kerja', 'jam_kerja_dept_detail.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
                                ->where('kode_departemen', $kode_departemen)
                                ->where('kode_cabang', $kode_cabang)
                                ->where('hari', $nama_hari)
                                ->first();
            // dd($jam_kerja_karyawan);
        }

        if($jam_kerja_karyawan == null){
            return view('presensi.jadwal_kosong', compact('nama_hari','cek_masuk', 'cek_keluar', 'foto_keluar', 'lokasi_kantor', 'jam_kerja_karyawan'));
        }else{
            return view('presensi.create_presensi', compact('nama_hari','cek_masuk', 'cek_keluar', 'foto_keluar', 'lokasi_kantor', 'jam_kerja_karyawan'));
        }
        // dd($jam_kerja_karyawan);
    }

    public function PresensiStore(Request $request)
    {
        // inisiasi data
        $nik = Auth::guard('karyawan')->user()->nik;
        $kode_departemen = Auth::guard('karyawan')->user()->kode_departemen;
        $kode_cabang = Auth::guard('karyawan')->user()->kode_cabang;
        $tgl_presensi = date("Y-m-d");
        $jam_presensi = date("H:i:s");
        $jam = explode(":", $jam_presensi);
        $jam_save = $jam[0] . $jam[1] . $jam[2];

        // cek jam kerja karyawan
        $nama_hari = $this->gethari();
        $jam_kerja_karyawan = DB::table('jam_kerja_karyawan')
                                ->join('jam_kerja', 'jam_kerja_karyawan.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
                                ->where('nik', $nik)
                                ->where('hari', $nama_hari)
                                ->first();

            if ($jam_kerja_karyawan == null) {
                $jam_kerja_karyawan = DB::table('jam_kerja_dept_detail')
                                        ->join('jam_kerja_dept', 'jam_kerja_dept_detail.kode_jk_dept', '=', 'jam_kerja_dept.kode_jk_dept')
                                        ->join('jam_kerja', 'jam_kerja_dept_detail.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
                                        ->where('kode_departemen', $kode_departemen)
                                        ->where('kode_cabang', $kode_cabang)
                                        ->where('hari', $nama_hari)
                                        ->first();
            }
        // nanti disesuaikan dengan lokasi kantor
        $kode_cabang = Auth::guard('karyawan')->user()->kode_cabang;
        $lokasi_kantor = DB::table('kantor_cabang')->where('kode_cabang', $kode_cabang)->first();
        $kantor = explode(",", $lokasi_kantor->lokasi_kantor );
        $latitude_kantor = $kantor[0];
        $longitude_kantor = $kantor[1];

        // lokasi user
        $lokasi = $request->lokasi;
        $lokasi_user = explode(",", $lokasi);
        $latitude_user = $lokasi_user[0];
        $longitude_user = $lokasi_user[1];

        // nanti disesuaikan dengan lokasi kantor untuk latitude1 dan longitude1
        $jarak = $this->distance($latitude_kantor, $longitude_kantor, $latitude_user, $longitude_user);
        $radius = round($jarak["meters"]);

        $cek = DB::table('presensi')->where('tanggal_presensi', $tgl_presensi)->where('nik', $nik)->count();
        if ($cek > 0){
            $ket = "keluar";
        } else {
            $ket = "masuk";
        }
        $image = $request->image;
        $folderPath = "public/uploads/absensi/";

        // setting simpan gambar
        $formatName = $nik . "-" . $tgl_presensi . "-" . $jam_save . "-" . $ket;
        $image_parts = explode(";base64", $image);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = $formatName . ".png";
        $file = $folderPath . $fileName;

        // save data

        // cek jarak
        if ($radius > $lokasi_kantor->radius) {
            echo "error|Maaf, anda diluar radius, jarak anda " . $radius . " meter dari kantor!|rad";
        } else {
            if ($cek > 0) {
                if ($jam_presensi < $jam_kerja_karyawan->jam_pulang) {
                    echo "error|Maaf Belum Waktunya Pulang!|out";
                }
                $data_pulang = [
                    'jam_keluar' => $jam_presensi,
                    'foto_keluar' => $file,
                    'lokasi_keluar' => $lokasi,
                    'updated_at' => Carbon::now()
                ];
                $update = DB::table('presensi')->where('nik', $nik)->where('tanggal_presensi', $tgl_presensi)->update($data_pulang);
                if ($update) {
                    echo "success|Terima kasih, Hati - hati dijalan!|out";
                    Storage::put($file, $image_base64);
                } else {
                    echo "error|Maaf Gagal absen, hubungi Tim IT|out";
                }
            } else {
                if ($jam_presensi < $jam_kerja_karyawan->awal_jam_masuk) {
                    echo "error|Maaf Belum Waktunya Melakukan Presensi!|in";
                } else if ($jam_presensi > $jam_kerja_karyawan->akhir_jam_masuk){
                    echo "error|Maaf Sudah Melewati Waktu Melakukan Presensi!|in";
                } else {
                    $data_masuk = [
                        'nik' => $nik,
                        'tanggal_presensi' => $tgl_presensi,
                        'jam_masuk' => $jam_presensi,
                        'foto_masuk' => $file,
                        'lokasi_masuk' => $lokasi,
                        'kode_jam_kerja' => $jam_kerja_karyawan->kode_jam_kerja,
                        'status' => 'hadir',
                        'created_at' => Carbon::now()
                    ];
                    $save = DB::table('presensi')->insert($data_masuk);
                    if ($save) {
                        echo "success|Terima kasih, Selamat bekerja!|in";
                        Storage::put($file, $image_base64);
                    } else {
                        echo "error|Maaf Gagal absen, hubungi Tim IT|in";
                    }
                }
            }
        }
    }

     //Menghitung Jarak
    function distance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
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

        // tampilkan data presensi sesuai bulan dan tahun
        $history = DB::table('presensi')
            ->whereRaw('MONTH(tanggal_presensi)="' . $bulan . '"')
            ->whereRaw('YEAR(tanggal_presensi)="' . $tahun . '"')
            ->where('nik', $nik)
            ->orderBy('tanggal_presensi')
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
            ->select('presensi.*', 'nama_lengkap', 'jabatan', 'nama_departemen', 'jam_kerja.jam_masuk as jam_masuk_kerja', 'jam_kerja.jam_pulang as jam_pulang_kerja', 'nama_jam_kerja', 'pengajuan_izin.keterangan as keterangan_izin')
            ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->leftJoin('pengajuan_izin', 'presensi.kode_izin', '=', 'pengajuan_izin.kode_izin')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->join('departemen', 'karyawan.kode_departemen', '=', 'departemen.kode_departemen')
            ->where('tanggal_presensi', $tanggal_presensi)
            ->get();
        // dd($presensi);

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
                    ->where('nik', $nik)
                    ->first();

        $presensi = DB::table('presensi')
                    ->select('presensi.*', 'jam_kerja.jam_masuk as jam_masuk_kerja', 'jam_kerja.jam_pulang as jam_pulang_kerja', 'nama_jam_kerja')
                    ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
                    ->where('nik', $nik)
                    ->whereRaw('MONTH(tanggal_presensi)="' . $bulan . '"')
                    ->whereRaw('YEAR(tanggal_presensi)="' . $tahun . '"')
                    ->orderBy('tanggal_presensi')
                    ->get();
        // dd($presensi);

        // Hitung total hari presensi
        $total_hari = $presensi->groupBy('tanggal_presensi')->count();

        // Asumsikan gaji harian adalah 100000
        $gaji_harian = 100000;
        $total_gaji = $total_hari * $gaji_harian;
        // dd($total_gaji);

        // Simpan atau perbarui gaji
        // $salary = Salary::updateOrCreate(
        //     ['nik' => $nik],
        //     ['total_days_worked' => $totalDaysWorked, 'salary_amount' => $salaryAmount]
        // );

        // Export data ke Excel
        // return Excel::download(new LaporanPresensiExport($presensi, $karyawan, $total_gaji), 'laporan_presensi_'.$karyawan->nik.'_'.$karyawan->nama_lengkap.'_'.$bulan.'_'.$tahun.'.xlsx');

        if(isset($_POST['export_excel'])){
            $time = date("H:i:s");
            // fungsi header dengan mengirimkan raw data excel
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file export "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Laporan_Presensi_$time.xls");
        }

        return view('presensi.laporan_print', compact('bulan', 'tahun', 'months', 'karyawan', 'presensi', 'total_hari', 'gaji_harian', 'total_gaji'));
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

        return view('presensi.rekap_presensi', compact('months'));
    }

    public function RekapPrint(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
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

        while(strtotime($dari) <= strtotime($sampai)){
            $range_tanggal[] = $dari;
            $dari = date("Y-m-d", strtotime("+1 day", strtotime($dari)));
        }
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
        $query->selectRaw("karyawan.nik,
                            karyawan.nama_lengkap,
                            karyawan.jabatan,
                            presensi.tgl_1,
                            presensi.tgl_2,
                            presensi.tgl_3,
                            presensi.tgl_4,
                            presensi.tgl_5,
                            presensi.tgl_6,
                            presensi.tgl_7,
                            presensi.tgl_8,
                            presensi.tgl_9,
                            presensi.tgl_10,
                            presensi.tgl_11,
                            presensi.tgl_12,
                            presensi.tgl_13,
                            presensi.tgl_14,
                            presensi.tgl_15,
                            presensi.tgl_16,
                            presensi.tgl_17,
                            presensi.tgl_18,
                            presensi.tgl_19,
                            presensi.tgl_20,
                            presensi.tgl_21,
                            presensi.tgl_22,
                            presensi.tgl_23,
                            presensi.tgl_24,
                            presensi.tgl_25,
                            presensi.tgl_26,
                            presensi.tgl_27,
                            presensi.tgl_28,
                            presensi.tgl_29,
                            presensi.tgl_30,
                            presensi.tgl_31"
                        );
        $query->leftJoin(
            DB::raw("(
                    SELECT
                    presensi.nik,
                    MAX(IF(tanggal_presensi = '$range_tanggal[0]', CONCAT(
                        IFNULL(presensi.jam_masuk, 'NA'), '|',
                        IFNULL(presensi.jam_keluar, 'NA'), '|',
                        IFNULL(presensi.status, 'NA') , '|',
                        IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
                        IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
                        IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
                        IFNULL(presensi.kode_izin, 'NA') , '|',
                        IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
                    ), NULL)) AS tgl_1,
                    MAX(IF(tanggal_presensi = '$range_tanggal[1]', CONCAT(
                        IFNULL(presensi.jam_masuk, 'NA'), '|',
                        IFNULL(presensi.jam_keluar, 'NA'), '|',
                        IFNULL(presensi.status, 'NA') , '|',
                        IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
                        IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
                        IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
                        IFNULL(presensi.kode_izin, 'NA') , '|',
                        IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
                    ), NULL)) AS tgl_2,
                    MAX(IF(tanggal_presensi = '$range_tanggal[2]', CONCAT(
                        IFNULL(presensi.jam_masuk, 'NA'), '|',
                        IFNULL(presensi.jam_keluar, 'NA'), '|',
                        IFNULL(presensi.status, 'NA') , '|',
                        IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
                        IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
                        IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
                        IFNULL(presensi.kode_izin, 'NA') , '|',
                        IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
                    ), NULL)) AS tgl_3,
                    MAX(IF(tanggal_presensi = '$range_tanggal[3]', CONCAT(
                        IFNULL(presensi.jam_masuk, 'NA'), '|',
                        IFNULL(presensi.jam_keluar, 'NA'), '|',
                        IFNULL(presensi.status, 'NA') , '|',
                        IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
                        IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
                        IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
                        IFNULL(presensi.kode_izin, 'NA') , '|',
                        IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
                    ), NULL)) AS tgl_4,
                    MAX(IF(tanggal_presensi = '$range_tanggal[4]', CONCAT(
                        IFNULL(presensi.jam_masuk, 'NA'), '|',
                        IFNULL(presensi.jam_keluar, 'NA'), '|',
                        IFNULL(presensi.status, 'NA') , '|',
                        IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
                        IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
                        IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
                        IFNULL(presensi.kode_izin, 'NA') , '|',
                        IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
                    ), NULL)) AS tgl_5,
                    MAX(IF(tanggal_presensi = '$range_tanggal[5]', CONCAT(
                        IFNULL(presensi.jam_masuk, 'NA'), '|',
                        IFNULL(presensi.jam_keluar, 'NA'), '|',
                        IFNULL(presensi.status, 'NA') , '|',
                        IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
                        IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
                        IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
                        IFNULL(presensi.kode_izin, 'NA') , '|',
                        IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
                    ), NULL)) AS tgl_6,
                    MAX(IF(tanggal_presensi = '$range_tanggal[6]', CONCAT(
                        IFNULL(presensi.jam_masuk, 'NA'), '|',
                        IFNULL(presensi.jam_keluar, 'NA'), '|',
                        IFNULL(presensi.status, 'NA') , '|',
                        IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
                        IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
                        IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
                        IFNULL(presensi.kode_izin, 'NA') , '|',
                        IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
                    ), NULL)) AS tgl_7,
                    MAX(IF(tanggal_presensi = '$range_tanggal[7]', CONCAT(
                        IFNULL(presensi.jam_masuk, 'NA'), '|',
                        IFNULL(presensi.jam_keluar, 'NA'), '|',
                        IFNULL(presensi.status, 'NA') , '|',
                        IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
                        IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
                        IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
                        IFNULL(presensi.kode_izin, 'NA') , '|',
                        IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
                    ), NULL)) AS tgl_8,
                    MAX(IF(tanggal_presensi = '$range_tanggal[8]', CONCAT(
                        IFNULL(presensi.jam_masuk, 'NA'), '|',
                        IFNULL(presensi.jam_keluar, 'NA'), '|',
                        IFNULL(presensi.status, 'NA') , '|',
                        IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
                        IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
                        IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
                        IFNULL(presensi.kode_izin, 'NA') , '|',
                        IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
                    ), NULL)) AS tgl_9,
                    MAX(IF(tanggal_presensi = '$range_tanggal[9]', CONCAT(
                        IFNULL(presensi.jam_masuk, 'NA'), '|',
                        IFNULL(presensi.jam_keluar, 'NA'), '|',
                        IFNULL(presensi.status, 'NA') , '|',
                        IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
                        IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
                        IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
                        IFNULL(presensi.kode_izin, 'NA') , '|',
                        IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
                    ), NULL)) AS tgl_10,
                    MAX(IF(tanggal_presensi = '$range_tanggal[10]', CONCAT(
                        IFNULL(presensi.jam_masuk, 'NA'), '|',
                        IFNULL(presensi.jam_keluar, 'NA'), '|',
                        IFNULL(presensi.status, 'NA') , '|',
                        IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
                        IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
                        IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
                        IFNULL(presensi.kode_izin, 'NA') , '|',
                        IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
                    ), NULL)) AS tgl_11,
                    MAX(IF(tanggal_presensi = '$range_tanggal[11]', CONCAT(
                        IFNULL(presensi.jam_masuk, 'NA'), '|',
                        IFNULL(presensi.jam_keluar, 'NA'), '|',
                        IFNULL(presensi.status, 'NA') , '|',
                        IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
                        IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
                        IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
                        IFNULL(presensi.kode_izin, 'NA') , '|',
                        IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
                    ), NULL)) AS tgl_12,
                    MAX(IF(tanggal_presensi = '$range_tanggal[12]', CONCAT(
                        IFNULL(presensi.jam_masuk, 'NA'), '|',
                        IFNULL(presensi.jam_keluar, 'NA'), '|',
                        IFNULL(presensi.status, 'NA') , '|',
                        IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
                        IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
                        IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
                        IFNULL(presensi.kode_izin, 'NA') , '|',
                        IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
                    ), NULL)) AS tgl_13,
                    MAX(IF(tanggal_presensi = '$range_tanggal[13]', CONCAT(
                        IFNULL(presensi.jam_masuk, 'NA'), '|',
                        IFNULL(presensi.jam_keluar, 'NA'), '|',
                        IFNULL(presensi.status, 'NA') , '|',
                        IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
                        IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
                        IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
                        IFNULL(presensi.kode_izin, 'NA') , '|',
                        IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
                    ), NULL)) AS tgl_14,
                    MAX(IF(tanggal_presensi = '$range_tanggal[14]', CONCAT(
                        IFNULL(presensi.jam_masuk, 'NA'), '|',
                        IFNULL(presensi.jam_keluar, 'NA'), '|',
                        IFNULL(presensi.status, 'NA') , '|',
                        IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
                        IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
                        IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
                        IFNULL(presensi.kode_izin, 'NA') , '|',
                        IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
                    ), NULL)) AS tgl_15,
                    MAX(IF(tanggal_presensi = '$range_tanggal[15]', CONCAT(
                        IFNULL(presensi.jam_masuk, 'NA'), '|',
                        IFNULL(presensi.jam_keluar, 'NA'), '|',
                        IFNULL(presensi.status, 'NA') , '|',
                        IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
                        IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
                        IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
                        IFNULL(presensi.kode_izin, 'NA') , '|',
                        IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
                    ), NULL)) AS tgl_16,
                    MAX(IF(tanggal_presensi = '$range_tanggal[16]', CONCAT(
                        IFNULL(presensi.jam_masuk, 'NA'), '|',
                        IFNULL(presensi.jam_keluar, 'NA'), '|',
                        IFNULL(presensi.status, 'NA') , '|',
                        IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
                        IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
                        IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
                        IFNULL(presensi.kode_izin, 'NA') , '|',
                        IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
                    ), NULL)) AS tgl_17,
                    MAX(IF(tanggal_presensi = '$range_tanggal[17]', CONCAT(
                        IFNULL(presensi.jam_masuk, 'NA'), '|',
                        IFNULL(presensi.jam_keluar, 'NA'), '|',
                        IFNULL(presensi.status, 'NA') , '|',
                        IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
                        IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
                        IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
                        IFNULL(presensi.kode_izin, 'NA') , '|',
                        IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
                    ), NULL)) AS tgl_18,
                    MAX(IF(tanggal_presensi = '$range_tanggal[18]', CONCAT(
                        IFNULL(presensi.jam_masuk, 'NA'), '|',
                        IFNULL(presensi.jam_keluar, 'NA'), '|',
                        IFNULL(presensi.status, 'NA') , '|',
                        IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
                        IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
                        IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
                        IFNULL(presensi.kode_izin, 'NA') , '|',
                        IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
                    ), NULL)) AS tgl_19,
                    MAX(IF(tanggal_presensi = '$range_tanggal[19]', CONCAT(
                        IFNULL(presensi.jam_masuk, 'NA'), '|',
                        IFNULL(presensi.jam_keluar, 'NA'), '|',
                        IFNULL(presensi.status, 'NA') , '|',
                        IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
                        IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
                        IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
                        IFNULL(presensi.kode_izin, 'NA') , '|',
                        IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
                    ), NULL)) AS tgl_20,
                    MAX(IF(tanggal_presensi = '$range_tanggal[20]', CONCAT(
                        IFNULL(presensi.jam_masuk, 'NA'), '|',
                        IFNULL(presensi.jam_keluar, 'NA'), '|',
                        IFNULL(presensi.status, 'NA') , '|',
                        IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
                        IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
                        IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
                        IFNULL(presensi.kode_izin, 'NA') , '|',
                        IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
                    ), NULL)) AS tgl_21,
                    MAX(IF(tanggal_presensi = '$range_tanggal[21]', CONCAT(
                        IFNULL(presensi.jam_masuk, 'NA'), '|',
                        IFNULL(presensi.jam_keluar, 'NA'), '|',
                        IFNULL(presensi.status, 'NA') , '|',
                        IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
                        IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
                        IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
                        IFNULL(presensi.kode_izin, 'NA') , '|',
                        IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
                    ), NULL)) AS tgl_22,
                    MAX(IF(tanggal_presensi = '$range_tanggal[22]', CONCAT(
                        IFNULL(presensi.jam_masuk, 'NA'), '|',
                        IFNULL(presensi.jam_keluar, 'NA'), '|',
                        IFNULL(presensi.status, 'NA') , '|',
                        IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
                        IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
                        IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
                        IFNULL(presensi.kode_izin, 'NA') , '|',
                        IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
                    ), NULL)) AS tgl_23,
                    MAX(IF(tanggal_presensi = '$range_tanggal[23]', CONCAT(
                        IFNULL(presensi.jam_masuk, 'NA'), '|',
                        IFNULL(presensi.jam_keluar, 'NA'), '|',
                        IFNULL(presensi.status, 'NA') , '|',
                        IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
                        IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
                        IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
                        IFNULL(presensi.kode_izin, 'NA') , '|',
                        IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
                    ), NULL)) AS tgl_24,
                    MAX(IF(tanggal_presensi = '$range_tanggal[24]', CONCAT(
                        IFNULL(presensi.jam_masuk, 'NA'), '|',
                        IFNULL(presensi.jam_keluar, 'NA'), '|',
                        IFNULL(presensi.status, 'NA') , '|',
                        IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
                        IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
                        IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
                        IFNULL(presensi.kode_izin, 'NA') , '|',
                        IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
                    ), NULL)) AS tgl_25,
                    MAX(IF(tanggal_presensi = '$range_tanggal[25]', CONCAT(
                        IFNULL(presensi.jam_masuk, 'NA'), '|',
                        IFNULL(presensi.jam_keluar, 'NA'), '|',
                        IFNULL(presensi.status, 'NA') , '|',
                        IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
                        IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
                        IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
                        IFNULL(presensi.kode_izin, 'NA') , '|',
                        IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
                    ), NULL)) AS tgl_26,
                    MAX(IF(tanggal_presensi = '$range_tanggal[26]', CONCAT(
                        IFNULL(presensi.jam_masuk, 'NA'), '|',
                        IFNULL(presensi.jam_keluar, 'NA'), '|',
                        IFNULL(presensi.status, 'NA') , '|',
                        IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
                        IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
                        IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
                        IFNULL(presensi.kode_izin, 'NA') , '|',
                        IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
                    ), NULL)) AS tgl_27,
                    MAX(IF(tanggal_presensi = '$range_tanggal[27]', CONCAT(
                        IFNULL(presensi.jam_masuk, 'NA'), '|',
                        IFNULL(presensi.jam_keluar, 'NA'), '|',
                        IFNULL(presensi.status, 'NA') , '|',
                        IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
                        IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
                        IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
                        IFNULL(presensi.kode_izin, 'NA') , '|',
                        IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
                    ), NULL)) AS tgl_28,
                    MAX(IF(tanggal_presensi = '$range_tanggal[28]', CONCAT(
                        IFNULL(presensi.jam_masuk, 'NA'), '|',
                        IFNULL(presensi.jam_keluar, 'NA'), '|',
                        IFNULL(presensi.status, 'NA') , '|',
                        IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
                        IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
                        IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
                        IFNULL(presensi.kode_izin, 'NA') , '|',
                        IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
                    ), NULL)) AS tgl_29,
                    MAX(IF(tanggal_presensi = '$range_tanggal[29]', CONCAT(
                        IFNULL(presensi.jam_masuk, 'NA'), '|',
                        IFNULL(presensi.jam_keluar, 'NA'), '|',
                        IFNULL(presensi.status, 'NA') , '|',
                        IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
                        IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
                        IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
                        IFNULL(presensi.kode_izin, 'NA') , '|',
                        IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
                    ), NULL)) AS tgl_30,
                    MAX(IF(tanggal_presensi = '$range_tanggal[30]', CONCAT(
                        IFNULL(presensi.jam_masuk, 'NA'), '|',
                        IFNULL(presensi.jam_keluar, 'NA'), '|',
                        IFNULL(presensi.status, 'NA') , '|',
                        IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
                        IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
                        IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
                        IFNULL(presensi.kode_izin, 'NA') , '|',
                        IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
                    ), NULL)) AS tgl_31
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
