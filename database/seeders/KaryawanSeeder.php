<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class KaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Hapus data yang sudah ada
        DB::table('karyawan')->truncate();

        // Hashed password
        $password = Hash::make('password123'); // Ganti dengan password yang ingin Anda gunakan

        // Timestamp
        $now = Carbon::now();

        // Buat data seeder
        $karyawan = [
            [
                'nik' => '123123123',
                'nama_lengkap' => 'Purnama Ramadhan Salim',
                'jabatan' => 'Head IT',
                'no_wa' => '08123456789',
                'password' => $password,
                'foto' => '/upload/img/foto_karyawan/foto1.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nik' => '321321321',
                'nama_lengkap' => 'Salim Purnama Ramadhan',
                'jabatan' => 'Head Manager',
                'no_wa' => '08123456789',
                'password' => $password,
                'foto' => '/upload/img/foto_karyawan/foto1.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nik' => '213213213',
                'nama_lengkap' => 'Ramadhan Salim Purnama',
                'jabatan' => 'Head HRD',
                'no_wa' => '08123456789',
                'password' => $password,
                'foto' => '/upload/img/foto_karyawan/foto1.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Tambahkan data karyawan lainnya sesuai kebutuhan
        ];

        // Masukkan data ke dalam tabel menggunakan DB facade
        DB::table('karyawan')->insert($karyawan);
    }
}
