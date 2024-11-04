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
        Schema::table('jam_kerja', function (Blueprint $table) {
            $table->string('kode_lokasi_penugasan', 255)->nullable()->after('kode_jam_kerja');
            $table->string('kode_cabang', 255)->nullable()->after('kode_lokasi_penugasan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jam_kerja', function (Blueprint $table) {
            $table->dropColumn(['kode_lokasi_penugasan', 'kode_cabang']);
        });
    }
};
