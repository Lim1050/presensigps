<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JamKerjaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('jam_kerja')->insert([
            [
                'kode_jam_kerja' => 'JK01',
                'nama_jam_kerja' => 'Shift Pagi',
                'awal_jam_masuk' => '07:00:00',
                'jam_masuk' => '08:00:00',
                'akhir_jam_masuk' => '09:00:00',
                'jam_pulang' => '17:00:00',
            ],
            [
                'kode_jam_kerja' => 'JK02',
                'nama_jam_kerja' => 'Shift Siang',
                'awal_jam_masuk' => '13:00:00',
                'jam_masuk' => '14:00:00',
                'akhir_jam_masuk' => '15:00:00',
                'jam_pulang' => '23:00:00',
            ],
            [
                'kode_jam_kerja' => 'JK03',
                'nama_jam_kerja' => 'Shift Malam',
                'awal_jam_masuk' => '21:00:00',
                'jam_masuk' => '22:00:00',
                'akhir_jam_masuk' => '23:00:00',
                'jam_pulang' => '07:00:00',
            ],
        ]);
    }
}
