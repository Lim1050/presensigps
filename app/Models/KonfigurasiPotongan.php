<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonfigurasiPotongan extends Model
{
    use HasFactory;

    protected $table = 'konfigurasi_potongan';
    protected $primaryKey = 'kode_jenis_potongan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function potongan()
    {
        return $this->hasMany(Potongan::class, 'kode_jenis_potongan', 'kode_jenis_potongan');
    }
}
