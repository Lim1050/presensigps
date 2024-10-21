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
        Schema::table('karyawan', function (Blueprint $table) {
            $table->dropColumn('jabatan');
            $table->string('kode_lokasi_penugasan')->after('kode_cabang')->nullable();
            $table->foreign('kode_lokasi_penugasan')->references('kode_lokasi_penugasan')->on('lokasi_penugasan')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('karyawan', function (Blueprint $table) {
            $table->dropForeign(['kode_lokasi_penugasan']);
            $table->dropColumn('kode_lokasi_penugasan');
            $table->string('jabatan')->after('kode_cabang')->nullable();
        });
    }
};
