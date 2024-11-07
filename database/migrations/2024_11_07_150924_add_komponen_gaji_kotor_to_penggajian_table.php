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
        Schema::table('penggajian', function (Blueprint $table) {
            $table->json('komponen_gaji_kotor')->nullable()->after('total_jam_lembur'); // Menambahkan kolom baru
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('penggajian', function (Blueprint $table) {
            $table->dropColumn('komponen_gaji_kotor'); // Menghapus kolom saat rollback
        });
    }
};
