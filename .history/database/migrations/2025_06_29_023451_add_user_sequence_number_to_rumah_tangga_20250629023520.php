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
        Schema::table('fm_rumah_tangga', function (Blueprint $table) {
            $table->unsignedInteger('user_sequence_number')->nullable()->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fm_rumah_tangga', function (Blueprint $table) {
            //
        });
    }
};
