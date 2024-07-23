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
            // Add the new 'surat_sakit' column after the 'keterangan' column
            $table->string('surat_sakit')->after('keterangan')->nullable();
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
            // Drop the 'surat_sakit' column
            $table->dropColumn('surat_sakit');
        });
    }
};
