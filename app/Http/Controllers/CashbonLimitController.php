<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CashbonKaryawanLimit;
use App\Models\CashbonLimit;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashbonLimitController extends Controller
{
    public function CashbonLimitIndex()
    {
        $globalLimit = CashbonLimit::first()->global_limit ?? 0;
        $karyawan = Karyawan::with('cashbonKaryawanLimit', 'cashbon')->get();
        // dd($karyawan);
        return view('cashbon_limit.cashbon_limit_index', compact('globalLimit', 'karyawan'));
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
