<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function Profile()
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();
        // dd($karyawan);
        return view('profile.profile', compact('karyawan'));
    }

    public function ProfileUpdate(Request $request)
    {
        // get data dari form
        $nik = Auth::guard('karyawan')->user()->nik;
        $nama_lengkap = $request->nama_lengkap;
        $jabatan = $request->jabatan;
        $no_wa = $request->no_wa;
        $password = Hash::make($request->password);

        // get data karyawan dari table
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();

        // cek apakah ada foto dari form
        if($request->hasFile('foto')){
            $foto = $nik . "_" . time() . "." . $request->file('foto')->getClientOriginalExtension();
             // Hapus foto lama jika ada
            if (!empty($karyawan->foto)) {
            Storage::delete("public/uploads/karyawan/{$karyawan->foto}");
            }
        } else {
            // Jika tidak ada file foto yang diunggah, gunakan foto yang sudah ada
            $foto = $karyawan->foto;
        }

        // if($request->hasFile('foto')){
        //     $file = $request->file('foto');
        //     @unlink(public_path('storage/uploads/karyawan/' . $karyawan->foto));
        //     $filename = $nik . "." . $file->getClientOriginalExtension();
        //     $file->move(public_path('storage/uploads/karyawan/'), $filename);
        //     $foto = $filename;
        // } else {
        //     $foto = $karyawan->foto;
        // }

        // cek apakah mengubah password / tidak
        if(empty($request->password)){
            $data = [
            'nama_lengkap' => $nama_lengkap,
            'jabatan' => $jabatan,
            'no_wa' => $no_wa,
            'foto' => $foto,
            'updated_at' => Carbon::now()
            ];
        } else {
            $data = [
            'nama_lengkap' => $nama_lengkap,
            'jabatan' => $jabatan,
            'no_wa' => $no_wa,
            'foto' => $foto,
            'password' => $password,
            'updated_at' => Carbon::now()
            ];
        }

        // update data to table karyawan
        $update = DB::table('karyawan')->where('nik', $nik)->update($data);
        if($update) {
            // save foto ke storage
            if($request->hasFile('foto')){
                $folderPath = "public/uploads/karyawan/";
                $request->file('foto')->storeAs($folderPath, $foto);
            }
            return redirect()->back()->with(['success' => 'Data Berhasil Diupdate!']);
        } else {
            return redirect()->back()->with(['error' => 'Data Gagal Diupdate!']);
        }

    }
}
