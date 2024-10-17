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
            $table->decimal('gaji_tetap', 10, 2)->after('nik')->default(0);
            $table->decimal('tunjangan_jabatan', 10, 2)->after('gaji_tetap')->default(0);
            $table->decimal('uang_makan', 10, 2)->after('tunjangan_jabatan')->default(0);
            $table->decimal('transportasi', 10, 2)->after('uang_makan')->default(0);
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
            $table->dropColumn(['gaji_tetap', 'tunjangan_jabatan', 'uang_makan', 'transportasi']);
        });
    }
};
