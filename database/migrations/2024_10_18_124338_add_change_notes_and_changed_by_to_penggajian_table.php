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
            $table->text('catatan_perubahan')->nullable()->after('total_gaji');
            $table->string('diubah_oleh')->nullable()->after('catatan_perubahan');
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
            $table->dropColumn('catatan_perubahan');
            $table->dropColumn('diubah_oleh');
        });
    }
};
