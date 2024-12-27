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
            $table->string(column: 'kode_lembur')->nullable()->after('kode_izin');

            $table->string('jenis_absen_lembur')->nullable()->after('kode_lembur');

            $table->foreign(columns: 'kode_lembur')
                    ->references('kode_lembur')
                    ->on('lembur')
                    ->onUpdate('no action')
                    ->onDelete('no action');
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
            $table->dropForeign(['kode_lembur']);
            $table->dropColumn('kode_lembur');
            $table->dropColumn('jenis_absen_lembur');
        });
    }
};
