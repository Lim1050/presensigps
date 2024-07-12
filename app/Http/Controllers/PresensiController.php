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
        $cek_masuk = DB::table('presensi')->where('tanggal_presensi', $hari_ini)->where('nik', $nik)->count();
        $cek_keluar = DB::table('presensi')->where('tanggal_presensi', $hari_ini)->where('nik', $nik)->whereNotNull('foto_keluar')->count();
        $foto_keluar = DB::table('presensi')->where('nik', $nik)->where('tanggal_presensi', $hari_ini)->whereNotNull('foto_keluar')->first();

        $kode_cabang = Auth::guard('karyawan')->user()->kode_cabang;
        $lokasi_kantor = DB::table('kantor_cabang')->where('kode_cabang', $kode_cabang)->first();

        $jam_kerja_karyawan = DB::table('jam_kerja_karyawan')
                                ->join('jam_kerja', 'jam_kerja_karyawan.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
                                ->where('nik', $nik)
                                ->where('hari', $nama_hari)
                                ->first();

        return view('presensi.create_presensi', compact('nama_hari','cek_masuk', 'cek_keluar', 'foto_keluar', 'lokasi_kantor', 'jam_kerja_karyawan'));
    }

    public function PresensiStore(Request $request)
    {
        // inisiasi data
        $nik = Auth::guard('karyawan')->user()->nik;
        $tgl_presensi = date("Y-m-d");
        $jam_presensi = date("H:i:s");
        $jam = explode(":", $jam_presensi);
        $jam_save = $jam[0] . $jam[1] . $jam[2];

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
            if ($cek > 0 ) {
            $data_pulang = [
                'jam_keluar' => $jam_presensi,
                'foto_keluar' => $file,
                'lokasi_keluar' => $lokasi,
                'updated_at' => Carbon::now()
            ];
            $update = DB::table('presensi')->where('nik', $nik)->where('tanggal_presensi', $tgl_presensi)->update($data_pulang);
            if($update){
                echo "success|Terima kasih, Hati - hati dijalan!|out";
                Storage::put($file, $image_base64);
            } else {
                echo "error|Maaf Gagal absen, hubungi Tim IT|out";
            }
        } else {
            $data_masuk = [
                'nik' => $nik,
                'tanggal_presensi' => $tgl_presensi,
                'jam_masuk' => $jam_presensi,
                'foto_masuk' => $file,
                'lokasi_masuk' => $lokasi,
                'created_at' => Carbon::now()
            ];
            $save = DB::table('presensi')->insert($data_masuk);
            if($save){
                echo "success|Terima kasih, Selamat bekerja!|in";
                Storage::put($file, $image_base64);
            } else {
                echo "error|Maaf Gagal absen, hubungi Tim IT|in";
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

    public function SakitIzin()
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $dataIzin = DB::table('pengajuan_sakit_izin')
            ->where('nik', $nik)
            ->get();
        return view('presensi.sakit_izin', compact('dataIzin'));
    }
    public function CreateSakitIzin()
    {

        return view('presensi.create_sakit_izin');
    }
    public function StoreSakitIzin(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $tanggal_izin = $request->tanggal_izin;
        $status = $request->status;
        $keterangan = $request->keterangan;

        $data = [
            'nik' => $nik,
            'tanggal_izin' => $tanggal_izin,
            'status' => $status,
            'keterangan' => $keterangan,
            'created_at' => Carbon::now()
        ];

        $save = DB::table('pengajuan_sakit_izin')->insert($data);

        if($save){
            return redirect('/presensi/sakit-izin')->with(['success' => 'Data berhasil disimpan!']);
        } else {
            return redirect()->back()->with(['error' => 'Data gagal disimpan!']);
        }
    }

    // Admin Monitoring Presensi
    public function MonitoringPresensi()
    {
        return view('presensi.monitoring_presensi');
    }

    public function MonitoringGetPresensi(Request $request)
    {
        $tanggal_presensi = $request->tanggal_presensi;
        $presensi = DB::table('presensi')
            ->select('presensi.*', 'nama_lengkap', 'nama_departemen')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->join('departemen', 'karyawan.kode_departemen', '=', 'departemen.kode_departemen')
            ->where('tanggal_presensi', $tanggal_presensi)
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
                    ->where('nik', $nik)
                    ->first();

        $presensi = DB::table('presensi')
                    ->where('nik', $nik)
                    ->whereRaw('MONTH(tanggal_presensi)="' . $bulan . '"')
                    ->whereRaw('YEAR(tanggal_presensi)="' . $tahun . '"')
                    ->orderBy('tanggal_presensi')
                    ->get();

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

        $rekap = DB::table('presensi')
                    ->selectRaw('presensi.nik,nama_lengkap,
                    MAX(IF(DAY(tanggal_presensi) = 1, CONCAT(jam_masuk, "-", IFNULL(jam_keluar,"00:00:00")),"")) as tanggal_1,
                    MAX(IF(DAY(tanggal_presensi) = 2, CONCAT(jam_masuk, "-", IFNULL(jam_keluar,"00:00:00")),"")) as tanggal_2,
                    MAX(IF(DAY(tanggal_presensi) = 3, CONCAT(jam_masuk, "-", IFNULL(jam_keluar,"00:00:00")),"")) as tanggal_3,
                    MAX(IF(DAY(tanggal_presensi) = 4, CONCAT(jam_masuk, "-", IFNULL(jam_keluar,"00:00:00")),"")) as tanggal_4,
                    MAX(IF(DAY(tanggal_presensi) = 5, CONCAT(jam_masuk, "-", IFNULL(jam_keluar,"00:00:00")),"")) as tanggal_5,
                    MAX(IF(DAY(tanggal_presensi) = 6, CONCAT(jam_masuk, "-", IFNULL(jam_keluar,"00:00:00")),"")) as tanggal_6,
                    MAX(IF(DAY(tanggal_presensi) = 7, CONCAT(jam_masuk, "-", IFNULL(jam_keluar,"00:00:00")),"")) as tanggal_7,
                    MAX(IF(DAY(tanggal_presensi) = 8, CONCAT(jam_masuk, "-", IFNULL(jam_keluar,"00:00:00")),"")) as tanggal_8,
                    MAX(IF(DAY(tanggal_presensi) = 9, CONCAT(jam_masuk, "-", IFNULL(jam_keluar,"00:00:00")),"")) as tanggal_9,
                    MAX(IF(DAY(tanggal_presensi) = 10, CONCAT(jam_masuk, "-", IFNULL(jam_keluar,"00:00:00")),"")) as tanggal_10,
                    MAX(IF(DAY(tanggal_presensi) = 11, CONCAT(jam_masuk, "-", IFNULL(jam_keluar,"00:00:00")),"")) as tanggal_11,
                    MAX(IF(DAY(tanggal_presensi) = 12, CONCAT(jam_masuk, "-", IFNULL(jam_keluar,"00:00:00")),"")) as tanggal_12,
                    MAX(IF(DAY(tanggal_presensi) = 13, CONCAT(jam_masuk, "-", IFNULL(jam_keluar,"00:00:00")),"")) as tanggal_13,
                    MAX(IF(DAY(tanggal_presensi) = 14, CONCAT(jam_masuk, "-", IFNULL(jam_keluar,"00:00:00")),"")) as tanggal_14,
                    MAX(IF(DAY(tanggal_presensi) = 15, CONCAT(jam_masuk, "-", IFNULL(jam_keluar,"00:00:00")),"")) as tanggal_15,
                    MAX(IF(DAY(tanggal_presensi) = 16, CONCAT(jam_masuk, "-", IFNULL(jam_keluar,"00:00:00")),"")) as tanggal_16,
                    MAX(IF(DAY(tanggal_presensi) = 17, CONCAT(jam_masuk, "-", IFNULL(jam_keluar,"00:00:00")),"")) as tanggal_17,
                    MAX(IF(DAY(tanggal_presensi) = 18, CONCAT(jam_masuk, "-", IFNULL(jam_keluar,"00:00:00")),"")) as tanggal_18,
                    MAX(IF(DAY(tanggal_presensi) = 19, CONCAT(jam_masuk, "-", IFNULL(jam_keluar,"00:00:00")),"")) as tanggal_19,
                    MAX(IF(DAY(tanggal_presensi) = 20, CONCAT(jam_masuk, "-", IFNULL(jam_keluar,"00:00:00")),"")) as tanggal_20,
                    MAX(IF(DAY(tanggal_presensi) = 21, CONCAT(jam_masuk, "-", IFNULL(jam_keluar,"00:00:00")),"")) as tanggal_21,
                    MAX(IF(DAY(tanggal_presensi) = 22, CONCAT(jam_masuk, "-", IFNULL(jam_keluar,"00:00:00")),"")) as tanggal_22,
                    MAX(IF(DAY(tanggal_presensi) = 23, CONCAT(jam_masuk, "-", IFNULL(jam_keluar,"00:00:00")),"")) as tanggal_23,
                    MAX(IF(DAY(tanggal_presensi) = 24, CONCAT(jam_masuk, "-", IFNULL(jam_keluar,"00:00:00")),"")) as tanggal_24,
                    MAX(IF(DAY(tanggal_presensi) = 25, CONCAT(jam_masuk, "-", IFNULL(jam_keluar,"00:00:00")),"")) as tanggal_25,
                    MAX(IF(DAY(tanggal_presensi) = 26, CONCAT(jam_masuk, "-", IFNULL(jam_keluar,"00:00:00")),"")) as tanggal_26,
                    MAX(IF(DAY(tanggal_presensi) = 27, CONCAT(jam_masuk, "-", IFNULL(jam_keluar,"00:00:00")),"")) as tanggal_27,
                    MAX(IF(DAY(tanggal_presensi) = 28, CONCAT(jam_masuk, "-", IFNULL(jam_keluar,"00:00:00")),"")) as tanggal_28,
                    MAX(IF(DAY(tanggal_presensi) = 29, CONCAT(jam_masuk, "-", IFNULL(jam_keluar,"00:00:00")),"")) as tanggal_29,
                    MAX(IF(DAY(tanggal_presensi) = 30, CONCAT(jam_masuk, "-", IFNULL(jam_keluar,"00:00:00")),"")) as tanggal_30,
                    MAX(IF(DAY(tanggal_presensi) = 31, CONCAT(jam_masuk, "-", IFNULL(jam_keluar,"00:00:00")),"")) as tanggal_31')
                    ->join('karyawan','presensi.nik','=','karyawan.nik')
                    ->whereRaw('MONTH(tanggal_presensi)="' . $bulan . '"')
                    ->whereRaw('YEAR(tanggal_presensi)="' . $tahun . '"')
                    ->groupByRaw('presensi.nik,nama_lengkap')
                    ->get();
        // dd($rekap);

        if(isset($_POST['export_excel'])){
            $time = date("H:i:s");
            // fungsi header dengan mengirimkan raw data excel
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file export "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Laporan_Presensi_$time.xls");
        }

        return view('presensi.rekap_print', compact('bulan', 'tahun', 'months', 'rekap'));
    }

    public function PersetujuanSakitIzin(Request $request)
    {

        $query = PersetujuanSakitIzin::query();
        $query->select('id', 'tanggal_izin', 'pengajuan_sakit_izin.nik', 'nama_lengkap', 'jabatan', 'status', 'status_approved', 'keterangan');
        $query->join('karyawan', 'pengajuan_sakit_izin.nik', '=', 'karyawan.nik');
        $query->orderBy('tanggal_izin', 'desc');

        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('tanggal_izin', [$request->dari, $request->sampai]);
        }
        if (!empty($request->nik)) {
            $query->where('pengajuan_sakit_izin.nik', 'like', '%' .  $request->nik . "%");
        }
        if (!empty($request->nama_lengkap)) {
            $query->where('nama_lengkap', 'like', '%'. $request->nama_lengkap . '%');
        }
        if (!empty($request->jabatan)) {
            $query->where('jabatan', 'like', '%'. $request->jabatan . '%');
        }
        if ($request->status_approved != '') {
            $query->where('status_approved', $request->status_approved);
        }

        $sakit_izin = $query->paginate('10');
        $sakit_izin->appends($request->all());
        // dd($sakit_izin);
        return view('presensi.persetujuan_sakit_izin', compact('sakit_izin'));
    }

    public function ApprovalSakitIzin(Request $request)
    {
        $id = $request->id;
        $status_approved = $request->status_approved;

        $update = DB::table('pengajuan_sakit_izin')
            ->where('id', $id)
            ->update([
                'status_approved' => $status_approved
            ]);

        if($update){
            return redirect()->back()->with(['success' => 'Data Berhasil Diupdate!']);
        } else {
            return redirect()->back()->with(['warning' => 'Data Gagal Diupdate!']);
        }
    }
    public function BatalkanSakitIzin($id)
    {
        $update = DB::table('pengajuan_sakit_izin')
            ->where('id', $id)
            ->update([
                'status_approved' => 0
            ]);

        if($update){
            return redirect()->back()->with(['success' => 'Data Berhasil Diupdate!']);
        } else {
            return redirect()->back()->with(['warning' => 'Data Gagal Diupdate!']);
        }
    }

    public function CekPengajuanSakitIzin(Request $request)
    {
        $tanggal_izin = $request->tanggal_izin;
        $nik = Auth::guard('karyawan')->user()->nik;
        $cek = DB::table('pengajuan_sakit_izin')->where('nik', $nik)->where('tanggal_izin', $tanggal_izin)->count();
        return $cek;
    }
}
