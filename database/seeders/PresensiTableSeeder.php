<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PresensiTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         // Hapus data yang sudah ada
        // DB::table('presensi')->truncate();

        // Hashed password
        // $password = Hash::make('password123'); // Ganti dengan password yang ingin Anda gunakan

        // Timestamp
        // $now = Carbon::now();

        // Buat data seeder
        $presensi = [
            [
                'nik' => '321321321',
                'tanggal_presensi' => '2024-06-20',
                'jam_masuk' => '07:10:43',
                'jam_keluar' => '17:10:43',
                'foto_masuk' => 'public/uploads/absensi/213213213-2024-06-20-071043-masuk.png',
                'foto_keluar' => 'public/uploads/absensi/213213213-2024-06-20-171043-keluar.png',
                'lokasi_masuk' => '-6.2017089,106.8421282',
                'lokasi_keluar' => '-6.2017089,106.8421282',
            ],
            [
                'nik' => '321321321',
                'tanggal_presensi' => '2024-06-21',
                'jam_masuk' => '08:10:43',
                'jam_keluar' => '17:10:43',
                'foto_masuk' => 'public/uploads/absensi/321321321-2024-06-21-081043-masuk.png',
                'foto_keluar' => 'public/uploads/absensi/321321321-2024-06-21-171043-keluar.png',
                'lokasi_masuk' => '-6.2017089,106.8421282',
                'lokasi_keluar' => '-6.2017089,106.8421282',
            ],
            [
                'nik' => '321321321',
                'tanggal_presensi' => '2024-06-22',
                'jam_masuk' => '07:10:43',
                'jam_keluar' => '17:10:43',
                'foto_masuk' => 'public/uploads/absensi/213213213-2024-06-20-071043-masuk.png',
                'foto_keluar' => 'public/uploads/absensi/213213213-2024-06-20-171043-keluar.png',
                'lokasi_masuk' => '-6.2017089,106.8421282',
                'lokasi_keluar' => '-6.2017089,106.8421282',
            ],
            [
                'nik' => '321321321',
                'tanggal_presensi' => '2024-06-23',
                'jam_masuk' => '08:10:43',
                'jam_keluar' => '17:10:43',
                'foto_masuk' => 'public/uploads/absensi/321321321-2024-06-21-081043-masuk.png',
                'foto_keluar' => 'public/uploads/absensi/321321321-2024-06-21-171043-keluar.png',
                'lokasi_masuk' => '-6.2017089,106.8421282',
                'lokasi_keluar' => '-6.2017089,106.8421282',
            ],

            // Tambahkan data karyawan lainnya sesuai kebutuhan
        ];

        // Masukkan data ke dalam tabel menggunakan DB facade
        DB::table('presensi')->insert($presensi);
    }
}
