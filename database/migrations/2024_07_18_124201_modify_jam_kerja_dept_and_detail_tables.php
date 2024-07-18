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
        // Schema::table('jam_kerja_dept', function (Blueprint $table) {
        //     $table->char('kode_jk_dept', 10)->nullable()->change();
        //     $table->char('kode_cabang', 10)->nullable()->change();
        //     $table->char('kode_departemen', 10)->nullable()->change();
        // });

        Schema::table('jam_kerja_dept_detail', function (Blueprint $table) {
            $table->char('kode_jk_dept', 10)->nullable()->change();
            $table->string('hari', 20)->nullable()->change();
            $table->char('kode_jam_kerja', 10)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('jam_kerja_dept', function (Blueprint $table) {
        //     $table->char('kode_jk_dept', 10)->nullable(false)->change();
        //     $table->char('kode_cabang', 10)->nullable(false)->change();
        //     $table->char('kode_departemen', 10)->nullable(false)->change();
        // });

        Schema::table('jam_kerja_dept_detail', function (Blueprint $table) {
            $table->char('kode_jk_dept', 10)->nullable(false)->change();
            $table->string('hari', 20)->nullable(false)->change();
            $table->char('kode_jam_kerja', 10)->nullable(false)->change();
        });
    }
};
