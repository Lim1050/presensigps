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
        Schema::table('lokasi_penugasan', function (Blueprint $table) {
            $table->integer('jumlah_jam_kerja')->after('radius')->nullable(); // Menambahkan kolom jumlah_jam_kerja setelah kolom radius
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lokasi_penugasan', function (Blueprint $table) {
            $table->dropColumn('jumlah_jam_kerja'); // Menghapus kolom jika migrasi dibatalkan
        });
    }
};
