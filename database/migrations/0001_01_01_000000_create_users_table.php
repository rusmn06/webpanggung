<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        // 1. Users table
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'user'])->default('user');
            $table->string('avatar')->nullable();
            $table->rememberToken();
            // $table->timestamp('email_verified_at')->nullable(); // jika verifikasi email
            $table->timestamps();
        });

        // 2. Password resets
        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // 3. Sessions
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_resets');
        Schema::dropIfExists('users');
        Schema::dropIfExists('avatar');
    }
}
