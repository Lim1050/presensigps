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
            $table->integer(column: 'jumlah_hari_kerja')->after('jumlah_jam_kerja')->nullable();
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
            $table->dropColumn('jumlah_hari_kerja');
        });
    }
};
