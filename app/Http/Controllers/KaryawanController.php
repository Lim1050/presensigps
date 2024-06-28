<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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
        $karyawan = $query->paginate('10');


        $departemen = DB::table('departemen')->get();

        return view('karyawan.karyawan_index', compact('karyawan', 'departemen'));
    }

    public function KaryawanStore(Request $request)
    {
        $nik = $request->nik;
        $nama_lengkap = $request->nama_lengkap;
        $no_wa = $request->no_wa;
        $jabatan = $request->jabatan;
        $kode_departemen = $request->kode_departemen;

        // get data karyawan dari table
        // cek apakah ada foto dari form
        if($request->hasFile('foto')){
            $foto = $nik . "_" . time() . "." . $request->file('foto')->getClientOriginalExtension();
        } else {
            // Jika tidak ada file foto yang diunggah, gunakan foto default
            $foto = null;
        }

        try {
            $data = [
                'nik' => $nik,
                'nama_lengkap' => $nama_lengkap,
                'no_wa' => $no_wa,
                'password' => Hash::make('password123'),
                'jabatan' => $jabatan,
                'kode_departemen' => $kode_departemen,
                'foto' => $foto,
                'created_at' => Carbon::now()
            ];
            $save = DB::table('karyawan')->insert($data);
            if($save){
                // save foto ke storage
            if($request->hasFile('foto')){
                $folderPath = "public/uploads/karyawan/";
                $request->file('foto')->storeAs($folderPath, $foto);
            }
            return redirect()->back()->with(['success' => 'Data Berhasil Disimpan!']);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Data Gagal Disimpan!']);
        }
    }

    public function KaryawanEdit(Request $request)
    {
        $nik = $request->nik;
        $departemen = DB::table('departemen')->get();
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();
        return view('karyawan.karyawan_edit', compact('nik', 'departemen', 'karyawan'));
    }

    public function KaryawanUpdate(Request $request, $nik)
    {
        $nik = $request->nik;
        $nama_lengkap = $request->nama_lengkap;
        $no_wa = $request->no_wa;
        $jabatan = $request->jabatan;
        $kode_departemen = $request->kode_departemen;

        // get data karyawan dari table
        // cek apakah ada foto dari form
        if($request->hasFile('foto')){
            $foto = $nik . "_" . time() . "." . $request->file('foto')->getClientOriginalExtension();
        } else {
            // Jika tidak ada file foto yang diunggah, gunakan foto sebelumnya
            $foto = null;
        }

        try {
            $data = [
                'nik' => $nik,
                'nama_lengkap' => $nama_lengkap,
                'no_wa' => $no_wa,
                'password' => Hash::make('password123'),
                'jabatan' => $jabatan,
                'kode_departemen' => $kode_departemen,
                'foto' => $foto,
                'created_at' => Carbon::now()
            ];
            $save = DB::table('karyawan')->insert($data);
            if($save){
                // save foto ke storage
            if($request->hasFile('foto')){
                $folderPath = "public/uploads/karyawan/";
                $request->file('foto')->storeAs($folderPath, $foto);
            }
            return redirect()->back()->with(['success' => 'Data Berhasil Disimpan!']);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Data Gagal Disimpan!']);
        }
    }
}
