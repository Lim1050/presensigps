<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JamKerja extends Model
{
    use HasFactory;
    protected $table = "jam_kerja";
    protected $primaryKey = "kode_jam_kerja";
    public $incrementing = false;
    protected $guarded = [];

    public function jamKerjaKaryawan()
    {
        return $this->hasMany(JamKerjaKaryawan::class, 'kode_jam_kerja', 'kode_jam_kerja');
    }
}
