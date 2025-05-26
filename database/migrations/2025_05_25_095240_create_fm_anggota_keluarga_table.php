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
        Schema::create('fm_anggota_keluarga', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('rumah_tangga_id');
            // foreign key
            $table->foreign('rumah_tangga_id')->references('id')->on('fm_rumah_tangga')->onDelete('cascade'); // <-- MENUNJUK KE fm_rumah_tangga

            // Data dari Step 2 (Identitas Anggota)
            $table->string('nama', 100);
            $table->string('nik', 20)->unique();
            $table->enum('hdkrt', ['1','2','3','4','5','6','7','8']);
            $table->unsignedTinyInteger('nuk');
            $table->enum('hdkk', ['1','2','3','4','5','6','7','8']);
            $table->enum('kelamin', ['1','2']);
            $table->enum('status_perkawinan', ['1','2','3','4']);
            $table->enum('status_pekerjaan', ['1','2','3','4','5']);
            $table->enum('jenis_pekerjaan', ['1','2','3','4']);
            $table->enum('sub_jenis_pekerjaan', ['1','2','3','4','5']);
            $table->enum('pendidikan_terakhir', ['1','2','3','4','5','6']);
            $table->enum('pendapatan_per_bulan', ['1','2','3','4','5','6']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fm_anggota_keluarga');
    }
};