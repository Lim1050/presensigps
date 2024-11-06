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
        Schema::create('penggajian', function (Blueprint $table) {
            // Primary Key
            $table->string('kode_penggajian', 20)->primary();

            // Informasi Dasar
            $table->string('nik')->nullable()->index();
            $table->string('kode_cabang')->nullable();
            $table->string('kode_lokasi_penugasan')->nullable();
            $table->date('tanggal_gaji')->nullable()->index();
            $table->string('bulan')->nullable();

            // Data Kehadiran
            $table->integer('jumlah_hari_kerja')->nullable();
            $table->integer('jumlah_hari_masuk')->nullable();
            $table->integer('jumlah_hari_tidak_masuk')->nullable();
            $table->decimal('total_jam_lembur', 10, 2)->default(0);

            // Komponen Gaji
            $table->json('komponen_gaji')->nullable();
            $table->decimal('total_gaji_kotor', 10, 2)->default(0);

            // Komponen Potongan
            $table->json('komponen_potongan')->nullable();
            $table->decimal('total_potongan', 10, 2)->default(0);

            // Total Akhir
            $table->decimal('gaji_bersih', 10, 2)->default(0);

            // Audit Trail
            $table->enum('status', ['draft', 'disetujui', 'ditolak', 'dibayar'])->default('draft');
            $table->text('catatan')->nullable();
            $table->string('diproses_oleh')->nullable();

            // Riwayat Perubahan
            $table->text('alasan_perubahan')->nullable();
            $table->string('diubah_oleh')->nullable();
            $table->timestamp('waktu_perubahan')->nullable();

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
        Schema::dropIfExists('penggajian');
    }
};
