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
            $table->string('foto')->nullable()->after('no_wa');
            // Tambahkan kolom 'foto' dengan tipe data string, nullable untuk mengizinkan nilai NULL
            // 'after' digunakan untuk menentukan posisi kolom baru setelah kolom 'no_wa'
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
            $table->dropColumn('foto');
            // Hapus kolom 'foto' jika migrasi di-rollback
        });
    }
};
