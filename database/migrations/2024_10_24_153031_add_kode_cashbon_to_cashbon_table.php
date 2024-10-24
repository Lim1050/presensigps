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
        Schema::table('cashbon', function (Blueprint $table) {
            $table->string('kode_cashbon')->unique()->after('id'); // Menambahkan kolom kode_cashbon
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cashbon', function (Blueprint $table) {
            $table->dropColumn('kode_cashbon'); // Menghapus kolom saat rollback
        });
    }
};
