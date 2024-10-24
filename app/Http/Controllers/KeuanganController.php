<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cashbon;
use App\Models\Penggajian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KeuanganController extends Controller
{
    public function KeuanganIndex()
    {
        return view('keuangan.keuangan_index');
    }
    public function KeuanganGaji()
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $gaji = Penggajian::where('nik', $nik)->get();
        return view('keuangan.keuangan_gaji', compact('gaji'));
    }
    public function KeuanganCashbon()
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $cashbon = Cashbon::where('nik', $nik)->get();
        return view('keuangan.keuangan_cashbon', compact('cashbon'));
    }

    public function KeuanganCashbonShow($id)
    {
        try {
            // Temukan cashbon berdasarkan ID
            $cashbon = Cashbon::with('karyawan')->findOrFail($id); // Menggunakan findOrFail untuk menangkap kesalahan jika tidak ditemukan

            // Kembalikan tampilan dengan data cashbon
            return view('keuangan.keuangan_cashbon_detail', compact('cashbon'));
        } catch (\Exception $e) {
            // Tangkap kesalahan dan redirect dengan pesan kesalahan
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengambil detail cashbon: ' . $e->getMessage());
        }
    }

    public function KeuanganCashbonCreate()
    {
        return view('keuangan.keuangan_cashbon_create');
    }

    public function KeuanganCashbonStore(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'tanggal_pengajuan' => 'required|date',
                'jumlah' => 'required|numeric',
                'keterangan' => 'nullable|string',
            ]);

            $nik = Auth::guard('karyawan')->user()->nik;

            // Cek apakah sudah ada pengajuan cashbon pada tanggal yang sama untuk NIK yang sama
            $existingCashbon = Cashbon::where('nik', $nik)
                ->whereDate('tanggal_pengajuan', $request->tanggal_pengajuan)
                ->first();

            if ($existingCashbon) {
                return redirect()->back()->with('error', 'Anda sudah mengajukan cashbon pada tanggal yang sama.')->withInput();
            }

            // Generate kode cashbon
            $kode_cashbon = $this->generateKodeCashbon();

            // Menyimpan data cashbon
            DB::beginTransaction();
            Cashbon::create([
                'kode_cashbon' => $kode_cashbon,
                'nik' => $nik,
                'tanggal_pengajuan' => $request->tanggal_pengajuan,
                'jumlah' => $request->jumlah,
                'keterangan' => $request->keterangan,
                'status' => 'pending', // Status default
            ]);
            DB::commit();

            // Redirect dengan pesan sukses
            return redirect()->route('keuangan.cashbon')->with('success', 'Pengajuan cashbon berhasil!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    private function generateKodeCashbon()
    {
        $randomString = strtoupper(string: Str::random(3)) . rand(100, 999); // 3 huruf kapital dan 3 angka
        return 'CB' . $randomString; // Menggabungkan dengan 'CB'
    }
}
