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
        Schema::table('lembur', function (Blueprint $table) {
            // Tambahkan kolom baru
            if (!Schema::hasColumn('lembur', 'kode_lembur')) {
                $table->string('kode_lembur')->nullable()->after('id');
            }

            if (!Schema::hasColumn('lembur', 'jenis_lembur')) {
                $table->enum('jenis_lembur', ['penebalan', 'reguler', 'khusus'])
                    ->default('reguler')
                    ->after('status');
            }

            if (!Schema::hasColumn('lembur', 'jam_masuk_asli')) {
                $table->time('jam_masuk_asli')->nullable()->after('jenis_lembur');
            }

            if (!Schema::hasColumn('lembur', 'jam_pulang_asli')) {
                $table->time('jam_pulang_asli')->nullable()->after('jam_masuk_asli');
            }

            if (!Schema::hasColumn('lembur', 'total_absen')) {
                $table->integer('total_absen')->default(1)->after('jam_pulang_asli');
            }

            // Default value untuk kolom existing
            $table->boolean('lintas_hari')->default(false)->change();
            $table->boolean('lembur_libur')->default(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lembur', function (Blueprint $table) {
            // Hapus kolom yang ditambahkan
            if (Schema::hasColumn('lembur', 'jenis_lembur')) {
                $table->dropColumn('jenis_lembur');
            }

            if (Schema::hasColumn('lembur', 'jam_masuk_asli')) {
                $table->dropColumn('jam_masuk_asli');
            }

            if (Schema::hasColumn('lembur', 'jam_pulang_asli')) {
                $table->dropColumn('jam_pulang_asli');
            }

            if (Schema::hasColumn('lembur', 'total_absen')) {
                $table->dropColumn('total_absen');
            }

            if (Schema::hasColumn('lembur', 'approved_by')) {
                $table->dropForeign(['approved_by']);
                $table->dropColumn('approved_by');
            }
        });
    }
};
