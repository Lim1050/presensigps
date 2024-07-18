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
        Schema::create('jam_kerja_dept', function (Blueprint $table) {
            $table->char('kode_jk_dept', 10)->primary();
            $table->char('kode_cabang', 10);
            $table->char('kode_departemen', 10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jam_kerja_dept');
    }
};
