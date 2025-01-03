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


    public function GajiIndex(Request $request)
    {
        $user = auth()->user();
        // Initialize the query
        $query = Gaji::with('jabatan', 'jenisGaji')->orderBy('kode_jabatan');

        if ($user->role === 'admin-cabang') {
            $query->where('kode_cabang', $user->kode_cabang);
        }

        // Check for search parameters and apply filters
        if ($request->has('nama_gaji') && $request->nama_gaji != '') {
            $query->where('nama_gaji', 'like', '%' . $request->nama_gaji . '%');
        }

        if ($request->has('kode_jabatan') && $request->kode_jabatan != '') {
            $query->where('kode_jabatan', $request->kode_jabatan);
        }

        if ($request->has('kode_jenis_gaji') && $request->kode_jenis_gaji != '') {
            $query->where('kode_jenis_gaji', $request->kode_jenis_gaji);
        }

        if ($request->has('kode_cabang') && $request->kode_cabang != '') {
            $query->whereHas('lokasiPenugasan', function($q) use ($request) {
                $q->where('kode_cabang', $request->kode_cabang);
            });
        }

        if ($request->has('kode_lokasi_penugasan') && $request->kode_lokasi_penugasan != '') {
            $query->where('kode_lokasi_penugasan', $request->kode_lokasi_penugasan);
        }

        // Execute the query
        $gaji = $query->get();

        // Fetch additional data for the view
        $jabatan = Jabatan::all();


        if ($user->role === 'admin-cabang') {
            $cabang = Cabang::with('lokasiPenugasan')->where('kode_cabang', $user->kode_cabang)->orderBy('kode_cabang')->get();
            $lokasi_penugasan = LokasiPenugasan::with('cabang')->where('kode_cabang', $user->kode_cabang)->get();
            $nama_cabang = Cabang::where('kode_cabang', $user->kode_cabang)->first();
            // dd($nama_cabang);
        } else {
            $cabang = Cabang::with('lokasiPenugasan')->orderBy('kode_cabang')->get();
            $lokasi_penugasan = LokasiPenugasan::with('cabang')->get();
            $nama_cabang = null;
        }
        // $cabang = Cabang::with('lokasiPenugasan')->get();
        $jenis_gaji = KonfigurasiGaji::all();

        // Return the view with the filtered data
        return view('gaji.gaji_index', compact('gaji', 'jabatan', 'jenis_gaji', 'lokasi_penugasan', 'cabang', 'nama_cabang'));
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
