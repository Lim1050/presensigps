<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cashbon;
use App\Models\Penggajian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function KeuanganCashbonCreate()
    {
        return view('keuangan.keuangan_cashbon_create');
    }
}
