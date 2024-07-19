<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IzinController extends Controller
{
    public function CreateIzinAbsen()
    {
        return view('izin.create_izin_absen');
    }
    public function CreateIzinSakit()
    {
        return view('izin.create_izin_sakit');
    }
    public function CreateIzinCuti()
    {
        return view('izin.create_izin_cuti');
    }
}
