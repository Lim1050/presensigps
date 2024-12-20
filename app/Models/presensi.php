<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class presensi extends Model
{
    use HasFactory;

    protected $table = "presensi";
    protected $primaryKey = "id";
    protected $guarded = [];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'nik', 'nik');
    }

    public function JamKerja()
    {
        return $this->belongsTo(JamKerja::class, 'kode_jam_kerja', 'kode_jam_kerja');
    }
    public function PengajuanIzin()
    {
        return $this->belongsTo(PengajuanIzin::class, 'kode_izin', ownerKey: 'kode_izin');
    }

    public function lembur()
    {
        return $this->hasOne(Lembur::class, 'nik', 'nik')
            ->where('tanggal_presensi', $this->tanggal_presensi);
    }
}
