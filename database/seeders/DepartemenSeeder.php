<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartemenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('departemen')->insert([
            ['kode_departemen' => 'IT', 'nama_departemen' => 'Information Technology'],
            ['kode_departemen' => 'HR', 'nama_departemen' => 'Human Resources'],
            ['kode_departemen' => 'FIN', 'nama_departemen' => 'Finance'],
            ['kode_departemen' => 'MKT', 'nama_departemen' => 'Marketing'],
            ['kode_departemen' => 'OPS', 'nama_departemen' => 'Operations'],
        ]);
    }
}
