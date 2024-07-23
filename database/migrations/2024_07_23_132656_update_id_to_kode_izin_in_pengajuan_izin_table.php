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
            // Rename the existing 'id' column to 'kode_izin' and make it non-incrementing
            $table->renameColumn('id', 'kode_izin');

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
            // Rename the 'kode_izin' column back to 'id' and make it incrementing
            $table->renameColumn('kode_izin', 'id');
        });
    }
};
