<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use App\Models\Gaji;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\KonfigurasiGaji;
use App\Models\LokasiPenugasan;
use App\Models\presensi;
use Illuminate\Http\Request;

class GajiController extends Controller
{


    public function GajiIndex()
    {
        $gaji = Gaji::with('jabatan', 'jenisGaji')->orderBy('kode_jabatan')->get();
        $jabatan = Jabatan::all();
        $lokasi_penugasan = LokasiPenugasan::with('cabang')->get();
        $cabang = Cabang::with('LokasiPenugasan')->get();
        $jenis_gaji = KonfigurasiGaji::all();
        return view('gaji.gaji_index', compact('gaji', 'jabatan', 'jenis_gaji', 'lokasi_penugasan', 'cabang'));
    }

    public function GajiStore(Request $request)
    {
        try {
            $request->validate([
                'kode_gaji' => 'required|unique:gaji,kode_gaji|max:255',
                'kode_jabatan' => 'required|exists:jabatan,kode_jabatan',
                'kode_lokasi_penugasan' => 'required|exists:lokasi_penugasan,kode_lokasi_penugasan',
                'kode_cabang' => 'required|exists:kantor_cabang,kode_cabang',
                'kode_jenis_gaji' => 'required',
                'nama_gaji' => 'required|max:255',
                'jumlah_gaji' => 'required|numeric',
            ]);

            Gaji::create($request->all());

            return redirect()->back()->with('success', 'Gaji berhasil ditambahkan.');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with('error', 'Gaji gagal ditambahkan.');
        }
    }

    public function GajiUpdate(Request $request, $kode_gaji)
    {
        $kode_gaji = $request->kode_gaji;
        // dd($kode_gaji);
        try {
            $request->validate([
                'kode_jabatan' => 'required|exists:jabatan,kode_jabatan',
                'kode_jenis_gaji' => 'required',
                'kode_lokasi_penugasan' => 'required|exists:lokasi_penugasan,kode_lokasi_penugasan',
                'kode_cabang' => 'required|exists:kantor_cabang,kode_cabang',
                'nama_gaji' => 'required|max:255',
                'jumlah_gaji' => 'required|numeric',
            ]);

            $gaji = Gaji::findOrFail($kode_gaji);
            $gaji->update($request->all());

            return redirect()->back()->with('success', 'Gaji berhasil diupdate.');
        } catch (\Exception $e) {
            // dd($e);
            return redirect()->back()->with('error', 'Gaji gagal diupdate.');
        }
    }

    public function GajiDelete($kode_gaji)
    {
        try {
            $gaji = Gaji::findOrFail($kode_gaji);
            $gaji->delete();

            return redirect()->back()->with('success', 'Gaji berhasil dihapus.');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with('error', 'Gaji gagal dihapus.');
        }
    }
}
