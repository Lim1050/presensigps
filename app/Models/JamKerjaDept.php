<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JamKerjaDept extends Model
{
    use HasFactory;
    protected $table = "jam_kerja_dept";
    protected $guarded = [];

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'kode_cabang', 'kode_cabang');
    }

    public function departemen()
    {
        return $this->belongsTo(Departemen::class, 'kode_departemen', 'kode_departemen');
    }

    public function jamKerjaDeptDetail()
    {
        return $this->hasMany(JamKerjaDeptDetail::class, 'kode_jk_dept',  'kode_jk_dept');
    }
}
