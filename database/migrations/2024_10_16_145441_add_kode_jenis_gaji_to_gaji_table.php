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
        Schema::table('gaji', function (Blueprint $table) {
            $table->string('kode_jenis_gaji')->after(column: 'kode_jabatan')->nullable();
            $table->foreign('kode_jenis_gaji')->references('kode_jenis_gaji')->on('konfigurasi_gaji')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gaji', function (Blueprint $table) {
            $table->dropForeign(['kode_jenis_gaji']);
            $table->dropColumn('kode_jenis_gaji');
        });
    }
};
