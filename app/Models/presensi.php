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

    public function lembur()
    {
        return $this->hasOne(Lembur::class, 'nik', 'nik')
            ->where('tanggal_presensi', $this->tanggal_presensi);
    }
}
