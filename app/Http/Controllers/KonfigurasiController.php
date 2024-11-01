<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JamKerjaDeptDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $jam_kerja = DB::table('jam_kerja')->orderBy('kode_jam_kerja')->get();
        // dd($jam_kerja);
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
        $lintas_hari = $request->lintas_hari;

        try {
            $data = [
                'kode_jam_kerja' => $kode_jam_kerja,
                'nama_jam_kerja' => $nama_jam_kerja,
                'awal_jam_masuk' => $awal_jam_masuk,
                'jam_masuk' => $jam_masuk,
                'akhir_jam_masuk' => $akhir_jam_masuk,
                'jam_pulang' => $jam_pulang,
                'lintas_hari' => $lintas_hari,
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
        $lintas_hari = $request->lintas_hari;

        try {
            $data = [
                'nama_jam_kerja' => $nama_jam_kerja,
                'awal_jam_masuk' => $awal_jam_masuk,
                'jam_masuk' => $jam_masuk,
                'akhir_jam_masuk' => $akhir_jam_masuk,
                'jam_pulang' => $jam_pulang,
                'lintas_hari' => $lintas_hari,
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
