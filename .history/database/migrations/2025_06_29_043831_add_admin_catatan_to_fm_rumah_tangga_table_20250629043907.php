<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fm_rumah_tangga', function (Blueprint $table) {
            $table->text('admin_catatan')->nullable()->after('status_validasi');
        });
    }

    public function down(): void
    {
        Schema::table('fm_rumah_tangga', function (Blueprint $table) {
            $table->dropColumn('admin_catatan');
        });
    }
};
