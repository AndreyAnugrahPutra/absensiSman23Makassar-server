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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('user_id')->primary();
            $table->string('nip')->unique();
            $table->string('nama');
            $table->date('tgl_lahir');
            $table->enum('jkel', ["Laki-Laki","Perempuan"]);
            $table->string('email')->unique();
            $table->text('password');
            $table->string('no_telp')->unique();
            $table->text('alamat');
            $table->binary('foto_profil')->nullable();
            $table->rememberToken();
            $table->foreignId('level')->references('id_level')->on('tb_level');
            $table->datetime('last_login')->nullable();
            $table->datetime('last_logout')->nullable();
            $table->datetime('created_at');
            $table->datetime('updated_at')->nullable();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignUuid('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
