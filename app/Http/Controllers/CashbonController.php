<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cashbon;
use App\Models\Jabatan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CashbonController extends Controller
{
    public function CashbonIndex(Request $request)
    {
        // Ambil semua parameter pencarian dari request
        $tanggalDari = $request->input('tanggal_dari');
        $tanggalSampai = $request->input('tanggal_sampai');
        $nik = $request->input('nik');
        $namaLengkap = $request->input('nama_lengkap');
        $kodeJabatan = $request->input('kode_jabatan');
        $status = $request->input('status');

        // Query untuk mengambil data cashbon
        $cashbonQuery = Cashbon::with('karyawan');

        // Tambahkan kondisi pencarian jika ada
        if ($tanggalDari && $tanggalSampai) {
            $cashbonQuery->whereBetween('tanggal_pengajuan', [$tanggalDari, $tanggalSampai]);
        }
        if ($nik) {
            $cashbonQuery->whereHas('karyawan', function ($query) use ($nik) {
                $query->where('nik', 'like', '%' . $nik . '%');
            });
        }
        if ($namaLengkap) {
            $cashbonQuery->whereHas('karyawan', function ($query) use ($namaLengkap) {
                $query->where('nama_lengkap', 'like', '%' . $namaLengkap . '%');
            });
        }
        if ($kodeJabatan) {
            $cashbonQuery->whereHas('karyawan', function ($query) use ($kodeJabatan) {
                $query->where('kode_jabatan', $kodeJabatan);
            });
        }
        if ($status) {
            $cashbonQuery->where('status', $status);
        }

        // Ambil hasil query
        $cashbon = $cashbonQuery->get();

        // Ambil data jabatan untuk dropdown
        $jabatan = Jabatan::all();

        return view('cashbon.cashbon_index', compact('cashbon', 'jabatan'));
    }

    public function CashbonShow($id)
    {
        try {
            // Temukan cashbon berdasarkan ID
            $cashbon = Cashbon::with('karyawan')->findOrFail($id); // Menggunakan findOrFail untuk menangkap kesalahan jika tidak ditemukan

            // Kembalikan tampilan dengan data cashbon
            return view('cashbon.cashbon_detail', compact('cashbon'));
        } catch (Exception $e) {
            // Tangkap kesalahan dan redirect dengan pesan kesalahan
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengambil detail cashbon: ' . $e->getMessage());
        }
    }
    public function CashbonPersetujuan(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:cashbon,id', // Pastikan ID cashbon ada
            'status' => 'required|in:diterima,ditolak', // Validasi status
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Temukan cashbon berdasarkan ID
            $cashbon = Cashbon::find($request->id);

            // Update status cashbon
            $cashbon->status = $request->status;
            $cashbon->save();

            // Redirect dengan pesan sukses
            return redirect()->back()->with('success', 'Pengajuan cashbon berhasil diperbarui.');
        } catch (Exception $e) {
            // Tangkap kesalahan dan redirect dengan pesan kesalahan
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui pengajuan cashbon: ' . $e->getMessage());
        }
    }

    public function CashbonPembatalan(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:cashbon,id', // Pastikan ID cashbon ada
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Temukan cashbon berdasarkan ID
            $cashbon = Cashbon::find($request->id);

            // Update status cashbon menjadi pending
            $cashbon->status = 'pending'; // 0 untuk pending
            $cashbon->save();

            // Redirect dengan pesan sukses
            return redirect()->back()->with('success', 'Pengajuan cashbon berhasil dibatalkan.');
        } catch (Exception $e) {
            // Tangkap kesalahan dan redirect dengan pesan kesalahan
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membatalkan pengajuan cashbon: ' . $e->getMessage());
        }
    }
}
