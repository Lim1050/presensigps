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
        Schema::create('jam_kerja_lokasi_penugasan', function (Blueprint $table) {
            $table->string('kode_jk_lp_c', 20)->primary();
            $table->string('kode_lokasi_penugasan', 20);
            $table->string('kode_cabang', 20);
            $table->string('kode_jam_kerja', 20);
            $table->string('hari', length: 20);
            $table->timestamps();

            $table->foreign('kode_jam_kerja')->references('kode_jam_kerja')->on('jam_kerja')->onDelete('cascade');
            $table->foreign('kode_lokasi_penugasan')->references('kode_lokasi_penugasan')->on('lokasi_penugasan')->onDelete('cascade');
            $table->foreign('kode_cabang')->references('kode_cabang')->on('kantor_cabang')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jam_kerja_lokasi_penugasan');
    }
};
