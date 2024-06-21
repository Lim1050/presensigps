<?php

namespace App\Http\Controllers;

use App\Models\presensi;
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
        $lokasi = $request->lokasi;
        $image = $request->image;
        $folderPath = "public/uploads/absensi/";

        // setting simpan gambar
        $formatName = $nik . "-" . $tgl_presensi . "-" . $jam_presensi;
        $image_parts = explode(";base64", $image);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = $formatName . ".png";
        $file = $folderPath . $fileName;

        // save data

        $cek = DB::table('presensi')->where('tanggal_presensi', $tgl_presensi)->where('nik', $nik)->count();
        if ($cek > 0 ) {
            $data_pulang = [
                'tanggal_presensi' => $tgl_presensi,
                'jam_keluar' => $jam_presensi,
                'foto_keluar' => $file,
                'lokasi_keluar' => $lokasi
            ];
            $update = DB::table('presensi')->where('nik', $nik)->update($data_pulang);
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
                'lokasi_masuk' => $lokasi
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
