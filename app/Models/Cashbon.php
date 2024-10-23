<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cashbon extends Model
{
    use HasFactory;
    protected $table = "cashbon";
    protected $primaryKey = "id";
    public $incrementing = true;
    protected $guarded = [];
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'nik', 'nik');
    }
}
