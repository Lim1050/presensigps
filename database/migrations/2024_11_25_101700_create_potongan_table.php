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
            $table->string('kode_potongan')->primary();
            $table->string('kode_jabatan')->nullable();
            $table->string('kode_lokasi_penugasan', 10)->nullable();
            $table->string('kode_cabang', 10)->nullable();
            $table->string('kode_jenis_potongan')->nullable();
            $table->string('nama_potongan')->nullable();
            $table->decimal('jumlah_potongan', 10, 2)->nullable();

            $table->timestamps();

            // Foreign key constraints with cascade delete
            $table->foreign('kode_jabatan')
                  ->references('kode_jabatan')
                  ->on('jabatan')
                  ->onDelete('cascade');

            $table->foreign('kode_lokasi_penugasan')
                  ->references('kode_lokasi_penugasan')
                  ->on('lokasi_penugasan')
                  ->onDelete('cascade');

            $table->foreign('kode_cabang')
                  ->references('kode_cabang')
                  ->on('kantor_cabang')
                  ->onDelete('cascade');

            $table->foreign('kode_jenis_potongan')
                  ->references('kode_jenis_potongan')
                  ->on('konfigurasi_potongan')
                  ->onDelete('cascade');
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
