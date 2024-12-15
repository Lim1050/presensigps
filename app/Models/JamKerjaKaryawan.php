<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JamKerjaKaryawan extends Model
{
    use HasFactory;
    protected $table = "jam_kerja_karyawan";
    protected $guarded = [];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'nik', 'nik');
    }

    public function jamKerja()
    {
        return $this->belongsTo(JamKerja::class, 'kode_jam_kerja', 'kode_jam_kerja');
    }

    // Scope untuk pencarian berdasarkan NIK dan hari
    public function scopeByNikAndDay($query, $nik, $hari)
    {
        return $query->where('nik', $nik)
                     ->where('hari', $hari);
    }
}
