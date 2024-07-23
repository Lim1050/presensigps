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
        Schema::table('pengajuan_izin', function (Blueprint $table) {
            // Add 'kode_cuti' column after 'jumlah_hari'
            $table->string('kode_cuti')->nullable()->after('jumlah_hari');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pengajuan_izin', function (Blueprint $table) {
            // Drop the 'kode_cuti' column
            $table->dropColumn('kode_cuti');
        });
    }
};
