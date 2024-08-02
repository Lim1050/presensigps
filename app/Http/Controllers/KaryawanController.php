<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JamKerjaKaryawan;
use App\Models\Karyawan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
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
        $cabang = DB::table('kantor_cabang')->orderBy('kode_cabang')->get();

        return view('karyawan.karyawan_index', compact('karyawan', 'departemen', 'cabang'));
    }

    public function KaryawanStore(Request $request)
    {
        $nik = $request->nik;
        $nama_lengkap = $request->nama_lengkap;
        $no_wa = $request->no_wa;
        $jabatan = $request->jabatan;
        $kode_departemen = $request->kode_departemen;
        $kode_cabang = $request->kode_cabang;

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
                'kode_cabang' => $kode_cabang,
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
            return redirect()->route('admin.karyawan')->with(['success' => 'Data Berhasil Disimpan!']);
            }
        } catch (\Exception $e) {
            // dd($e->getCode());
            if($e->getCode()==23000){
                $message = " Data dengan NIK " . $nik . " Sudah ada!";
            } else {
                $message = " Hubungi Tim IT";
            }
            return redirect()->back()->with(['error' => 'Data Gagal Disimpan!' . $message]);
        }
    }

    public function KaryawanEdit(Request $request, $nik)
    {
        $nik = $request->nik;
        $departemen = DB::table('departemen')->get();
        $cabang = DB::table('kantor_cabang')->orderBy('kode_cabang')->get();
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();
        return view('karyawan.karyawan_edit', compact('nik', 'departemen', 'karyawan', 'cabang'));
    }

    public function KaryawanUpdate(Request $request)
    {
        $nik = $request->nik;
        $nama_lengkap = $request->nama_lengkap;
        $no_wa = $request->no_wa;
        $jabatan = $request->jabatan;
        $kode_departemen = $request->kode_departemen;
        $kode_cabang = $request->kode_cabang;
        $old_foto = $request->old_foto;

        // cek apakah ada foto dari form
        if($request->hasFile('foto')){
            $foto = $nik . "_" . time() . "." . $request->file('foto')->getClientOriginalExtension();
        } else {
            // Jika tidak ada file foto yang diunggah, gunakan foto sebelumnya
            $foto = $old_foto;
        }

        try {
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'no_wa' => $no_wa,
                // 'password' => Hash::make('password123'),
                'jabatan' => $jabatan,
                'kode_departemen' => $kode_departemen,
                'kode_cabang' => $kode_cabang,
                'foto' => $foto,
                'updated_at' => Carbon::now()
            ];
            $update = DB::table('karyawan')->where('nik',$nik)->update($data);
            // dd($update);
            if($update){
                // save foto ke storage
                if($request->hasFile('foto')){
                    $folderPath = "public/uploads/karyawan/";
                    $request->file('foto')->storeAs($folderPath, $foto);

                    // Hapus foto lama jika berbeda dengan foto baru
                    if ($old_foto !== $foto) {
                        Storage::delete($folderPath . $old_foto);
                    }
                }
            return redirect()->route('admin.karyawan')->with(['success' => 'Data Berhasil Diupdate!']);
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.karyawan')->with(['error' => 'Data Gagal Diupdate!']);
        }
    }

    public function KaryawanDelete($nik)
    {
        // Ambil data karyawan berdasarkan NIK
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();

        // Periksa apakah data karyawan ada
        if ($karyawan) {
            // Hapus foto dari storage
            $folderPath = "public/uploads/karyawan/";
            if ($karyawan->foto && Storage::exists($folderPath . $karyawan->foto)) {
                Storage::delete($folderPath . $karyawan->foto);
            }

            // Hapus data karyawan dari database
            $delete = DB::table('karyawan')->where('nik', $nik)->delete();

            if ($delete) {
                return redirect()->back()->with(['success' => 'Data Berhasil Dihapus!']);
            } else {
                return redirect()->back()->with(['warning' => 'Data Gagal Dihapus!']);
            }
        } else {
            return redirect()->back()->with(['warning' => 'Data Karyawan Tidak Ditemukan!']);
        }
    }

    public function KaryawanSetting(Request $request, $nik)
    {
        $nik = $request->nik;
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();
        $jam_kerja = DB::table('jam_kerja')->orderBy('kode_jam_kerja')->get();
        $cek_jam_kerja_karyawan = DB::table('jam_kerja_karyawan')->where('nik', $nik)->count();

        if($cek_jam_kerja_karyawan > 0 ) {
            $jam_kerja_karyawan = DB::table('jam_kerja_karyawan')->where('nik', $nik)->get();
            return view('karyawan.karyawan_setting_edit', compact('nik', 'karyawan', 'jam_kerja', 'jam_kerja_karyawan'));
        } else {
            return view('karyawan.karyawan_setting', compact('nik', 'karyawan', 'jam_kerja'));
        }

    }

    public function KaryawanSettingStore(Request $request)
    {
        $nik = $request->nik;
        $hari = $request->hari;
        $kode_jam_kerja = $request->kode_jam_kerja;

        for ($i = 0; $i < count($hari); $i++){
            $data[] = [
                'nik' => $nik,
                'hari' => $hari[$i],
                'kode_jam_kerja' => $kode_jam_kerja[$i],
                'created_at' => Carbon::now()
            ];
        }

        try {
            JamKerjaKaryawan::insert($data);
            return redirect()->route('admin.karyawan')->with(['success' => 'Jam Kerja Berhasil Disimpan!']);
        } catch (\Throwable $e){
            return redirect()->route('admin.karyawan')->with(['warning' => 'Jam Kerja Gagal Disimpan!']);
        }
    }

    public function KaryawanSettingUpdate(Request $request)
    {
        $nik = $request->nik;
        $hari = $request->hari;
        $kode_jam_kerja = $request->kode_jam_kerja;

        for ($i = 0; $i < count($hari); $i++){
            $data[] = [
                'nik' => $nik,
                'hari' => $hari[$i],
                'kode_jam_kerja' => $kode_jam_kerja[$i],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }

        DB::beginTransaction();

        try {
            DB::table('jam_kerja_karyawan')->where('nik', $nik)->delete();
            JamKerjaKaryawan::insert($data);
            DB::commit();
            return redirect()->route('admin.karyawan')->with(['success' => 'Jam Kerja Berhasil Diupdate!']);
        } catch (\Throwable $e){
            DB::rollBack();
            return redirect()->route('admin.karyawan')->with(['warning' => 'Jam Kerja Gagal Diupdate!']);
        }
    }

    public function KaryawanResetPassword($nik)
    {
        $nik = Crypt::decrypt($nik);
        $password = Hash::make('password123');

        $reset_pass = DB::table('karyawan')
            ->where('nik', $nik)
            ->update([
                'password' => $password
            ]);
        if ($reset_pass) {
            return redirect()->back()->with(['success' => 'Password Berhasil Direset!']);
        } else {
            return redirect()->back()->with(['warning' => 'Password Gagal Direset!']);
        }
    }
}
