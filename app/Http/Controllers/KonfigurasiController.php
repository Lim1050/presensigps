<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KonfigurasiController extends Controller
{
    public function LokasiKantor()
    {
        $lokasi_kantor = DB::table('lokasi_kantor')->where('id', 1)->first();
        return view('konfigurasi.lokasi_kantor', compact('lokasi_kantor'));
    }

    public function UpdateLokasiKantor(Request $request)
    {
        $lokasi_kantor = $request->lokasi_kantor;
        $radius = $request->radius;

        $update = DB::table('lokasi_kantor')->where('id', 1)->update([
            'lokasi_kantor' => $lokasi_kantor,
            'radius' => $radius
        ]);

        if($update){
            return redirect()->back()->with(['success' => 'Data Berhasil Diupdate!']);
        } else {
            return redirect()->back()->with(['warning' => 'Data Gagal Diupdate!']);
        }
    }

    public function JamKerja()
    {
        $jam_kerja = DB::table('jam_kerja')->orderBy('kode_jam_kerja')->get();
        return view('konfigurasi.jam_kerja', compact('jam_kerja'));
    }

    public function JamKerjaStore(Request $request)
    {
        $kode_jam_kerja = $request->kode_jam_kerja;
        $nama_jam_kerja = $request->nama_jam_kerja;
        $awal_jam_masuk = $request->awal_jam_masuk;
        $jam_masuk = $request->jam_masuk;
        $akhir_jam_masuk = $request->akhir_jam_masuk;
        $jam_pulang = $request->jam_pulang;

        try {
            $data = [
                'kode_jam_kerja' => $kode_jam_kerja,
                'nama_jam_kerja' => $nama_jam_kerja,
                'awal_jam_masuk' => $awal_jam_masuk,
                'jam_masuk' => $jam_masuk,
                'akhir_jam_masuk' => $akhir_jam_masuk,
                'jam_pulang' => $jam_pulang,
                'created_at' => Carbon::now()
            ];

            $save = DB::table('jam_kerja')->insert($data);
            if($save){
                return redirect()->route('admin.konfigurasi.jam.kerja')->with(['success' => 'Data Berhasil Disimpan!']);
            }
        } catch (\Exception $e) {
            if($e->getCode()==23000){
                $message = " Data dengan Kode Jam Kerja " . $kode_jam_kerja . " Sudah ada!";
            }
            return redirect()->back()->with(['error' => 'Data Gagal Disimpan!' . $message]);
        }
    }

    public function JamKerjaUpdate(Request $request, $kode_jam_kerja)
    {
        $kode_jam_kerja = $request->kode_jam_kerja;
        $nama_jam_kerja = $request->nama_jam_kerja;
        $awal_jam_masuk = $request->awal_jam_masuk;
        $jam_masuk = $request->jam_masuk;
        $akhir_jam_masuk = $request->akhir_jam_masuk;
        $jam_pulang = $request->jam_pulang;

        try {
            $data = [
                'nama_jam_kerja' => $nama_jam_kerja,
                'awal_jam_masuk' => $awal_jam_masuk,
                'jam_masuk' => $jam_masuk,
                'akhir_jam_masuk' => $akhir_jam_masuk,
                'jam_pulang' => $jam_pulang,
                'updated_at' => Carbon::now()
            ];

            $update = DB::table('jam_kerja')->where('kode_jam_kerja', $kode_jam_kerja)->update($data);
            if($update){
                return redirect()->route('admin.konfigurasi.jam.kerja')->with(['success' => 'Data Berhasil Diupdate!']);
            }
        } catch (\Exception $e) {
            if($e->getCode()==23000){
                $message = " Data dengan Kode Jam Kerja " . $kode_jam_kerja . " Sudah ada!";
            }
            return redirect()->back()->with(['error' => 'Data Gagal Disimpan!' . $message]);
        }

    }

    public function JamKerjaDelete($kode_jam_kerja)
    {

        // Hapus data cabang dari database
        $delete = DB::table('jam_kerja')->where('kode_jam_kerja', $kode_jam_kerja)->delete();

        if ($delete) {
            return redirect()->back()->with(['success' => 'Data Berhasil Dihapus!']);

        } else {
            return redirect()->back()->with(['warning' => 'Data Jam Kerja Tidak Ditemukan!']);
        }
    }
}
