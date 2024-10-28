<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\Thr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ThrController extends Controller
{
    public function ThrIndex()
    {
        $thr = Thr::with(['jabatan', 'lokasiPenugasan', 'cabang', 'karyawan'])->get();

        return view('thr.index', compact(var_name: 'thr'));
    }

    public function ThrHitung()
    {
        $tahun = date('Y');
        $karyawan = Karyawan::with(['jabatan', 'lokasiPenugasan', 'cabang'])->get();

        DB::beginTransaction();
        try {
            foreach ($karyawan as $item) {
                $jumlah_thr = $this->ThrHitungJumlah($item);

                Thr::create([
                    'employee_id' => $item->id,
                    'year' => $tahun,
                    'amount' => $jumlah_thr,
                    'payment_date' => date('Y-m-d'),
                    'status' => 'calculated'
                ]);
            }
            DB::commit();
            return redirect()->route('thr.index')->with('success', 'THR berhasil dihitung');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function ThrHitungJumlah($employee)
    {
        $baseThr = $employee->base_salary;
        $positionMultiplier = $employee->position->thr_multiplier;
        $locationAllowance = $employee->branchOffice->location_allowance;

        return ($baseThr * $positionMultiplier) + $locationAllowance;
    }

    public function ThrShow($kode_thr)
    {
        $employee = Thr::with(['jabatan', 'lokasiPenugasan', 'cabang', 'karyawan'])->findOrFail($kode_thr);
        return view('thr.show', compact('employee'));
    }

    public function ThrPersetujuan(Request $request, $kode_thr)
    {
        $thr = Thr::findOrFail($kode_thr);
        $thr->update([
            'status' => 'approved',
            'notes' => $request->notes
        ]);

        return redirect()->back()->with('success', 'THR berhasil disetujui');
    }
}
