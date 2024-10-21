<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    use HasFactory;
    protected $table = 'kantor_cabang';
    protected $primaryKey = 'kode_cabang';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [];

    public function LokasiPenugasan()
    {
        return $this->hasMany(LokasiPenugasan::class, 'kode_cabang', 'kode_cabang');
    }
    public function Cabang()
    {
        return $this->hasMany(Karyawan::class, 'kode_cabang', 'kode_cabang');
    }
}
