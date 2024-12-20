<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JamKerja extends Model
{
    use HasFactory;
    protected $table = "jam_kerja";
    protected $primaryKey = "kode_jam_kerja";
    public $incrementing = false;
    protected $guarded = [];

    public function jamKerjaKaryawan()
    {
        return $this->hasMany(JamKerjaKaryawan::class, 'kode_jam_kerja', 'kode_jam_kerja');
    }
    public function jamKerjaDeptDetail()
    {
        return $this->hasMany(JamKerjaDeptDetail::class, 'kode_jam_kerja', 'kode_jam_kerja');
    }

    public function Presensi()
    {
        return $this->hasMany(Presensi::class, 'kode_jam_kerja', 'kode_jam_kerja');
    }

    // Relasi dengan model LokasiPenugasan
    public function lokasiPenugasan()
    {
        return $this->belongsTo(LokasiPenugasan::class, 'kode_lokasi_penugasan', 'kode_lokasi_penugasan');
    }

    // Relasi dengan model Cabang
    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'kode_cabang', 'kode_cabang');
    }

    // Scope untuk mencari jam kerja berdasarkan kode
    public function scopeByKode($query, $kode)
    {
        return $query->where('kode_jam_kerja', $kode);
    }

    // Accessor untuk mendapatkan rentang jam kerja
    public function getRentangJamKerjaAttribute()
    {
        return $this->jam_masuk . ' - ' . $this->jam_pulang;
    }
}
