<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thr extends Model
{
    use HasFactory;

    protected $table = 'thr';

    protected $primaryKey = "kode_thr";
    public $incrementing = false;
    protected $guarded = [];
    protected $keyType = 'string';

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'nik', ownerKey: 'nik');
    }
    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'kode_jabatan', 'kode_jabatan');
    }

    public function lokasiPenugasan()
    {
        return $this->belongsTo(LokasiPenugasan::class, 'kode_lokasi_penugasan', 'kode_lokasi_penugasan');
    }
    public function kantorCabang()
    {
        return $this->belongsTo(Cabang::class, 'kode_cabang', ownerKey: 'kode_cabang');
    }
}
