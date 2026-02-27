<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Master wilayah yang dipantau / terdampak
        Schema::create('wilayahs', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kecamatan')->nullable();
            $table->enum('status', ['terdampak', 'tidak terdampak'])->default('terdampak');
            $table->text('deskripsi')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // Kondisi fasilitas sanitasi di wilayah terdampak
        Schema::create('sanitasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wilayah_id')
                ->nullable()
                ->constrained('wilayahs')
                ->nullOnDelete();
            $table->string('nama');
            $table->integer('jumlah')->nullable();
            $table->string('lokasi');
            $table->text('keterangan')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // Data distribusi bantuan air bersih ke wilayah terdampak
        Schema::create('penyaluran_air', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wilayah_id')
                ->nullable()
                ->constrained('wilayahs')
                ->nullOnDelete();
            $table->string('sumber_air');
            $table->integer('volume_liter')->nullable();
            $table->date('tanggal_distribusi');
            $table->enum('status', ['terdistribusi', 'belum terdistribusi'])->default('belum terdistribusi');
            $table->text('keterangan')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // Riwayat inspeksi / laporan kondisi oleh petugas
        Schema::create('laporan_kondisi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wilayah_id')
                ->constrained('wilayahs')
                ->cascadeOnDelete();
            $table->foreignId('petugas_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->date('tanggal_inspeksi');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_kondisi');
        Schema::dropIfExists('penyaluran_air');
        Schema::dropIfExists('sanitasi');
        Schema::dropIfExists('wilayahs');
    }
};
