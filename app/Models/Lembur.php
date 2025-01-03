<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lembur extends Model
{
    use HasFactory;
    protected $table = "lembur";
    protected $primaryKey = "kode_lembur";
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'nik', 'nik');
    }

    public function presensi()
    {
        return $this->hasOne(presensi::class, 'kode_lembur', 'kode_lembur');
    }
}
