<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cashbon;
use App\Models\CashbonLimit;
use App\Models\Penggajian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        // dd($cashbon);
        // Log::info('NIK Karyawan: ' . $nik);
        // Log::info('Data Cashbon: ', $cashbon->toArray());
        // Log::info('Session Error: ', session()->all());
        return view('keuangan.keuangan_cashbon', compact('cashbon'));
    }

    public function KeuanganCashbonShow($id)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $cashbon = Cashbon::where('nik', $nik)->find($id);
        return view('keuangan.keuangan_cashbon_detail', compact('cashbon'));
    }

    public function KeuanganCashbonCreate()
    {
        $karyawan = Auth::guard('karyawan')->user();
        $globalLimit = CashbonLimit::first()->global_limit ?? 0;

        $personalLimit = $karyawan->cashbonKaryawanLimit->limit ?? $globalLimit;
        $usedCashbon = $karyawan->cashbon()
                                ->where('status', 'diterima')
                                ->sum('jumlah');
        $availableLimit = max(0, $personalLimit - $usedCashbon);

        $data = [
            'totalLimit' => $personalLimit,
            'usedLimit' => $usedCashbon,
            'availableLimit' => $availableLimit
        ];

        return view('keuangan.keuangan_cashbon_create', $data);
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

            $karyawan = Auth::guard('karyawan')->user();
            $nik = $karyawan->nik;

            // Cek apakah sudah ada pengajuan cashbon pada tanggal yang sama untuk NIK yang sama
            $existingCashbon = Cashbon::where('nik', $nik)
                ->whereDate('tanggal_pengajuan', $request->tanggal_pengajuan)
                ->first();

            if ($existingCashbon) {
                return redirect()->back()->with('error', 'Anda sudah mengajukan cashbon pada tanggal yang sama.')->withInput();
            }

            // Ambil global limit
            $globalLimit = CashbonLimit::first()->global_limit ?? 0;

            // Cek limit cashbon
            $availableLimit = $karyawan->getAvailableCashbonLimit($globalLimit);

            if ($request->jumlah > $availableLimit) {
                return redirect()->back()->with('error', "Pengajuan melebihi limit yang tersedia. Limit tersedia: Rp " . number_format($availableLimit, 0, ',', '.'))->withInput();
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
