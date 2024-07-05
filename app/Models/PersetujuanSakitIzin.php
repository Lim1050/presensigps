<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersetujuanSakitIzin extends Model
{
    use HasFactory;
    protected $table = "pengajuan_sakit_izin";
    protected $primaryKey = "id";
    protected $guarded = [];

}
