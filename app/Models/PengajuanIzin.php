<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanIzin extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_izin';

    protected $primaryKey = 'kode_izin';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [];

    protected $dates = [
        'tanggal_izin_dari',
        'tanggal_izin_sampai',
        'created_at',
        'updated_at',
    ];

    // Relasi dengan model Karyawan
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'nik', 'nik');
    }

    public function Presensi()
    {
        return $this->hasMany(Presensi::class, 'kode_izin', 'kode_izin');
    }

        public function getJumlahHariAttribute()
    {
        return Carbon::parse($this->tanggal_izin_dari)->diffInDays(Carbon::parse($this->tanggal_izin_sampai)) + 1;
    }

    // public function scopeApproved($query)
    // {
    //     return $query->where('status_approved', 1);
    // }

    // public function scopeByNik($query, $nik)
    // {
    //     return $query->where('pengajuan_izin.nik', 'like', '%' . $nik . '%');
    // }

    // public function scopeByNama($query, $nama)
    // {
    //     return $query->where('nama_lengkap', 'like', '%' . $nama . '%');
    // }

    // public function scopeByJabatan($query, $kodeJabatan)
    // {
    //     return $query->where('kode_jabatan', 'like', '%' . $kodeJabatan . '%');
    // }

    // public function scopeByDateRange($query, $dari, $sampai)
    // {
    //     return $query->whereBetween('tanggal_izin_dari', [$dari, $sampai]);
    // }
}
