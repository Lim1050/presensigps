<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KaryawanController extends Controller
{
    public function KaryawanIndex(Request $request)
    {

        $query = Karyawan::query();
        $query->select('karyawan.*', 'nama_departemen');
        $query->join('departemen', 'karyawan.kode_departemen', '=', 'departemen.kode_departemen');
        $query->orderBy('nama_lengkap');
        if(!empty($request->nama_karyawan)){
            $query->where('nama_lengkap', 'like', '%' . $request->nama_karyawan . '%');
        }
        if(!empty($request->kode_departemen)){
            $query->where('karyawan.kode_departemen', $request->kode_departemen);
        }
        $karyawan = $query->paginate('2');


        $departemen = DB::table('departemen')->get();

        return view('karyawan.karyawan_index', compact('karyawan', 'departemen'));
    }
}
