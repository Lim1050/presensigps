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
        Schema::create('lokasi_penugasan', function (Blueprint $table) {
            $table->string('kode_lokasi_penugasan')->primary();
            $table->string('nama_lokasi_penugasan');
            $table->string('lokasi_penugasan');
            $table->float('radius')->nullable();
            $table->string('kode_cabang');
            $table->timestamps();

            // Menambahkan foreign key jika tabel cabang sudah ada
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
        Schema::dropIfExists('lokasi_penugasan');
    }
};
