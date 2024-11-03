<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JamKerjaLokasiPenugasan extends Model
{
    use HasFactory;

    protected $table = 'jam_kerja_lokasi_penugasan'; // Sesuaikan dengan nama tabel Anda
    protected $primaryKey = 'kode_jk_lp_c';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [];

    public function jamKerja()
    {
        return $this->belongsTo(JamKerja::class, 'kode_jam_kerja', 'kode_jam_kerja');
    }

    public function lokasiPenugasan()
    {
        return $this->belongsTo(LokasiPenugasan::class, 'kode_lokasi_penugasan', 'kode_lokasi_penugasan');
    }

    public function kantorCabang()
    {
        return $this->belongsTo(Cabang::class, 'kode_cabang', 'kode_cabang');
    }
}
