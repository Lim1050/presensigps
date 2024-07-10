<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Departemen;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartemenController extends Controller
{
    public function DepartemenIndex(Request $request)
    {
        $nama_departemen = $request->nama_departemen_cari;
        $query = Departemen::query();
        $query->select('*');
        if(!empty($nama_departemen)){
            $query->where('nama_departemen', 'like', '%' . $nama_departemen . '%');
        }
        $departemen = $query->paginate(10);
        // dd($departemen->kode_departemen);
        // $departemen = DB::table('departemen')->orderBy('kode_departemen')->get();
        return view('departemen.departemen_index', compact('departemen'));
    }

    public function DepartemenStore(Request $request)
    {
        $kode_departemen = $request->kode_departemen;
        $nama_departemen = $request->nama_departemen;


        try {
            $data = [
                'kode_departemen' => $kode_departemen,
                'nama_departemen' => $nama_departemen,
                'created_at' => Carbon::now()
            ];

            $save = DB::table('departemen')->insert($data);
            if($save){
                return redirect()->route('admin.departemen')->with(['success' => 'Data Berhasil Disimpan!']);
            }
        } catch (\Exception $e) {
            if($e->getCode()==23000){
                $message = " Data dengan Kode Departemen " . $kode_departemen . " Sudah ada!";
            }
            return redirect()->back()->with(['error' => 'Data Gagal Disimpan!' . $message]);
        }
    }

    public function DepartemenEdit(Request $request, $kode_departemen)
    {
        $kode_departemen = $request->kode_departemen;
        $departemen = DB::table('departemen')->where('kode_departemen', $kode_departemen)->first();
        return view('departemen.departemen_edit', compact( 'departemen'));
    }

    public function DepartemenUpdate(Request $request)
    {
        $kode_departemen = $request->kode_departemen;
        $nama_departemen = $request->nama_departemen;


        try {
            $data = [
                'nama_departemen' => $nama_departemen,
                'updated_at' => Carbon::now()
            ];

            $update = DB::table('departemen')->where('kode_departemen', $kode_departemen)->update($data);
            if($update){
                return redirect()->route('admin.departemen')->with(['success' => 'Data Berhasil Diupdate!']);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Data Gagal Disimpan!']);
        }
    }

    public function DepartemenDelete($kode_departemen)
    {

        // Hapus data departemen dari database
        $delete = DB::table('departemen')->where('kode_departemen', $kode_departemen)->delete();

        if ($delete) {
            return redirect()->back()->with(['success' => 'Data Berhasil Dihapus!']);

        } else {
            return redirect()->back()->with(['warning' => 'Data Karyawan Tidak Ditemukan!']);
        }
    }
}
