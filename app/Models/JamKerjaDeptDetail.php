<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JamKerjaDeptDetail extends Model
{
    use HasFactory;
    protected $table = "jam_kerja_dept_detail";
    protected $guarded = [];

    public function jamKerja()
    {
        return $this->belongsTo(JamKerja::class, 'kode_jam_kerja', 'kode_jam_kerja');
    }

    public function jamKerjaDept()
    {
        return $this->belongsTo(JamKerjaDept::class, 'kode_jk_dept', ownerKey: 'kode_jk_dept');
    }
}
