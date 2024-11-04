<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use App\Models\JamKerja;
use App\Models\JamKerjaDeptDetail;
use App\Models\LokasiPenugasan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class KonfigurasiController extends Controller
{
    // public function LokasiKantor()
    // {
    //     $lokasi_kantor = DB::table('lokasi_kantor')->where('id', 1)->first();
    //     return view('konfigurasi.lokasi_kantor', compact('lokasi_kantor'));
    // }

    // public function UpdateLokasiKantor(Request $request)
    // {
    //     $lokasi_kantor = $request->lokasi_kantor;
    //     $radius = $request->radius;

    //     $update = DB::table('lokasi_kantor')->where('id', 1)->update([
    //         'lokasi_kantor' => $lokasi_kantor,
    //         'radius' => $radius
    //     ]);

    //     if($update){
    //         return redirect()->back()->with(['success' => 'Data Berhasil Diupdate!']);
    //     } else {
    //         return redirect()->back()->with(['warning' => 'Data Gagal Diupdate!']);
    //     }
    // }

    public function JamKerja()
    {
        // Mengambil semua jam kerja dan mengurutkannya berdasarkan kode_jam_kerja
        $jam_kerja = JamKerja::orderBy('kode_jam_kerja')->get();
        $lokasiPenugasan = LokasiPenugasan::all();
        $cabang = Cabang::all();

        // Mengembalikan view dengan data jam kerja
        return view('konfigurasi.jam_kerja', compact('jam_kerja', 'lokasiPenugasan', 'cabang'));
    }

    public function JamKerjaStore(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            // 'kode_jam_kerja' => 'required|unique:jam_kerja,kode_jam_kerja',
            'kode_lokasi_penugasan' => 'required|exists:lokasi_penugasan,kode_lokasi_penugasan',
            'kode_cabang' => 'required|exists:kantor_cabang,kode_cabang',
            'nama_jam_kerja' => 'required',
            'awal_jam_masuk' => 'required|date_format:H:i',
            'jam_masuk' => 'required|date_format:H:i',
            'akhir_jam_masuk' => 'required|date_format:H:i',
            'jam_pulang' => 'required|date_format:H:i',
            'lintas_hari' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
        }

        try {
            DB::beginTransaction();

            $kode_lokasi_penugasan = $request->kode_lokasi_penugasan; // Misalkan ada field 'kode' di tabel lokasi_penugasan
            $kode_cabang = $request->kode_cabang; // Misalkan ada field 'kode' di tabel cabang

            // Ambil huruf pertama dari setiap kata di nama_jam_kerja
            $nama_jam_kerja = $request->nama_jam_kerja;
            $inisial_nama_jam_kerja = strtoupper(implode('', array_map(function($word) {
                return substr($word, 0, 1);
            }, explode(' ', $nama_jam_kerja))));

            // Generate kode_jam_kerja
            $kode_jam_kerja = 'JK' . $kode_lokasi_penugasan . $kode_cabang . $inisial_nama_jam_kerja;

            // Cek apakah kode_jam_kerja sudah ada
            if (JamKerja::where('kode_jam_kerja', $kode_jam_kerja)->exists()) {
                return redirect()->back()->with(['error' => 'Kode Jam Kerja sudah ada!'])->withInput();
            }

            // Simpan data
            JamKerja::create([
                'kode_jam_kerja' => $kode_jam_kerja,
                'kode_lokasi_penugasan' => $request->kode_lokasi_penugasan,
                'kode_cabang' => $request->kode_cabang,
                'nama_jam_kerja' => $request->nama_jam_kerja,
                'awal_jam_masuk' => $request->awal_jam_masuk,
                'jam_masuk' => $request->jam_masuk,
                'akhir_jam_masuk' => $request->akhir_jam_masuk,
                'jam_pulang' => $request->jam_pulang,
                'lintas_hari' => $request->lintas_hari,
            ]);

            DB::commit();

            return redirect()->route('admin.konfigurasi.jam.kerja')
                            ->with('success', 'Data Jam Kerja berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat menyimpan Jam Kerja: ' . $e->getMessage());
            return redirect()->back()
                            ->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.')
                            ->withInput();
        }
    }

    public function JamKerjaUpdate(Request $request, $kode_jam_kerja)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'kode_lokasi_penugasan' => 'required|exists:lokasi_penugasan,kode_lokasi_penugasan',
            'kode_cabang' => 'required|exists:kantor_cabang,kode_cabang',
            'nama_jam_kerja' => 'required',
            'awal_jam_masuk' => 'required',
            'jam_masuk' => 'required',
            'akhir_jam_masuk' => 'required',
            'jam_pulang' => 'required',
            'lintas_hari' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
        }

        try {
            DB::beginTransaction();

            // Cari data jam kerja berdasarkan kode
            $jamKerja = JamKerja::where('kode_jam_kerja', $kode_jam_kerja)->first();

            if (!$jamKerja) {
                return redirect()->back()
                                ->with('error', 'Data Jam Kerja tidak ditemukan.')
                                ->withInput();
            }

            // Menyiapkan data untuk update
            $dataUpdate = [
                'kode_lokasi_penugasan' => $request->kode_lokasi_penugasan,
                'kode_cabang' => $request->kode_cabang,
                'nama_jam_kerja' => $request->nama_jam_kerja,
                'awal_jam_masuk' => $request->awal_jam_masuk,
                'jam_masuk' => $request->jam_masuk,
                'akhir_jam_masuk' => $request->akhir_jam_masuk,
                'jam_pulang' => $request->jam_pulang,
                'lintas_hari' => $request->lintas_hari,
            ];

            // Cek apakah ada perubahan data
            $hasChanges = false;
            foreach ($dataUpdate as $key => $value) {
                if ($jamKerja->$key != $value) {
                    $hasChanges = true;
                    break;
                }
            }

            if ($hasChanges) {
                // Update data jika ada perubahan
                $jamKerja->update($dataUpdate);
                DB::commit();

                return redirect()->route('admin.konfigurasi.jam.kerja')
                                ->with('success', 'Data Jam Kerja berhasil diperbarui!');
            } else {
                DB::rollBack();
                return redirect()->back()
                                ->with('info', 'Tidak ada perubahan pada data Jam Kerja.')
                                ->withInput();
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat memperbarui Jam Kerja: ' . $e->getMessage());

            return redirect()->back()
                            ->with('error', 'Terjadi kesalahan saat memperbarui data. Silakan coba lagi.')
                            ->withInput();
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

    public function JamKerjaDept()
    {
        $jam_kerja_dept = DB::table('jam_kerja_dept')
                            ->join('kantor_cabang', 'jam_kerja_dept.kode_cabang', '=', 'kantor_cabang.kode_cabang')
                            ->join('departemen', 'jam_kerja_dept.kode_departemen', '=', 'departemen.kode_departemen')
                            ->get();

        $jam_kerja_dept_det = DB::table('jam_kerja_dept_detail')->get();

        return view('konfigurasi.jam_kerja_dept', compact('jam_kerja_dept'));
    }
    public function JamKerjaDeptCreate()
    {
        $jam_kerja = DB::table('jam_kerja')->orderBy('kode_jam_kerja')->get();
        $cabang = DB::table('kantor_cabang')->orderBy('nama_cabang')->get();
        $departemen = DB::table('departemen')->orderBy('nama_departemen')->get();
        return view('konfigurasi.jam_kerja_dept_create', compact('jam_kerja', 'cabang', 'departemen'));
    }

    public function JamKerjaDeptStore(Request $request)
    {
        $kode_cabang = $request->kode_cabang;
        $kode_departemen = $request->kode_departemen;
        $hari = $request->hari;
        $kode_jam_kerja = $request->kode_jam_kerja;
        $kode_jk_dept = "J" . $kode_cabang . $kode_departemen;

        DB::beginTransaction();
        try {
            //menyimpan data ke table jam_kerja_dept
            DB::table('jam_kerja_dept')->insert([
                'kode_jk_dept' => $kode_jk_dept,
                'kode_cabang' => $kode_cabang,
                'kode_departemen' => $kode_departemen,
                'created_at' => Carbon::now()
            ]);

            //menyimpan data ke table jam_kerja_dept_detail
            for ($i = 0; $i < count($hari); $i++){
                $data[] = [
                    'kode_jk_dept' => $kode_jk_dept,
                    'hari' => $hari[$i],
                    'kode_jam_kerja' => $kode_jam_kerja[$i],
                    'created_at' => Carbon::now()
                ];
            }
            JamKerjaDeptDetail::insert($data);

            DB::commit();

            return redirect()->route('admin.konfigurasi.jam-kerja-dept')->with(['success' => 'Jam Kerja Berhasil Disimpan!']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->route('admin.konfigurasi.jam-kerja-dept')->with(['warning' => 'Jam Kerja Gagal Disimpan!']);
        }
    }

    public function JamKerjaDeptView($kode_jk_dept)
    {
        $jam_kerja_dept = DB::table('jam_kerja_dept')
                            ->join('kantor_cabang', 'jam_kerja_dept.kode_cabang', '=', 'kantor_cabang.kode_cabang')
                            ->join('departemen', 'jam_kerja_dept.kode_departemen', '=', 'departemen.kode_departemen')
                            ->where('kode_jk_dept', $kode_jk_dept)
                            ->first();
        $jam_kerja_dept_detail = DB::table('jam_kerja_dept_detail')
                                ->join('jam_kerja', 'jam_kerja_dept_detail.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
                                ->where('kode_jk_dept', $kode_jk_dept)
                                ->get();

        $jam_kerja = DB::table('jam_kerja')->orderBy('kode_jam_kerja')->get();
        $cabang = DB::table('kantor_cabang')->orderBy('nama_cabang')->get();
        $departemen = DB::table('departemen')->orderBy('nama_departemen')->get();
        return view('konfigurasi.jam_kerja_dept_view', compact('jam_kerja', 'cabang', 'departemen', 'jam_kerja_dept', 'jam_kerja_dept_detail'));
    }
    public function JamKerjaDeptEdit($kode_jk_dept)
    {
        $jam_kerja_dept = DB::table('jam_kerja_dept')->where('kode_jk_dept', $kode_jk_dept)->first();
        // dd($jam_kerja_dept);
        $jam_kerja_dept_detail = DB::table('jam_kerja_dept_detail')->where('kode_jk_dept', $kode_jk_dept)->get();

        $jam_kerja = DB::table('jam_kerja')->orderBy('kode_jam_kerja')->get();
        $cabang = DB::table('kantor_cabang')->orderBy('nama_cabang')->get();
        $departemen = DB::table('departemen')->orderBy('nama_departemen')->get();
        return view('konfigurasi.jam_kerja_dept_edit', compact('jam_kerja', 'cabang', 'departemen', 'jam_kerja_dept', 'jam_kerja_dept_detail'));
    }

    public function JamKerjaDeptUpdate( $kode_jk_dept ,Request $request)
    {
        $hari = $request->hari;
        $kode_jam_kerja = $request->kode_jam_kerja;

        DB::beginTransaction();
        try {
            // delete data yg lama
            DB::table('jam_kerja_dept_detail')->where('kode_jk_dept', $kode_jk_dept)->delete();

            //update data ke table jam_kerja_dept_detail
            for ($i = 0; $i < count($hari); $i++){
                $data[] = [
                    'kode_jk_dept' => $kode_jk_dept,
                    'hari' => $hari[$i],
                    'kode_jam_kerja' => $kode_jam_kerja[$i],
                    'updated_at' => Carbon::now()
                ];
            }
            JamKerjaDeptDetail::insert($data);

            DB::commit();

            return redirect()->route('admin.konfigurasi.jam-kerja-dept')->with(['success' => 'Jam Kerja Berhasil Diupdate!']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->route('admin.konfigurasi.jam-kerja-dept')->with(['warning' => 'Jam Kerja Gagal Diupdate!']);
        }
    }

    public function JamKerjaDeptDelete($kode_jk_dept)
    {
        try {
            // Hapus data dari table jam_kerja_dept_detail
            DB::table('jam_kerja_dept_detail')->where('kode_jk_dept', $kode_jk_dept)->delete();
            // Hapus data dari table jam_kerja_dept
            DB::table('jam_kerja_dept')->where('kode_jk_dept', $kode_jk_dept)->delete();
            return redirect()->back()->with(['success' => 'Data Berhasil Dihapus!']);
        } catch (\Throwable $th) {
            return redirect()->back()->with(['warning' => 'Data Gagal Dihapus!']);
        }

    }
}
