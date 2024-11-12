<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use App\Models\CashbonKaryawanLimit;
use App\Models\CashbonLimit;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\LokasiPenugasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CashbonLimitController extends Controller
{
    public function CashbonLimitIndex(Request $request)
    {
        // Ambil limit global
        $globalLimit = CashbonLimit::first()->global_limit ?? 0;

        // Ambil bulan saat ini
        $currentMonth = now()->month;
        $currentYear = Carbon::now()->year;

        // Ambil data karyawan beserta relasi yang diperlukan
        $karyawanQuery = Karyawan::with(['cashbonKaryawanLimit', 'jabatan', 'lokasiPenugasan', 'cabang']);

        // Filter berdasarkan nama karyawan jika ada
        if ($request->filled('nama_karyawan')) {
            $karyawanQuery->where('nama_lengkap', 'like', '%' . $request->nama_karyawan . '%');
        }

        // Filter berdasarkan kode jabatan jika ada
        if ($request->filled('kode_jabatan')) {
            $karyawanQuery->where('kode_jabatan', $request->kode_jabatan);
        }

        // Filter berdasarkan kode cabang jika ada
        if ($request->filled('kode_cabang')) {
            $karyawanQuery->where('kode_cabang', $request->kode_cabang);
        }

        // Filter berdasarkan kode lokasi penugasan jika ada
        if ($request->filled('kode_lokasi_penugasan')) {
            $karyawanQuery->where('kode_lokasi_penugasan', $request->kode_lokasi_penugasan);
        }

        // Ambil data karyawan
        $karyawan = $karyawanQuery->get()->map(function ($item) use ($currentMonth, $currentYear) {
            // Hitung cashbon yang diterima pada bulan dan tahun ini
            $item->cashbon_bulan_ini = $item->cashbon->where('status', 'diterima')
                ->filter(function ($cashbon) use ($currentMonth, $currentYear) {
                    return Carbon::parse($cashbon->tanggal_pengajuan)->month == $currentMonth &&
                        Carbon::parse($cashbon->tanggal_pengajuan)->year == $currentYear;
                })
                ->sum('jumlah');

            return $item;
        });

        // Ambil data jabatan, lokasi penugasan, dan cabang
        $jabatan = Jabatan::all();
        $lokasi_penugasan = LokasiPenugasan::with('cabang')->get();
        $cabang = Cabang::with('LokasiPenugasan')->get();

        // Kembalikan tampilan dengan data yang diperlukan
        return view('cashbon_limit.cashbon_limit_index', compact(
            'globalLimit',
            'karyawan',
            'currentMonth',
            'currentYear',
            'jabatan',
            'lokasi_penugasan',
            'cabang'
        ));
    }

    public function CashbonSetGlobalLimit(Request $request)
    {
        $request->validate([
        'global_limit' => 'required|numeric|min:0',
        'update_all' => 'sometimes|boolean',
        ]);

        DB::beginTransaction();

        try {
            // Update global limit
            CashbonLimit::updateOrCreate(
                ['id' => 1],
                ['global_limit' => $request->global_limit]
            );

            // Jika checkbox 'update_all' dicentang
            if ($request->has('update_all') && $request->update_all) {
                // Update semua limit personal karyawan
                CashbonKaryawanLimit::query()->update(['limit' => $request->global_limit]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Global limit updated successfully. All personal limits updated if requested.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update limits: ' . $e->getMessage());
        }
    }

    public function CashbonSetKaryawanLimit(Request $request, $nik)
    {
        $request->validate([
            'limit' => 'required|numeric|min:0',
        ]);

        CashbonKaryawanLimit::updateOrCreate(
            ['nik' => $nik],
            ['limit' => $request->limit]
        );

        return redirect()->back()->with('success', 'Karyawan limit updated successfully.');
    }
}
