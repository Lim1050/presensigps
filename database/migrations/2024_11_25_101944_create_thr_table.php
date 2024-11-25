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
        Schema::create('thr', function (Blueprint $table) {
            $table->string('kode_thr')->primary();
            $table->string('nik')->nullable();
            $table->string('kode_jabatan')->nullable();
            $table->string('kode_lokasi_penugasan', 10)->nullable();
            $table->string('kode_cabang', 10)->nullable();
            $table->string('nama_thr')->nullable();
            $table->integer('tahun');
            $table->decimal('jumlah_thr', 65, 2)->nullable();
            $table->date('tanggal_penyerahan');
            $table->string('status')->default('pending');
            $table->text('notes')->nullable();
            $table->text('catatan_perubahan')->nullable();
            $table->string('diubah_oleh')->nullable();

            $table->timestamps();

            // Foreign key constraints with cascade delete
            $table->foreign('nik')
                  ->references('nik')
                  ->on('karyawan')
                  ->onDelete('cascade');

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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('thr');
    }
};
