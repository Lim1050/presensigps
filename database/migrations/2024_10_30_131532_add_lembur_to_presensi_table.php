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
        Schema::table('presensi', function (Blueprint $table) {
            $table->boolean(column: 'lembur')->nullable()->after('kode_izin');
            $table->time(column: 'mulai_lembur')->nullable()->after('lembur');
            $table->time(column: 'selesai_lembur')->nullable()->after(column: 'mulai_lembur');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('presensi', function (Blueprint $table) {
            $table->dropColumn(columns: 'selesai_lembur');
            $table->dropColumn(columns: 'mulai_lembur');
            $table->dropColumn(columns: 'lembur');
        });
    }
};
