<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IzinController extends Controller
{
    public function CreateIzinAbsen()
    {
        return view('izin.create_izin_absen');
    }
    public function StoreIzinAbsen(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $tanggal_izin_dari = $request->tanggal_izin_dari;
        $tanggal_izin_sampai = $request->tanggal_izin_sampai;
        $jumlah_hari = $request->jumlah_hari;
        $status = 'izin';
        $keterangan = $request->keterangan;

        $data = [
            'nik' => $nik,
            'tanggal_izin_dari' => $tanggal_izin_dari,
            'tanggal_izin_sampai' => $tanggal_izin_sampai,
            'jumlah_hari' => $jumlah_hari,
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
    public function CreateIzinSakit()
    {
        return view('izin.create_izin_sakit');
    }
    public function CreateIzinCuti()
    {
        return view('izin.create_izin_cuti');
    }
}
