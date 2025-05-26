<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// use Illuminate\Support\Facades\Auth; // Tidak diperlukan di migrasi

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fm_rumah_tangga', function (Blueprint $table) { // <-- NAMA TABEL BARU
            $table->id();

            // Data dari Step 1 (Informasi Form)
            $table->string('provinsi', 20);
            $table->string('kabupaten', 20);
            $table->string('kecamatan', 20);
            $table->string('desa', 20);
            $table->unsignedTinyInteger('rt');
            $table->unsignedTinyInteger('rw');
            $table->date('tgl_pembuatan');
            $table->string('nama_pendata', 100);
            $table->string('nama_responden', 100);

            // Data dari Step 3 (Rekapitulasi)
            $table->unsignedTinyInteger('jart');
            $table->unsignedTinyInteger('jart_ab');
            $table->unsignedTinyInteger('jart_tb');
            $table->unsignedTinyInteger('jart_ms');
            $table->enum('jpr2rtp', ['0','1','2','3','4']);

            // Data dari Step 4 (Verifikasi & Validasi Awal oleh User)
            $table->date('verif_tgl_pembuatan');
            $table->string('verif_nama_pendata', 100);
            $table->string('ttd_pendata')->nullable(); // TTD dari user/pendata awal

            // Kolom untuk validasi admin
            $table->enum('status_validasi', ['pending','validated','rejected'])->default('pending');
            $table->date('admin_tgl_validasi')->nullable();
            $table->string('admin_nama_kepaladusun', 100)->nullable();
            $table->string('admin_ttd_pendata')->nullable(); // TTD dari admin/pejabat

            $table->unsignedBigInteger('user_id')->nullable();
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('set null'); //relasi user

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fm_rumah_tangga');
    }
};