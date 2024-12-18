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
             // Change the column type to string and ensure it is not auto-increment
            $table->string('kode_izin')->change();
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
            $table->unsignedBigInteger('kode_izin')->autoIncrement()->change();
        });
    }
};
