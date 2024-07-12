<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JamKerjaKaryawan extends Model
{
    use HasFactory;
    protected $table = "jam_kerja_karyawan";
    protected $guarded = [];
}
