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
            $table->string('kode_thr', 255)->primary();
            $table->string('nik', 255)->nullable();
            $table->string('kode_jabatan', 255)->nullable();
            $table->string('kode_lokasi_penugasan', 10)->nullable();
            $table->string('kode_cabang', 10)->nullable();
            $table->string('nama_thr', 255)->nullable();
            $table->integer(column: 'tahun');
            $table->decimal('jumlah_thr', 10, 2)->nullable();
            $table->date(column: 'tanggal_penyerahan');
            $table->string('status')->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('nik')->references('nik')->on('karyawan')->onDelete('set null');
            $table->foreign('kode_jabatan')->references('kode_jabatan')->on('jabatan')->onDelete('set null');
            $table->foreign('kode_lokasi_penugasan')->references('kode_lokasi_penugasan')->on('lokasi_penugasan')->onDelete('set null');
            $table->foreign('kode_cabang')->references('kode_cabang')->on('kantor_cabang')->onDelete('set null');
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
