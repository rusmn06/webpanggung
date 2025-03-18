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
        Schema::create('informasi_form', function (Blueprint $table) {
            $table->id();
            $table->string('Provinsi', 20);
            $table->string('Kabupaten', 20);
            $table->string('Kecamatan', 20);
            $table->string('Desa', 20);
            $table->string('RT_RW', 7);
            $table->date('TglPembuatan');
            $table->string('NamaPendata', 100);
            $table->string('NamaResponden', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('informasi_form');
    }
};