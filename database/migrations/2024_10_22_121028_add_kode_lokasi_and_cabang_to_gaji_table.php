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
            $table->string('kode_lokasi_penugasan', 10)->after('kode_jabatan')->nullable();
            $table->string('kode_cabang', 10)->after('kode_lokasi_penugasan')->nullable();
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
        Schema::table('gaji', function (Blueprint $table) {
            $table->dropColumn(['kode_lokasi_penugasan', 'kode_cabang']);
            $table->dropForeign(['kode_lokasi_penugasan', 'kode_cabang']);
        });
    }
};
