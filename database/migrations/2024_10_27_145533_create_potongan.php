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
        Schema::create('potongan', function (Blueprint $table) {
            $table->string('kode_potongan', 255)->primary();
            $table->string('kode_jabatan', 255)->nullable();
            $table->string('kode_lokasi_penugasan', 10)->nullable();
            $table->string('kode_cabang', 10)->nullable();
            $table->string('kode_jenis_potongan')->nullable();
            $table->string('nama_potongan', 255)->nullable();
            $table->decimal('jumlah_potongan', 10, 2)->nullable();
            $table->timestamps();

            $table->foreign('kode_jabatan')->references('kode_jabatan')->on('jabatan')->onDelete('set null');
            $table->foreign('kode_lokasi_penugasan')->references('kode_lokasi_penugasan')->on('lokasi_penugasan')->onDelete('set null');
            $table->foreign('kode_cabang')->references('kode_cabang')->on('kantor_cabang')->onDelete('set null');
            $table->foreign('kode_jenis_potongan')->references('kode_jenis_potongan')->on('konfigurasi_potongan')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('potongan');
    }
};