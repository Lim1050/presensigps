<?php

namespace App\Http\Controllers;

use App\Models\presensi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PresensiController extends Controller
{
    public function PresensiCreate()
    {
        // cek apakah sudah absen
        $hari_ini = date("Y-m-d");
        $nik = Auth::guard('karyawan')->user()->nik;
        $cek_masuk = DB::table('presensi')->where('tanggal_presensi', $hari_ini)->where('nik', $nik)->count();
        $cek_keluar = DB::table('presensi')->where('tanggal_presensi', $hari_ini)->where('nik', $nik)->whereNotNull('foto_keluar')->count();
        $foto_keluar = DB::table('presensi')->where('nik', $nik)->where('tanggal_presensi', $hari_ini)->whereNotNull('foto_keluar')->first();
        return view('presensi.create_presensi', compact('cek_masuk', 'cek_keluar', 'foto_keluar'));
    }

    public function PresensiStore(Request $request)
    {
        // inisiasi data
        $nik = Auth::guard('karyawan')->user()->nik;
        $tgl_presensi = date("Y-m-d");
        $jam_presensi = date("H:i:s");
        $jam = explode(":", $jam_presensi);
        $jam_save = $jam[0] . $jam[1] . $jam[2];

        $latitude_kantor = -6.235776234264604;
        $longitude_kantor = 107.00803747720603;
        $lokasi = $request->lokasi;
        $lokasi_user = explode(",", $lokasi);
        $latitude_user = $lokasi_user[0];
        $longitude_user = $lokasi_user[1];

        $jarak = $this->distance($latitude_user, $longitude_user, $latitude_user, $longitude_user);
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
        if ($radius > 60) {
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
}
