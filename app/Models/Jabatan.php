<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;
    protected $table = "jabatan";
    protected $primaryKey = "kode_jabatan";
    public $incrementing = false;
    protected $guarded = [];
    // protected $keyType = 'string';

    public function karyawan()
    {
        return $this->hasMany(Karyawan::class, 'kode_jabatan', 'kode_jabatan');
    }

    public function gaji()
    {
        return $this->hasMany(Gaji::class, 'kode_jabatan', 'kode_jabatan');
    }
    public function karyawan()
    {
        return $this->hasMany(Karyawan::class, 'kode_jabatan', 'kode_jabatan');
    }

}
