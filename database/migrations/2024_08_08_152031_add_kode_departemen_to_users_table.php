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
        Schema::table('users', function (Blueprint $table) {
            $table->string('foto')->after('password')->nullable();
            $table->string('role')->after('foto')->nullable();
            $table->string('kode_departemen')->after('role')->nullable();
            $table->string('kode_cabang')->after('kode_departemen')->nullable();
            $table->string('no_hp')->after('kode_cabang')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('no_hp');
            $table->dropColumn('kode_cabang');
            $table->dropColumn('kode_departemen');
            $table->dropColumn('role');
            $table->dropColumn('foto');
        });
    }
};
