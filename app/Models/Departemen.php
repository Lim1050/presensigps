<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departemen extends Model
{
    use HasFactory;
    protected $table = "departemen";
    protected $guarded = [];

    public function karyawan()
    {
        return $this->hasMany(Karyawan::class, 'kode_departemen', localKey: 'kode_departemen');
    }

    public function jamKerjaDept()
    {
        return $this->hasMany(JamKerjaDept::class, 'kode_departemen', localKey: 'kode_departemen');
    }
}
