<?php

namespace Database\Seeders;

use App\Models\LokasiKantor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LokasiKantorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LokasiKantor::create([
            'lokasi_kantor' => '-6.201977003748781, 106.84193108465621',
            'radius' => 30,
        ]);
    }
}
