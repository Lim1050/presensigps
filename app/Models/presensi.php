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
        return $this->belongsTo(Lembur::class, 'kode_lembur', 'kode_lembur');
    }

    // Accessor untuk nama jam kerja
    public function getNamaJamKerjaAttribute()
    {
        return optional($this->JamKerja)->nama_jam_kerja;
    }

    // Accessor untuk jam kerja masuk
    public function getJamKerjaMasukAttribute()
    {
        return optional($this->JamKerja)->jam_masuk;
    }

    // Accessor untuk jam pulang
    public function getJamPulangAttribute()
    {
        return optional($this->JamKerja)->jam_pulang;
    }

    // Accessor untuk jam mulai lembur
    public function getJamMulaiLemburAttribute()
    {
        return optional($this->Lembur)->waktu_mulai;
    }

    // Accessor untuk jam selesai lembur
    public function getJamSelesaiLemburAttribute()
    {
        return optional($this->Lembur)->waktu_selesai;
    }
}
