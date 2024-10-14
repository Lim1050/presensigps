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

    // Hitung jumlah hari izin
    public function getJumlahHariAttribute()
    {
        return Carbon::parse($this->tanggal_izin_dari)->diffInDays(Carbon::parse($this->tanggal_izin_sampai)) + 1;
    }

    // Scope untuk filter izin yang sudah disetujui
    public function scopeApproved($query)
    {
        return $query->where('status_approved', 1);
    }

    // Scope untuk filter izin berdasarkan jenis
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
