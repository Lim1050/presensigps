<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Potongan extends Model
{
    use HasFactory;

    protected $table = "potongan";
    protected $primaryKey = "kode_potongan";
    public $incrementing = false;
    protected $guarded = [];
    protected $keyType = 'string';

    public function jenisPotongan()
    {
        return $this->belongsTo(KonfigurasiPotongan::class, 'kode_jenis_potongan', ownerKey: 'kode_jenis_potongan');
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
