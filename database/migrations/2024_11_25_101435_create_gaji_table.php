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
        Schema::create('gaji', function (Blueprint $table) {
            $table->string('kode_gaji')->primary();
            $table->string('kode_jabatan')->nullable();
            $table->string('kode_lokasi_penugasan', 10)->nullable();
            $table->string('kode_cabang', 10)->nullable();
            $table->string('kode_jenis_gaji')->nullable();
            $table->enum('jenis_gaji', ['Gaji tetap', 'Tunjangan jabatan', 'Transportasi'])->nullable();
            $table->string('nama_gaji')->nullable();
            $table->decimal('jumlah_gaji', 12, 2)->nullable();

            $table->timestamps();

            // Foreign key constraints with cascade delete
            $table->foreign('kode_jenis_gaji')
                  ->references('kode_jenis_gaji')
                  ->on('konfigurasi_gaji')
                  ->onDelete('cascade');

            $table->foreign('kode_lokasi_penugasan')
                  ->references('kode_lokasi_penugasan')
                  ->on('lokasi_penugasan')
                  ->onDelete('cascade');

            $table->foreign('kode_cabang')
                  ->references('kode_cabang')
                  ->on('kantor_cabang')
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
        Schema::dropIfExists('gaji');
    }
};
