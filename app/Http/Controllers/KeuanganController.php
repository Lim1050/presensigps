<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cashbon;
use App\Models\CashbonLimit;
use App\Models\Penggajian;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
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
    public function KeuanganGaji(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $query = Penggajian::with(['karyawan', 'cabang', 'lokasiPenugasan'])
                ->where('nik', $nik)
                ->where('status', 'dibayar');

        // Filter berdasarkan bulan dan tahun jika ada
        if ($request->filled('bulan') && $request->filled('tahun')) {
            $query->whereYear('tanggal_gaji', $request->tahun)
                ->whereMonth('tanggal_gaji', $request->bulan);
        }
        // Filter hanya berdasarkan tahun
        elseif ($request->filled('tahun')) {
            $query->whereYear('tanggal_gaji', $request->tahun);
        }
        // Filter hanya berdasarkan bulan
        elseif ($request->filled('bulan')) {
            $query->whereMonth('tanggal_gaji', $request->bulan);
        }

        $gaji = $query->orderBy('tanggal_gaji', 'desc')->get();

        return view('keuangan.keuangan_gaji', compact('gaji'));
    }

    public function KeuanganGajiExport($kode_penggajian)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $penggajian = Penggajian::with(['karyawan', 'cabang', 'lokasiPenugasan'])
                ->where('nik', $nik)->find($kode_penggajian);
        // Decode komponen gaji dan potongan dari JSON
        $komponenGajiKotor = json_decode($penggajian->komponen_gaji_kotor, true);
        $komponenGaji = json_decode($penggajian->komponen_gaji, true);
        $komponenPotongan = json_decode($penggajian->komponen_potongan, true);


        $imagePath = public_path('assets/img/MASTER-LOGO-PT-GUARD-500-500.png');
        $imageData = base64_encode(file_get_contents($imagePath));
        $src = 'data:image/png;base64,' . $imageData;

        // Mengoper data ke pdf
        $pdf = Pdf::loadView('keuangan.keuangan_gaji_export', compact('penggajian', 'komponenGajiKotor', 'komponenGaji', 'komponenPotongan', 'src'))
                    ->setPaper('A4', 'portrait')
                    ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        return $pdf->download(filename: 'Gaji_' . $penggajian->kode_penggajian . '_' . $penggajian->karyawan->nama_lengkap . '_' . $penggajian->bulan . '.pdf');
    }

    public function KeuanganCashbon(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $query = Cashbon::where('nik', $nik);

        // Filter berdasarkan bulan dan tahun jika ada
        if ($request->filled('bulan') && $request->filled('tahun')) {
            $query->whereYear('tanggal_pengajuan', $request->tahun)
                ->whereMonth('tanggal_pengajuan', $request->bulan);
        }
        // Filter hanya berdasarkan tahun
        elseif ($request->filled('tahun')) {
            $query->whereYear('tanggal_pengajuan', $request->tahun);
        }
        // Filter hanya berdasarkan bulan
        elseif ($request->filled('bulan')) {
            $query->whereMonth('tanggal_pengajuan', $request->bulan);
        }

        $cashbon = $query->orderBy('tanggal_pengajuan', 'desc')->get();

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

        // Ambil bulan saat ini
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $personalLimit = $karyawan->cashbonKaryawanLimit->limit ?? $globalLimit;
        $usedCashbon = $karyawan->cashbon()
                                ->where('status', 'diterima')
                                ->whereMonth('tanggal_pengajuan', $currentMonth)
                                ->whereYear('tanggal_pengajuan', $currentYear)
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

    public function KeuanganCashbonEdit($id)
    {
        $cashbon = Cashbon::findOrFail($id);
        return view('keuangan.keuangan_cashbon_edit', compact('cashbon'));
    }

    public function KeuanganCashbonUpdate(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'tanggal_pengajuan' => 'required|date',
            'jumlah' => 'required|numeric|min:0',
            'keterangan' => 'required|string',
        ]);

        try {
            $cashbon = Cashbon::findOrFail($id);

            // Cek apakah status masih pending
            if ($cashbon->status !== 'pending') {
                return redirect()->back()->with('error', 'Hanya cashbon dengan status pending yang dapat diubah.');
            }

            // Cek dan update data hanya jika ada perubahan
            $updated = false; // Flag untuk mengecek apakah ada perubahan

            if ($cashbon->tanggal_pengajuan != $request->tanggal_pengajuan) {
                $cashbon->tanggal_pengajuan = $request->tanggal_pengajuan;
                $updated = true;
            }

            if ($cashbon->jumlah != $request->jumlah) {
                $cashbon->jumlah = $request->jumlah;
                $updated = true;
            }

            if ($cashbon->keterangan != $request->keterangan) {
                $cashbon->keterangan = $request->keterangan;
                $updated = true;
            }

            // Simpan hanya jika ada perubahan
            if ($updated) {
                $cashbon->save();
                return redirect()->route('keuangan.cashbon')->with('success', 'Cashbon berhasil diperbarui.');
            } else {
                // Jika tidak ada perubahan, kembalikan pesan informasi
                return redirect()->back()->with('info', 'Tidak ada data yang berubah.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function KeuanganCashbonDelete($id)
    {
        try {
            $cashbon = Cashbon::findOrFail($id);

            // Cek apakah status masih pending
            if ($cashbon->status !== 'pending') {
                return redirect()->back()->with('error', 'Hanya cashbon dengan status pending yang dapat dihapus.');
            }

            // Hapus cashbon
            $cashbon->delete();

            return redirect()->route('keuangan.cashbon')->with('success', 'Cashbon berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
