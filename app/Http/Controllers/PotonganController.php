<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use App\Models\Jabatan;
use App\Models\KonfigurasiPotongan;
use App\Models\LokasiPenugasan;
use App\Models\Potongan;
use Illuminate\Http\Request;

class PotonganController extends Controller
{
    public function PotonganIndex(Request $request)
    {
        $user = auth()->user();
        // Initialize the query
        $query = Potongan::with('jabatan', 'jenisPotongan')->orderBy('kode_jabatan');

        if ($user->role === 'admin-cabang') {
            $query->where('kode_cabang', $user->kode_cabang);
        }

        // Check for search parameters and apply filters
        if ($request->has('nama_potongan') && $request->nama_potongan != '') {
            $query->where('nama_potongan', 'like', '%' . $request->nama_potongan . '%');
        }

        if ($request->has('kode_jabatan') && $request->kode_jabatan != '') {
            $query->where('kode_jabatan', $request->kode_jabatan);
        }

        if ($request->has('kode_jenis_potongan') && $request->kode_jenis_potongan != '') {
            $query->where('kode_jenis_potongan', $request->kode_jenis_potongan);
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
        $potongan = $query->get();
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
        // $cabang = Cabang::with('LokasiPenugasan')->get();
        $jenis_potongan = KonfigurasiPotongan::all();
        return view('potongan.potongan_index', compact('potongan', 'jabatan', 'jenis_potongan', 'lokasi_penugasan', 'cabang', 'nama_cabang'));
    }

    public function PotonganStore(Request $request)
    {
        try {
            $request->validate([
                'kode_potongan' => 'required|unique:potongan,kode_potongan|max:255',
                'kode_jabatan' => 'required|exists:jabatan,kode_jabatan',
                'kode_lokasi_penugasan' => 'required|exists:lokasi_penugasan,kode_lokasi_penugasan',
                'kode_cabang' => 'required|exists:kantor_cabang,kode_cabang',
                'kode_jenis_potongan' => 'required',
                'nama_potongan' => 'required|max:255',
                'jumlah_potongan' => 'required|numeric',
            ]);

            Potongan::create($request->all());

            return redirect()->back()->with('success', value: 'Potongan berhasil ditambahkan.');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with('error', 'Potongan gagal ditambahkan.');
        }
    }

    public function PotonganUpdate(Request $request, $kode_potongan)
    {
        $kode_potongan = $request->kode_potongan;
        // dd($kode_potongan);
        try {
            $request->validate([
                'kode_jabatan' => 'required|exists:jabatan,kode_jabatan',
                'kode_jenis_potongan' => 'required',
                'kode_lokasi_penugasan' => 'required|exists:lokasi_penugasan,kode_lokasi_penugasan',
                'kode_cabang' => 'required|exists:kantor_cabang,kode_cabang',
                'nama_potongan' => 'required|max:255',
                'jumlah_potongan' => 'required|numeric',
            ]);

            $potongan = Potongan::findOrFail($kode_potongan);
            $potongan->update($request->all());

            return redirect()->back()->with('success', 'Potongan berhasil diupdate.');
        } catch (\Exception $e) {
            // dd($e);
            return redirect()->back()->with('error', 'Potongan gagal diupdate.');
        }
    }

    public function PotonganDelete($kode_potongan)
    {
        try {
            $potongan = Potongan::findOrFail($kode_potongan);
            $potongan->delete();

            return redirect()->back()->with('success', 'Potongan berhasil dihapus.');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with('error', 'Potongan gagal dihapus.');
        }
    }
}
