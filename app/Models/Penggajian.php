<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penggajian extends Model
{
    use HasFactory;
    protected $table = "penggajian";
    protected $primaryKey = "id";
    public $incrementing = false;
    protected $guarded = [];

// Boot method untuk menggenerate kode_penggajian otomatis
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->kode_penggajian)) {
                // Format: PG-YYYYMM-XXXX
                $latest = static::where('kode_penggajian', 'like', 'PG-' . date('Ym') . '%')
                    ->orderBy('kode_penggajian', 'desc')
                    ->first();

                if (!$latest) {
                    $number = '0001';
                } else {
                    $number = str_pad((int)substr($latest->kode_penggajian, -4) + 1, 4, '0', STR_PAD_LEFT);
                }

                $model->kode_penggajian = 'PG-' . date('Ym') . '-' . $number;
            }
        });
    }

    // Relationships (sesuaikan dengan struktur database Anda)
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'nik', 'nik');
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'kode_cabang', 'kode_cabang');
    }

    public function lokasiPenugasan()
    {
        return $this->belongsTo(LokasiPenugasan::class, 'kode_lokasi_penugasan', 'kode_lokasi_penugasan');
    }

    // Scope queries
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeDisetujui($query)
    {
        return $query->where('status', 'disetujui');
    }

    public function scopeDitolak($query)
    {
        return $query->where('status', 'ditolak');
    }

    public function scopeDibayar($query)
    {
        return $query->where('status', 'dibayar');
    }

    // Accessor untuk format rupiah
    public function getGajiBersihRupiahAttribute()
    {
        return 'Rp ' . number_format($this->gaji_bersih, 0, ',', '.');
    }
}
