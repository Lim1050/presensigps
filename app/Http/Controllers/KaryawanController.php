<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\Jabatan;
use App\Models\JamKerja;
use App\Models\JamKerjaKaryawan;
use App\Models\Karyawan;
use App\Models\LokasiPenugasan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class KaryawanController extends Controller
{
    // public function KaryawanIndex(Request $request)
    // {
    //     $query = Karyawan::query();

    //     // Mengambil data karyawan, jabatan, dan departemen
    //     $query->select('karyawan.*', 'departemen.nama_departemen', 'jabatan.nama_jabatan');
    //     $query->join('departemen', 'karyawan.kode_departemen', '=', 'departemen.kode_departemen');
    //     $query->leftJoin('jabatan', 'karyawan.kode_jabatan', '=', 'jabatan.kode_jabatan');

    //     // Menyortir berdasarkan nama lengkap
    //     $query->orderBy('nama_lengkap');

    //     // Filter berdasarkan nama karyawan jika diberikan
    //     if(!empty($request->nama_karyawan)){
    //         $query->where('nama_lengkap', 'like', '%' . $request->nama_karyawan . '%');
    //     }

    //     // Filter berdasarkan kode departemen jika diberikan
    //     if(!empty($request->kode_departemen)){
    //         $query->where('karyawan.kode_departemen', $request->kode_departemen);
    //     }

    //     // Filter berdasarkan kode jabatan jika diberikan
    //     if(!empty($request->kode_jabatan)){
    //         $query->where('karyawan.kode_jabatan', $request->kode_jabatan);
    //     }

    //     // Paginate hasil query
    //     $karyawan = $query->get();

    //     // Mengambil semua data jabatan, departemen, dan cabang untuk kebutuhan filter
    //     $jabatan = Jabatan::all();
    //     $departemen = DB::table('departemen')->get();
    //     $cabang = DB::table('kantor_cabang')->orderBy('kode_cabang')->get();
    //     $lokasi_penugasan = DB::table('lokasi_penugasan')->orderBy('kode_lokasi_penugasan')->get();

    //     // Mengembalikan view dengan data karyawan, jabatan, departemen, dan cabang
    //     return view('karyawan.karyawan_index', compact('karyawan', 'jabatan', 'departemen', 'cabang', 'lokasi_penugasan'));
    // }

    public function search(Request $request)
    {
        $search = $request->get('q');
        $karyawan = Karyawan::where('nik', 'LIKE', "%$search%")
                            ->orWhere('nama_lengkap', 'LIKE', "%$search%")
                            ->get();

        return response()->json($karyawan);
    }

    public function getKaryawanData($nik)
    {
        $karyawan = Karyawan::with(['jabatan', 'lokasiPenugasan', 'Cabang'])
                            ->where('nik', $nik)
                            ->first();

        return response()->json([
            'kode_jabatan' => $karyawan->kode_jabatan,
            'nama_jabatan' => $karyawan->jabatan->nama_jabatan,
            'kode_lokasi_penugasan' => $karyawan->kode_lokasi_penugasan,
            'nama_lokasi_penugasan' => $karyawan->lokasiPenugasan->nama_lokasi_penugasan,
            'kode_cabang' => $karyawan->kode_cabang,
            'nama_cabang' => $karyawan->Cabang->nama_cabang
        ]);
    }

    public function getByLokasiPenugasan($kode_lokasi_penugasan)
    {
        $karyawan = Karyawan::where('kode_lokasi_penugasan', $kode_lokasi_penugasan)
            ->select('nik', 'nama_lengkap')
            ->get();

        return response()->json($karyawan);
    }

    public function getJabatan($nik)
    {
        $karyawan = Karyawan::with('jabatan')
            ->where('nik', $nik)
            ->firstOrFail();

        return response()->json([
            'kode_jabatan' => $karyawan->jabatan->kode_jabatan,
            'nama_jabatan' => $karyawan->jabatan->nama_jabatan
        ]);
    }

    public function KaryawanIndex(Request $request)
    {
        $query = Karyawan::with(['departemen', 'jabatan', 'Cabang', 'lokasiPenugasan'])
            ->when($request->nama_karyawan, function ($q) use ($request) {
                return $q->where('nama_lengkap', 'like', '%' . $request->nama_karyawan . '%');
            })
            ->when($request->kode_departemen, function ($q) use ($request) {
                return $q->where('kode_departemen', $request->kode_departemen);
            })
            ->when($request->kode_jabatan, function ($q) use ($request) {
                return $q->where('kode_jabatan', $request->kode_jabatan);
            })
            ->when($request->kode_lokasi_penugasan, function ($q) use ($request) {
                return $q->where('kode_lokasi_penugasan', $request->kode_lokasi_penugasan);
            })
            ->when($request->kode_cabang, function ($q) use ($request) {
                return $q->where('kode_cabang', $request->kode_cabang);
            })
            ->orderBy('nama_lengkap');

        $karyawan = $query->get();

        $jabatan = Jabatan::all();
        $departemen = Departemen::all();
        $cabang = Cabang::orderBy('kode_cabang')->get();
        $lokasi_penugasan = LokasiPenugasan::orderBy('kode_lokasi_penugasan')->get();

        return view('karyawan.karyawan_index', compact('karyawan', 'jabatan', 'departemen', 'cabang', 'lokasi_penugasan'));
    }

    // public function KaryawanStore(Request $request)
    // {
    //     $nik = $request->nik;
    //     $nama_lengkap = $request->nama_lengkap;
    //     $no_wa = $request->no_wa;
    //     $kode_jabatan = $request->kode_jabatan;
    //     $kode_departemen = $request->kode_departemen;
    //     $kode_lokasi_penugasan = $request->kode_lokasi_penugasan;
    //     $kode_cabang = $request->kode_cabang;

    //     // get data karyawan dari table
    //     // cek apakah ada foto dari form
    //     if($request->hasFile('foto')){
    //         $foto = $nik . "_" . time() . "." . $request->file('foto')->getClientOriginalExtension();
    //     } else {
    //         // Jika tidak ada file foto yang diunggah, gunakan foto default
    //         $foto = null;
    //     }

    //     try {
    //         $data = [
    //             'nik' => $nik,
    //             'nama_lengkap' => $nama_lengkap,
    //             'no_wa' => $no_wa,
    //             'password' => Hash::make('password123'),
    //             'kode_jabatan' => $kode_jabatan,
    //             'kode_departemen' => $kode_departemen,
    //             'kode_lokasi_penugasan' => $kode_lokasi_penugasan,
    //             'kode_cabang' => $kode_cabang,
    //             'foto' => $foto,
    //             'created_at' => Carbon::now()
    //         ];
    //         $save = DB::table('karyawan')->insert($data);
    //         if($save){
    //             // save foto ke storage
    //         if($request->hasFile('foto')){
    //             $folderPath = "public/uploads/karyawan/";
    //             $request->file('foto')->storeAs($folderPath, $foto);
    //         }
    //         return redirect()->route('admin.karyawan')->with(['success' => 'Data Berhasil Disimpan!']);
    //         }
    //     } catch (\Exception $e) {
    //         // dd($e->getCode());
    //         if($e->getCode()==23000){
    //             $message = " Data dengan NIK " . $nik . " Sudah ada!";
    //         } else {
    //             $message = " Hubungi Tim IT";
    //         }
    //         return redirect()->back()->with(['error' => 'Data Gagal Disimpan!' . $message]);
    //     }
    // }

    public function KaryawanStore(Request $request)
    {
        $request->validate([
            'nik' => 'required|unique:karyawan,nik',
            'nama_lengkap' => 'required',
            'no_wa' => 'required',
            'kode_jabatan' => 'required',
            'kode_departemen' => 'required',
            'kode_lokasi_penugasan' => 'required',
            'kode_cabang' => 'required',
            'foto' => 'nullable|image|max:2048', // Maksimum 2MB
        ]);

        try {
            DB::transaction(function () use ($request) {
                $foto = null;
                if ($request->hasFile('foto')) {
                    $foto = $request->nik . "_" . time() . "." . $request->file('foto')->getClientOriginalExtension();
                    $request->file('foto')->storeAs('public/uploads/karyawan', $foto);
                }

                Karyawan::create([
                    'nik' => $request->nik,
                    'nama_lengkap' => $request->nama_lengkap,
                    'no_wa' => $request->no_wa,
                    'password' => Hash::make('password123'),
                    'kode_jabatan' => $request->kode_jabatan,
                    'kode_departemen' => $request->kode_departemen,
                    'kode_lokasi_penugasan' => $request->kode_lokasi_penugasan,
                    'kode_cabang' => $request->kode_cabang,
                    'foto' => $foto,
                ]);
            });

            return redirect()->route('admin.karyawan')->with('success', 'Data Berhasil Disimpan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Data Gagal Disimpan: ' . $e->getMessage());
        }
    }

    public function KaryawanEdit(Request $request, $nik)
    {
        $nik = Crypt::decrypt($request->nik);
        $jabatan = DB::table('jabatan')->get();
        $departemen = DB::table('departemen')->get();
        $cabang = DB::table('kantor_cabang')->orderBy('kode_cabang')->get();
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();
        return view('karyawan.karyawan_edit', compact('nik', 'departemen', 'karyawan', 'cabang', 'jabatan'));
    }

    public function KaryawanUpdate(Request $request, $nik)
    {
        $nik = $request->nik;
        $nama_lengkap = $request->nama_lengkap;
        $no_wa = $request->no_wa;
        $kode_jabatan = $request->kode_jabatan;
        $kode_departemen = $request->kode_departemen;
        $kode_cabang = $request->kode_cabang;
        $kode_lokasi_penugasan = $request->kode_lokasi_penugasan;

        $karyawan = Karyawan::findOrFail($nik);

        $old_foto = $karyawan->foto;

        // cek apakah ada foto dari form
        if($request->hasFile('foto')){
            $foto = $nik . "_" . time() . "." . $request->file('foto')->getClientOriginalExtension();
        } else {
            $foto = $old_foto;
        }

        try {
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'no_wa' => $no_wa,
                // 'password' => Hash::make('password123'),
                'kode_jabatan' => $kode_jabatan,
                'kode_departemen' => $kode_departemen,
                'kode_lokasi_penugasan' => $kode_lokasi_penugasan,
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
        $nik = Crypt::decrypt($nik);
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
        $nik = Crypt::decrypt($request->nik);
        $karyawan = Karyawan::with(['lokasiPenugasan', 'Cabang'])
                        ->where('nik', $nik)
                        ->first();
        $jam_kerja = JamKerja::where('kode_lokasi_penugasan', $karyawan->kode_lokasi_penugasan)
                            ->where('kode_cabang', $karyawan->kode_cabang)
                            ->orderBy('kode_jam_kerja')
                            ->get();
        $cek_jam_kerja_karyawan = JamKerjaKaryawan::where('nik', $nik)->count();

        if($cek_jam_kerja_karyawan > 0 ) {
            $jam_kerja_karyawan = JamKerjaKaryawan::where('nik', $nik)->get();
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
