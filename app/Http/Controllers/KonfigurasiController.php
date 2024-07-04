<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
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
}
