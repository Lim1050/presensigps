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
        Schema::create('master_cuti', function (Blueprint $table) {
            // Define columns
            $table->string('kode_cuti')->primary();
            $table->string('nama_cuti')->nullable();
            $table->smallInteger('jumlah_hari')->nullable();

            // Timestamps (optional, can be removed if not needed)
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
        Schema::dropIfExists('master_cuti');
    }
};
