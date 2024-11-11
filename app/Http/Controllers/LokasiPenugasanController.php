<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use App\Models\LokasiPenugasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LokasiPenugasanController extends Controller
{
    public function search(Request $request)
    {
        $search = $request->get('q');
        $lokasi_penugasan = LokasiPenugasan::where('kode_lokasi_penugasan', 'LIKE', "%$search%")
                        ->orWhere('nama_lokasi_penugasan', 'LIKE', "%$search%")
                        ->get();

        return response()->json($lokasi_penugasan);
    }

    public function getByKodeCabang($kode_cabang)
    {
        $lokasiPenugasan = LokasiPenugasan::where('kode_cabang', $kode_cabang)
            ->select('kode_lokasi_penugasan', 'nama_lokasi_penugasan')
            ->get();

        return response()->json($lokasiPenugasan);
    }

    public function LokasiPenugasanIndex(Request $request)
    {
        $query = LokasiPenugasan::with('cabang');

        // Pencarian berdasarkan nama lokasi penugasan
        if ($request->filled('nama_lokasi_penugasan')) {
            $query->where('nama_lokasi_penugasan', 'like', '%' . $request->nama_lokasi_penugasan . '%');
        }

        // Pencarian berdasarkan jumlah jam kerja
        if ($request->filled('jumlah_jam_kerja')) {
            $query->where('jumlah_jam_kerja', $request->jumlah_jam_kerja);
        }

        // Pencarian berdasarkan jumlah hari kerja
        if ($request->filled('jumlah_hari_kerja')) {
            $query->where('jumlah_hari_kerja', $request->jumlah_hari_kerja);
        }

        // Pencarian berdasarkan cabang
        if ($request->filled('kode_cabang')) {
            $query->where('kode_cabang', $request->kode_cabang);
        }

        // Ambil data lokasi penugasan dengan pagination
        $lokasi_penugasan = $query->paginate(10); // Ganti 10 dengan jumlah item per halaman yang diinginkan

        // Ambil data cabang untuk dropdown
        $cabang = Cabang::all();

        return view('lokasi_penugasan.lokasi_penugasan_index', compact('lokasi_penugasan', 'cabang'));
    }

    public function LokasiPenugasanStore(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'kode_lokasi_penugasan' => 'required|unique:lokasi_penugasan,kode_lokasi_penugasan',
            'nama_lokasi_penugasan' => 'required|string|max:255',
            'lokasi_penugasan' => 'required|string',
            'radius' => 'required|numeric',
            'jumlah_jam_kerja' => 'required|numeric',
            'jumlah_hari_kerja' => 'required|numeric',
            'kode_cabang' => 'required|exists:kantor_cabang,kode_cabang',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Buat instance LokasiPenugasan baru
            $lokasiPenugasan = new LokasiPenugasan();
            $lokasiPenugasan->kode_lokasi_penugasan = $request->kode_lokasi_penugasan;
            $lokasiPenugasan->nama_lokasi_penugasan = $request->nama_lokasi_penugasan;
            $lokasiPenugasan->lokasi_penugasan = $request->lokasi_penugasan;
            $lokasiPenugasan->radius = $request->radius;
            $lokasiPenugasan->jumlah_jam_kerja = $request->jumlah_jam_kerja;
            $lokasiPenugasan->jumlah_hari_kerja = $request->jumlah_hari_kerja;
            $lokasiPenugasan->kode_cabang = $request->kode_cabang;

            // Simpan ke database
            $lokasiPenugasan->save();

            // Redirect dengan pesan sukses
            return redirect()
                ->back()
                ->with('success', 'Lokasi Penugasan berhasil ditambahkan.');
        } catch (\Exception $e) {
            // Jika terjadi error, redirect kembali dengan pesan error
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function LokasiPenugasanEdit($kode_lokasi_penugasan)
    {
        $lokasi_penugasan = LokasiPenugasan::findOrFail($kode_lokasi_penugasan);
        $cabang = Cabang::all();

        return view('lokasi_penugasan.lokasi_penugasan_edit', compact('lokasi_penugasan', 'cabang'));
    }

    public function LokasiPenugasanUpdate(Request $request, $kode_lokasi_penugasan)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nama_lokasi_penugasan' => 'required|string|max:255',
            'lokasi_penugasan' => 'required|string',
            'radius' => 'required|numeric',
            'jumlah_jam_kerja' => 'required|numeric',
            'jumlah_hari_kerja' => 'required|numeric',
            'kode_cabang' => 'required|exists:kantor_cabang,kode_cabang',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Ambil data lokasi penugasan yang akan diupdate
            $lokasi_penugasan = LokasiPenugasan::findOrFail($kode_lokasi_penugasan);

            // Update data hanya jika ada perubahan
            $lokasi_penugasan->nama_lokasi_penugasan = $request->nama_lokasi_penugasan ?? $lokasi_penugasan->nama_lokasi_penugasan;
            $lokasi_penugasan->lokasi_penugasan = $request->lokasi_penugasan ?? $lokasi_penugasan->lokasi_penugasan;
            $lokasi_penugasan->radius = $request->radius ?? $lokasi_penugasan->radius;
            $lokasi_penugasan->jumlah_jam_kerja = $request->jumlah_jam_kerja ?? $lokasi_penugasan->jumlah_jam_kerja;
            $lokasi_penugasan->jumlah_hari_kerja = $request->jumlah_hari_kerja ?? $lokasi_penugasan->jumlah_hari_kerja;
            $lokasi_penugasan->kode_cabang = $request->kode_cabang ?? $lokasi_penugasan->kode_cabang;

            // Cek apakah ada perubahan sebelum menyimpan
            if ($lokasi_penugasan->isDirty()) {
                $lokasi_penugasan->save();
                $message = 'Lokasi Penugasan berhasil diperbarui.';
            } else {
                $message = 'Tidak ada perubahan pada Lokasi Penugasan.';
            }

            return redirect()
                ->route('admin.lokasi.penugasan')
                ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function LokasiPenugasanDelete($kode_lokasi_penugasan)
    {
        try {
            $lokasiPenugasan = LokasiPenugasan::findOrFail($kode_lokasi_penugasan);
            $lokasiPenugasan->delete();

            return response()->json(['success' => true, 'message' => 'Lokasi Penugasan berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus Lokasi Penugasan: ' . $e->getMessage()], 500);
        }
    }
}
