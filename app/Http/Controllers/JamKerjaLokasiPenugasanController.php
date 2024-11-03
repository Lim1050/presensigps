<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use App\Models\JamKerja;
use App\Models\JamKerjaLokasiPenugasan;
use App\Models\LokasiPenugasan;
use Illuminate\Http\Request;

class JamKerjaLokasiPenugasanController extends Controller
{
    public function jamKerjaLokasiPenugasanIndex()
    {
        $jamKerjaLokasiPenugasan = JamKerjaLokasiPenugasan::with(['jamKerja', 'lokasiPenugasan', 'kantorCabang'])->get();
        return view('konfigurasi.jam_kerja_lokasi_penugasan_index', compact('jamKerjaLokasiPenugasan'));
    }

    public function JamKerjaLokasiPenugasanCreate()
    {
        $jam_kerja = JamKerja::get();
        $lokasi_penugasan = LokasiPenugasan::get();
        $kantor_cabang = Cabang::get();

        return view('konfigurasi.jam_kerja_lokasi_penugasan_create', compact('jam_kerja', 'lokasi_penugasan', 'kantor_cabang'));
    }
}
