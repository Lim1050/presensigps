<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LokasiPenugasan extends Model
{
    use HasFactory;
    protected $table = 'lokasi_penugasan';
    protected $primaryKey = 'kode_lokasi_penugasan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [];

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'kode_cabang', 'kode_cabang');
    }
    public function karyawan()
    {
        return $this->hasMany(Karyawan::class, 'kode_lokasi_penugasan',  'kode_lokasi_penugasan');
    }
}
