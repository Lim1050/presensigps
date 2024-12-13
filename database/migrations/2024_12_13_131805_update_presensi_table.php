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

            // Tambah kolom baru
            if (!Schema::hasColumn('presensi', 'kode_lembur')) {
                $table->string('kode_lembur')->nullable()->after('kode_izin');
                $table->foreign('kode_lembur')
                    ->references('kode_lembur')
                    ->on('lembur')
                    ->onDelete('set null');
            }

            if (!Schema::hasColumn('presensi', 'jenis_absen_lembur')) {
                $table->enum('jenis_absen_lembur', ['single', 'double'])
                    ->nullable()
                    ->after('kode_lembur');
            }

            // Default value untuk kolom existing
            $table->boolean('lembur')->default(false)->change();
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
            // Hapus kolom yang ditambahkan
            if (Schema::hasColumn('presensi', 'kode_lembur')) {
                $table->dropForeign(['kode_lembur']);
                $table->dropColumn('kode_lembur');
            }

            if (Schema::hasColumn('presensi', 'jenis_absen_lembur')) {
                $table->dropColumn('jenis_absen_lembur');
            }
        });
    }
};
