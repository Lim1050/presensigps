<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CabangController extends Controller
{
    public function CabangIndex()
    {
        $cabang = DB::table('kantor_cabang')
                    ->orderBy('kode_cabang')
                    ->get();
        return view('cabang.cabang_index', compact('cabang'));
    }

    public function CabangStore(Request $request)
    {
        $kode_cabang = $request->kode_cabang;
        $nama_cabang = $request->nama_cabang;
        $lokasi_kantor = $request->lokasi_kantor;
        $radius = $request->radius;

        try {
            $data = [
                'kode_cabang' => $kode_cabang,
                'nama_cabang' => $nama_cabang,
                'lokasi_kantor' => $lokasi_kantor,
                'radius' => $radius,
                'created_at' => Carbon::now()
            ];

            $save = DB::table('kantor_cabang')->insert($data);
            if($save){
                return redirect()->route('admin.cabang')->with(['success' => 'Data Berhasil Disimpan!']);
            }
        } catch (\Exception $e) {
            if($e->getCode()==23000){
                $message = " Data dengan Kode Departemen " . $kode_cabang . " Sudah ada!";
            }
            return redirect()->back()->with(['error' => 'Data Gagal Disimpan!' . $message]);
        }
    }

    public function CabangEdit(Request $request, $kode_cabang)
    {
        $kode_cabang = $request->kode_cabang;
        $cabang = DB::table('kantor_cabang')->where('kode_cabang', $kode_cabang)->first();
        return view('cabang.cabang_edit', compact( 'cabang'));
    }

    public function CabangUpdate(Request $request)
    {
        $kode_cabang = $request->kode_cabang;
        $nama_cabang = $request->nama_cabang;
        $lokasi_kantor = $request->lokasi_kantor;
        $radius = $request->radius;


        try {
            $data = [
                'nama_cabang' => $nama_cabang,
                'lokasi_kantor' => $lokasi_kantor,
                'radius' => $radius,
                'updated_at' => Carbon::now()
            ];

            $update = DB::table('kantor_cabang')->where('kode_cabang', $kode_cabang)->update($data);
            if($update){
                return redirect()->route('admin.cabang')->with(['success' => 'Data Berhasil Diupdate!']);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Data Gagal Disimpan!']);
        }
    }

    public function CabangDelete($kode_cabang)
    {

        // Hapus data cabang dari database
        $delete = DB::table('kantor_cabang')->where('kode_cabang', $kode_cabang)->delete();

        if ($delete) {
            return redirect()->back()->with(['success' => 'Data Berhasil Dihapus!']);

        } else {
            return redirect()->back()->with(['warning' => 'Data Kantor Cabang Tidak Ditemukan!']);
        }
    }
}
