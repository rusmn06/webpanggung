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
        Schema::create('tb_tenagakerja', function (Blueprint $table) {
            $table->bigIncrements('id');
            // informasi Form
            $table->string('provinsi', 20);
            $table->string('kabupaten', 20);
            $table->string('kecamatan', 20);
            $table->string('desa', 20);
            $table->unsignedTinyInteger('rt');
            $table->unsignedTinyInteger('rw');
            $table->date('tgl_pembuatan');
            $table->string('nama_pendata', 100);
            $table->string('nama_responden', 100);
            // identitas
            $table->string('nama', 100);
            $table->string('nik', 20);
            $table->enum('hdkrt', ['1','2','3','4','5','6','7','8']);
            $table->enum('nuk', ['1','2']);
            $table->enum('hdkk', ['1','2','3','4','5','6','7','8']);
            $table->enum('kelamin', ['1','2']);
            $table->enum('status_perkawinan', ['1','2','3','4']);
            $table->enum('status_pekerjaan', ['1','2','3','4','5']);
            $table->enum('jenis_pekerjaan', ['1','2','3','4']);
            $table->enum('sub_jenis_pekerjaan', ['1','2','3','4','5']);
            $table->enum('pendidikan_terakhir', ['1','2','3','4','5','6']);
            $table->enum('pendapatan_per_bulan', ['1','2','3','4','5','6']);
            // rekapitulasi
            $table->unsignedTinyInteger('jart');
            $table->unsignedTinyInteger('jart_ab');
            $table->unsignedTinyInteger('jart_tb');
            $table->unsignedTinyInteger('jart_ms');
            $table->enum('jpr2rtp', ['0','1','2','3','4']);
            // verifikasi & validasi
            $table->date('verif_tgl_pembuatan');
            $table->string('verif_nama_pendata', 100);
            $table->string('ttd_pendata')->nullable(); // path relative to storage/app/public/ttd
            $table->date('admin_tgl_validasi')->nullable();
            $table->string('admin_nama_kepaladusun', 100)->nullable();
            $table->string('admin_ttd_pendata')->nullable();
            $table->enum('status_validasi', ['pending','validated','rejected'])
                  ->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    /**public function down(): void
    {
         Schema::dropIfExists('tb_tenagakerja');
    }*/
};
