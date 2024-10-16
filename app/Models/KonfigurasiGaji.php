<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonfigurasiGaji extends Model
{
    use HasFactory;

    protected $table = 'konfigurasi_gaji';
    protected $primaryKey = 'kode_jenis_gaji';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function gaji()
    {
        return $this->hasMany(Gaji::class, 'kode_jenis_gaji', 'kode_jenis_gaji');
    }
}
