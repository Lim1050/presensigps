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
            $table->string('bulan')->nullable()->after('gaji'); // Kolom bulan
            $table->integer('jumlah_hari_dalam_bulan')->nullable()->after('bulan'); // Total hari dalam bulan
            $table->integer('jumlah_hari_masuk')->nullable()->after('jumlah_hari_dalam_bulan'); // Hari masuk kerja
            $table->integer('jumlah_hari_tidak_masuk')->nullable()->after('jumlah_hari_masuk'); // Hari tidak masuk
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
            $table->dropColumn(['bulan', 'jumlah_hari_dalam_bulan', 'jumlah_hari_masuk', 'jumlah_hari_tidak_masuk']);
        });
    }
};
