<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterCutiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Data to be seeded
        $cutiData = [
            [
                'kode_cuti' => 'C001',
                'nama_cuti' => 'Cuti Tahunan',
                'jumlah_hari' => 12,
                'created_at' => Carbon::now(),
            ],
            [
                'kode_cuti' => 'C002',
                'nama_cuti' => 'Cuti Sakit',
                'jumlah_hari' => 5,
                'created_at' => Carbon::now(),
            ],
            [
                'kode_cuti' => 'C003',
                'nama_cuti' => 'Cuti Melahirkan',
                'jumlah_hari' => 90,
                'created_at' => Carbon::now(),
            ],
        ];

        // Insert data into the table
        DB::table('master_cuti')->insert($cutiData);
    }
}
