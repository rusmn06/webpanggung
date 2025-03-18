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
        Schema::create('rekapitulasi', function (Blueprint $table) {
            $table->id();
            $table->integer('JART', false, true)->length(2);
            $table->integer('JART_AB', false, true)->length(2);
            $table->integer('JART_TB', false, true)->length(2);
            $table->integer('JART_MS', false, true)->length(2);
            $table->enum('JPR2RTP', ['0','1','2','3','4']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    // public function down(): void
    // {
    //     Schema::dropIfExists('rekapitulasi');
    // }
};