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
        Schema::create('identitas', function (Blueprint $table) {
            $table->id();
            $table->string('Nama', 100);
            $table->string('NIK', 20);
            $table->enum('HDKRT', ['1','2','3','4','5','6','7','8']);
            $table->enum('NUK', ['1','2']);
            $table->enum('HDKK', ['1','2','3','4','5','6','7','8']);
            $table->enum('Kelamin', ['1','2']);
            $table->enum('StatusPerkawinan', ['1','2','3','4']);
            $table->enum('StatusPekerjaan', ['1','2','3','4','5']);
            $table->enum('JenisPekerjaan', ['1','2','3','4']);
            $table->enum('SubJenisPekerjaan', ['1','2','3','4','5']);
            $table->enum('PendidikanTerakhir', ['1','2','3','4','5','6']);
            $table->enum('PendapatanPerBulan', ['1','2','3','4','5','6']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('identitas');
    }
};