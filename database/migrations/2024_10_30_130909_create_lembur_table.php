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
        Schema::create('lembur', function (Blueprint $table) {
            $table->id();
            $table->string('nik')->nullable();
            $table->date('tanggal_presensi')->nullable();
            $table->time(column: 'waktu_mulai')->nullable();
            $table->time(column: 'waktu_selesai')->nullable();
            $table->boolean(column: 'lintas_hari')->nullable();
            $table->boolean('lembur_libur')->default(false)->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('lembur');
    }
};
