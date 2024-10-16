<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gaji extends Model
{
    use HasFactory;
    protected $table = "gaji";
    protected $primaryKey = "kode_gaji";
    public $incrementing = false;
    protected $guarded = [];
    protected $keyType = 'string';

    public function jenisGaji()
    {
        return $this->belongsTo(KonfigurasiGaji::class, 'kode_jenis_gaji', 'kode_jenis_gaji');
    }
    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'kode_jabatan', 'kode_jabatan');
    }
}
