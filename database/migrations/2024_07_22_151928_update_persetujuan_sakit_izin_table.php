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
        Schema::table('pengajuan_sakit_izin', function (Blueprint $table) {
            // Rename the existing 'tanggal_izin' column to 'tanggal_izin_dari'
            $table->renameColumn('tanggal_izin', 'tanggal_izin_dari');

            // Add the new 'tanggal_izin_sampai' column after 'tanggal_izin_dari'
            $table->date('tanggal_izin_sampai')->after('tanggal_izin')->nullable();
            // Add the new 'jumlah_hari' column after 'tanggal_izin_sampai'
            $table->string('jumlah_hari')->after('tanggal_izin_sampai')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pengajuan_sakit_izin', function (Blueprint $table) {
            // Drop the 'tanggal_izin_sampai' column
            $table->dropColumn('tanggal_izin_sampai');
            // Drop the 'jumlah_hari' column
            $table->dropColumn('jumlah_hari');

            // Rename 'tanggal_izin_dari' back to 'tanggal_izin'
            $table->renameColumn('tanggal_izin_dari', 'tanggal_izin');
        });
    }
};
