<?php

namespace App\Exports;

use App\Models\Presensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanPresensiExport implements FromCollection, WithHeadings
{
    protected $presensiData;
    protected $karyawan;
    protected $totalGaji;

    public function __construct($presensiData, $karyawan, $totalGaji)
    {
        $this->presensiData = $presensiData;
        $this->karyawan = $karyawan;
        $this->totalGaji = $totalGaji;
    }

    public function collection()
    {
        return $this->presensiData;
    }

    public function headings(): array
    {
        return [
            'nomor',
            'nik',
            'Tanggal Presensi',
            'Jam Masuk',
            'Jam Keluar',
            'Foto Masuk',
            'Foto Keluar',
            'Lokasi Masuk',
            'Lokasi Keluar',
        ];
    }
}
