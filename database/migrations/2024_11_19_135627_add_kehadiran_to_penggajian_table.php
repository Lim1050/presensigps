<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penggajian', function (Blueprint $table) {
            $table->integer('kehadiran_murni')->default(0)->after('jumlah_hari_masuk');
            $table->integer('jumlah_isc')->default(0)->after('kehadiran_murni');
            $table->integer('jumlah_izin')->default(0)->after('jumlah_isc');
            $table->integer('jumlah_sakit')->default(0)->after('jumlah_izin');
            $table->integer('jumlah_cuti')->default(0)->after('jumlah_sakit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('penggajian', function (Blueprint $table) {
            $table->dropColumn([
                'kehadiran_murni',
                'jumlah_isc',
                'jumlah_izin',
                'jumlah_sakit',
                'jumlah_cuti'
            ]);
        });
    }
};
