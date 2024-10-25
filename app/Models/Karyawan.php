<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Karyawan extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = "karyawan";
    protected $primaryKey = "nik";
    public $incrementing = false;
    protected $guarded = [];
    protected $keyType = 'string';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'kode_jabatan', 'kode_jabatan');
    }

    public function presensi()
    {
        return $this->hasMany(presensi::class, 'nik', 'nik');
    }

    public function penggajian()
    {
        return $this->hasMany(Penggajian::class, 'nik', 'nik');
    }
    public function cashbon()
    {
        return $this->hasMany(Cashbon::class, 'nik', 'nik');
    }
    public function cashbonKaryawanLimit()
    {
        return $this->hasOne(CashbonKaryawanLimit::class, 'nik', 'nik');
    }

        public function getAvailableCashbonLimit($globalLimit)
    {
        // Ambil limit personal karyawan, jika tidak ada gunakan global limit
        $personalLimit = $this->cashbonKaryawanLimit->limit ?? $globalLimit;

        // Hitung total cashbon yang sudah diterima
        $usedCashbon = $this->cashbon()
                            ->where('status', 'diterima')
                            ->sum('jumlah');

        // Hitung sisa limit yang tersedia
        return max(0, $personalLimit - $usedCashbon);
    }

    public function getFormattedAvailableCashbonLimit($globalLimit)
    {
        $availableLimit = $this->getAvailableCashbonLimit($globalLimit);
        return 'Rp ' . number_format($availableLimit, 0, ',', '.');
    }

    // Relasi dengan model PengajuanIzin
    public function pengajuanIzin()
    {
        return $this->hasMany(PengajuanIzin::class, 'nik', 'nik');
    }

    public function lokasiPenugasan()
    {
        return $this->belongsTo(LokasiPenugasan::class, 'kode_lokasi_penugasan', 'kode_lokasi_penugasan');
    }

    public function Cabang()
    {
        return $this->belongsTo(Cabang::class, 'kode_cabang',  ownerKey: 'kode_cabang');
    }

    public function departemen()
    {
        return $this->belongsTo(Departemen::class, 'kode_departemen', 'kode_departemen');
    }
}
