<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PresensiController extends Controller
{
    public function PresensiCreate()
    {
        return view('presensi.create_presensi');
    }
}
